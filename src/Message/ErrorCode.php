<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * Time: 下午2:32
 */

namespace Lmh\EasyOpen\Message;


use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * Class ErrorCode
 * @package Lmh\EasyOpen\Message
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * @method static string getMessage(int $error_code)
 * @Constants
 */
class ErrorCode extends AbstractConstants
{
    const ISV = 'isv';
    /**
     * @Message("success")
     */
    const SUCCESS = 10000;
    /**
     * @Message("服务不可用")
     */
    const SYSTEM_ERROR = 20000;
    /**
     * @Message("授权权限不足")
     */
    const ACCESS_FORBIDDEN = 20001;
    /**
     * @Message("缺少必选参数")
     */
    const  MISSING_PARAMETER = 40001;
    /**
     * @Message("非法的参数")
     */
    const  INVALID_PARAMETER = 40002;
    /**
     * @Message("业务处理失败")
     */
    const  BUSINESS_ERROR = 40004;
}