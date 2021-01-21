<?php
declare(strict_types=1);
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 *
 * Class CacheConst
 *
 * @package App\Constants
 */
class CacheConst extends AbstractConstants
{
    /**
     * 文章详情的数据缓存前缀
     *
     */
    const ARTICLE_DETAIL_PREFIX = 'article:detail:';

    /**
     * 缓存1天
     */
    const CACHE_TTL_DAY = 386400;
    /**
     * 缓存1小时
     */
    const CACHE_TTL_HOUR = 3600;
    /**
     * 缓存1分钟
     */
    const CACHE_TTL_MIN = 60;
}