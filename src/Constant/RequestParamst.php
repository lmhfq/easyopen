<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/20
 * Time: 下午4:59
 */

namespace Lmh\EasyOpen\Constant;


class RequestParamst
{
    /**
     * @var string 请求方法名称
     */
    const METHOD_FIELD = "method";
    /**
     * @var string 请求版本号名称
     */
    const VERSION_FIELD = "version";
    /**
     * @var string 请求APPID名称
     */
    const APP_ID_FIELD = "app_id";
    /**
     * @var string 请求业务参数名称
     */
    const BIZ_CONTENT_FIELD = "biz_content";
    /**
     * @var string 请求时间名称
     */
    const TIMESTAMP_FIELD = "timestamp";
    /**
     * @var string 请求签名名称
     */
    const SIGN_FIELD = "sign";
    /**
     * @var string 请求签名名称
     */
    const NONCE_FIELD = "nonce";
    /**
     * @var string 请求格式名称
     */
    const FORMAT_FIELD = "format";
    /**
     * @var string 请求签名类型名称
     */
    const SIGN_TYPE_FIELD = "sign_type";

}