<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * Time: 下午2:52
 */

namespace Lmh\EasyOpen\Message;


use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * Class SubErrorCode
 * @package Lmh\EasyOpen\Message
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * @method static string getMessage(string $error_code)
 * @Constants
 */
class ErrorSubCode extends AbstractConstants
{
    /**
     * @Message("服务暂不可用（业务系统不可用）")
     */
    const UNKNOW_ERROR = 'unknow_error';
    /**
     * @Message("缺少appId参数")r
     */
    const MISSING_APP_ID = 'missing_app_id';
    /**
     * @Message("缺少方法名参数")
     */
    const MISSING_METHOD = 'missing_method';
    /**
     * @Message("缺少签名参数")
     */
    const MISSING_SIGNATURE = 'missing_signature';
    /**
     * @Message("缺少签名类型参数")
     */
    const MISSING_SIGNATURE_TYPE = 'missing_signature_type';
    /**
     * @Message("缺少签名配置")
     */
    const MISSING_SIGNATURE_KEY = 'missing_signature_key';
    /**
     * @Message("缺少时间戳参数")
     */
    const MISSING_TIMESTAMP = 'missing_timestamp';
    /**
     * @Message("缺少版本参数")
     */
    const MISSING_VERSION = 'missing_version';
    /**
     * @Message("缺少业务参数")
     */
    const MISSING_BIZ_CONTENT = 'missing_biz_content';
    /**
     * @Message("参数无效")
     */
    const INVALID_PARAMETER = 'invalid_parameter';
    /**
     * @Message("无效的appId参数")
     */
    const INVALID_APP_ID = 'invalid_app_id';
    /**
     * @Message("不存在的方法名")
     */
    const INVALID_METHOD = 'invalid_method';
    /**
     * @Message("无效的数据格式")
     */
    const INVALID_BIZ_CONTENT = 'invalid_biz_content';
    /**
     * @Message("无效签名")
     */
    const INVALID_SIGNATURE = 'invalid_signature';
    /**
     * @Message("无效的签名类型")
     */
    const INVALID_SIGNATURE_TYPE = 'invalid_signature_type';
}