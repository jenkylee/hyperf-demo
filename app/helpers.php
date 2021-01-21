<?php
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Server\ServerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Str;

/**
 * 容器实例
 *
 */
if (!function_exists('container')) {
    function container()
    {
        return ApplicationContext::getContainer();
    }
}

/**
 * 数据集合
 */
if (!function_exists('collect')) {
    /**
     * Create a collection from the given value.
     *
     * @param  mixed  $value
     * @return \Hyperf\Utils\Collection
     */
    function collect($value = null)
    {
        return new Collection($value);
    }
}

/**
 * redis 客户端实例
 */
if (!function_exists('redis')) {
    function redis()
    {
        return container()->get(Redis::class);
    }
}

/**
 * server 实例 基于 swoole server
 */
if (!function_exists('server')) {
    function server()
    {
        return container()->get(ServerFactory::class)->getServer()->getServer();
    }
}

/**
 * websocket frame 实例
 */
if (!function_exists('frame')) {
    function frame()
    {
        return container()->get(Frame::class);
    }
}

/**
 * websocket 实例
 */
if (!function_exists('websocket')) {
    function websocket()
    {
        return container()->get(WebSocketServer::class);
    }
}

/**
 * 缓存实例 简单的缓存
 */
if (!function_exists('cache')) {
    function cache()
    {
        return container()->get(Psr\SimpleCache\CacheInterface::class);
    }
}

/**
 * 控制台日志
 */
if (!function_exists('stdLog')) {
    function stdLog()
    {
        return container()->get(StdoutLoggerInterface::class);
    }
}

/**
 * 文件日志
 */
if (!function_exists('logger')) {
    /**
     * @param string $name
     * @param string $group
     * @return mixed
     */
    function logger($name = 'app', $group = 'default')
    {
        return container()->get(LoggerFactory::class)->make($name, $group);
    }
}

/**
 * 请求对象
 */
if (!function_exists('request')) {
    function request()
    {
        return container()->get(ServerRequestInterface::class);
    }
}

/**
 * 响应对象
 */
if (!function_exists('response')) {
    function response()
    {
        return container()->get(ResponseInterface::class);
    }
}

if (!function_exists('micro_time')) {
    /**
     * 当前微秒时间
     *
     * @return int
     */
    function micro_time()
    {
        $time = microtime(true);
        $last_time = (int)($time * 1000);

        return $last_time;
    }
}

if (!function_exists('image_to_base64')) {
    /**
     * 普通图片转base64图片
     *
     * @param string $image
     * @return string
     */
    function image_to_base64(string $image)
    {
        $fp = fopen($image, 'r');
        $imageInfo = getimagesize($image);
        $imageData = fread($fp, filesize($image));
        $base64Image = 'data:'.$imageInfo['mime'] . ';base64,' . base64_encode($imageData);

        return $base64Image;
    }
}

/**
 * 获取缩略提地址
 *
 * @param $path
 *
 * @return string
 */
if (!function_exists('thumb_path')) {
    function thumb_path($path, $size)
    {
        //尺寸
        $sizeArr = explode(',', $size);
        $width   = $sizeArr[0];
        $height  = $sizeArr[1];

        //缩略图地址拼接
        $lastEleIndex = strripos($path, '/');
        $filename     = substr($path, $lastEleIndex + 1);
        $thumbDir     = substr($path, 0, $lastEleIndex);
        $thumbDir     = Str::finish($thumbDir, '/') . 'thumb';

        //缩略图地址
        $thumbPath = Str::finish($thumbDir, '/') . $width . '_' . $height . '_' . $filename;

        return $thumbPath;
    }
}

/**
 * cdn oss 图片地址
 *
 * @param $path
 */
if (!function_exists('cdn_oss_img_path')) {
    function cdn_oss_img_path($path, $cdn_url = '', $oss_url = '')
    {
        if (!$path) {
            return "";
        }
        $cdn_url = $cdn_url ? $cdn_url : config('course.cdn_img_path');
        $oss_url = $oss_url ? $oss_url : config('course.oss_img_path');
        //域名追加
        if ($cdn_url) {
            $path = Str::finish($cdn_url, '/') . $path;
        } else {
            $path = Str::finish($oss_url, '/') . $path;
        }

        return $path;
    }
}

if (!function_exists('full_img_path')) {
    /***
     * 创课堂图片全路径
     *
     * @param $path
     * @return string
     */
    function full_img_path($path)
    {
        if (!$path) {
            return '';
        }
        $cdn_url   = config('course.cdn_url');
        $oss_url   = config('course.oss_url');
        $thumb_size = config('course.thumb_lesson_size');
        //创课堂本地图 增加cdn或oss前缀
        if (strpos($path, 'http') === false) {
            //域名追加
            if ($domain_path = cdn_oss_img_path($path)) {
                $path = $domain_path;
            }
            //缩略图
            if ($thumb_size) {
                if ($thumbPath = thumb_path($path, $thumb_size)) {
                    $path = $thumbPath;//缩略图地址
                }
            }

            return $path;
        } else {
            //远程地址
            $oss_addr  = parse_url($oss_url);
            $path_addr = parse_url($path);
            if ($path_addr['host'] == $oss_addr['host']) {
                $url = substr($path_addr['path'], 1);
                if ($domain_path = cdn_oss_img_path($url, $cdn_url, $oss_url)) {
                    $path = $domain_path;
                }
            }

            return $path;
        }
    }
}

if (!function_exists('full_ckt_img_editor_path')) {
    /**
     * 编辑器里的图片地址转换
     *
     * @param $content
     * @param $title
     * @return mixed|string|string[]
     */
    function full_ckt_img_editor_path($content, $title)
    {
        $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
        preg_match_all($preg, $content, $imgArr);
        foreach ($imgArr[0] as $k => $img) {
            if (Str::contains($imgArr[1][$k], "uploads/ueditor")) {
                $path    = "uploads/ueditor" . Str::after($imgArr[1][$k], 'uploads/ueditor');
                $url     = cdn_oss_img_path($path);
                $newImg  = '<img src="' . $url . '" title="' . $title . '" alt="' . $title . '">';
                $content = str_replace($img, $newImg, $content);
            }
        }

        return $content;
    }
}

if (!function_exists('cache_tag')) {
    /**
     * 给缓存key做tag
     *
     * @param $tag
     * @param $key
     * @return bool
     */
    function cache_tag($tag, $key)
    {
        try {
            $tag_key = 'tags:' . $tag."keys";
            $keys_json = cache()->get($tag_key);
            if (!empty($keys_json)) {
                $keys = json_decode($keys_json, true);
                if (!in_array($key, $keys)) {
                    $keys[] = $key;
                    cache()->set($tag_key, json_encode($keys, JSON_UNESCAPED_UNICODE), \App\Constants\CacheConst::CACHE_TTL_DAY);
                }
            } else {
                $keys = [];
                $keys[] = $key;
                cache()->set($tag_key, json_encode($keys, JSON_UNESCAPED_UNICODE), \App\Constants\CacheConst::CACHE_TTL_DAY);
            }
            return true;
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            logger()->info("缓存异常：" . $e->getMessage());
        }

        return false;
    }
}

if (!function_exists('clear_cache_tag')) {
    /**
     * 清除tag标记的缓存
     *
     * @param $tag
     * @return bool
     */
    function clear_cache_tag($tag)
    {
        try {
            $tag_key = 'tags:' . $tag."keys";
            $keys_json = cache()->get($tag_key);
            if (!empty($keys_json)) {
                $keys = json_decode($keys_json, true);
                if ($keys) {
                    cache()->deleteMultiple($keys);
                }
            }
            return true;
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            logger()->info("缓存异常：" . $e->getMessage());
        }

        return false;
    }
}

if (!function_exists('clear_cache_key')) {
    /**
     * 清除$key的缓存
     *
     * @param $key
     * @return bool
     */
    function clear_cache_key($key)
    {
        try {
            cache()->delete($key);
            return true;
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            logger()->info("缓存异常：" . $e->getMessage());
        }

        return false;
    }
}

if (!function_exists('validated_cache_key')) {
    /**
     * 缓存key过滤公共参数
     * 
     * @param array $validated
     * @return bool
     */
    function validated_cache_key(array $validated)
    {
        unset($validated['caller']);
        unset($validated['time_stamp']);
        unset($validated['trace_id']);

        return md5(json_encode($validated, JSON_UNESCAPED_UNICODE));
    }
}