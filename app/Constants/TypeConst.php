<?php

declare(strict_types=1);
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 *
 * Class TypeConst
 *
 * @package App\Constants
 */
class TypeConst extends AbstractConstants
{
    /**
     * @Message("无效")
     */
    const STATUS_INVALID = 0;
    /**
     * @Message("有效")
     */
    const STATUS_VALID = 1;
}