<?php
declare(strict_types=1);

namespace App\Utils;

use Hyperf\Redis\Redis;

class RedisLock
{
    /**
     * redis key 模板
     */
    const LOCK_KEY_TEMPLATE = 'lock_%s';

    /**
     * 默认超时时间
     */
    const DEFAULT_EXPIRE_TIME = 120;

    /**
     * 用于生成唯一的锁ID的redis Key
     */
    const LOCK_UNIQUE_ID = 'lock_unique_id';

    /**
     * 加锁
     *
     * @param $keyId 加锁目标唯一识别Key
     * @param int $expireTime 锁过期时间
     * @return bool | int 加锁成功后， 返回唯一锁ID，加锁失败返回false
     */
    public static function addLock($keyId, $expireTime = self::DEFAULT_EXPIRE_TIME)
    {
        // 参数校验
        if (empty($keyId) || $expireTime <=0) {
            return false;
        }

        // 获取Redis连接
        $conn = self::getConn();

        //生成唯一锁ID，解锁需持有此ID
        $uniqueLockId = self::getUniqueLockId();

        $appEnv = env('APP_ENV', 'dev');
        $lock_key_template = $appEnv . "_" . self::LOCK_KEY_TEMPLATE;

        //根据模板，结合目标KeyID，生成唯一Redis key（一般来说，目标KeyID在业务中系统中唯一的）
        $strKey = sprintf($lock_key_template, $keyId);

        // 加锁（通过Redis setnx指令实现，从Redis 2.6.12开始，通过set指令可选参数也可以实现setnx，
        // 同时可原子化地设置超时时间）
        $bool = $conn->set($strKey, $uniqueLockId, ['nx', 'ex' => $expireTime]);
        logger()->info("添加锁：".$strKey);
        return $bool ? $uniqueLockId : $bool;
    }

    /**
     * 释放锁
     *
     * @param $keyId 释放锁目标唯一识别Key
     * @param $lockId 添加锁时返回的唯一锁ID
     * @return bool
     */
    public static function releaseLock($keyId, $lockId)
    {
        // 参数校验
        if (empty($keyId) || empty($lockId)) {
            return false;
        }

        // 获取Redis连接
        $conn = self::getConn();

        $appEnv = env('APP_ENV', 'dev');
        $lock_key_template = $appEnv . "_" . self::LOCK_KEY_TEMPLATE;

        //生成Redis key
        $strKey = sprintf($lock_key_template, $keyId);

        // 监听Redis key防止在【比对lock id】与【解锁事务执行过程中】被修改或删除，
        // 提交事务后会自动取消监控，其他情况需手动解除监控
        $conn->watch($strKey);

        if ($lockId == $conn->get($strKey)) {
            $conn->multi()->del($strKey)->exec();
            logger()->info("释放锁:".$strKey);
            return true;
        }
        $conn->unwatch();
        return false;
    }

    /**
     * 连接Redis
     *
     * @return Redis
     */
    public static function getConn()
    {
        $redis = make(Redis::class);
        return $redis;
    }

    /**
     * 生成锁唯一ID（通过Redis incr指令实现简易版本，可结合日期、时间戳、取余、字符串填充、随机数等函数，生成指定位数唯一ID）
     *
     * @return mixed
     */
    public static function getUniqueLockId()
    {
        $appEnv = env('APP_ENV', 'dev');
        $lock_unique_id = $appEnv . "_" . self::LOCK_UNIQUE_ID;
        return self::getConn()->incr($lock_unique_id);
    }
}