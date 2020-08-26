<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/24
 * Time: 下午4:36
 */

namespace Lmh\EasyOpen\Support;


use Lmh\EasyOpen\Constant\RequestParamsConstant;
use Lmh\EasyOpen\Exception\ErrorCodeException;
use Lmh\EasyOpen\Message\ErrorCode;
use Lmh\EasyOpen\Message\ErrorSubCode;

class SignValidator implements Validator
{
    /**
     * @param $input
     */
    public function validate($input)
    {
        $sign = $content[RequestParamsConstant::SIGN_FIELD] ?? '';
        if ($input[RequestParamsConstant::SIGN_TYPE_FIELD] == 'MD5') {
            if (!isset($input[RequestParamsConstant::APP_SECRET_FIELD])) {
                throw new ErrorCodeException(ErrorCode::MISSING_PARAMETER, ErrorSubCode::MISSING_APP_SECRET);
            }
            $appSecret = $input[RequestParamsConstant::APP_SECRET_FIELD];
            if (!isset($input[RequestParamsConstant::NONCE_FIELD])) {
                throw new ErrorCodeException(ErrorCode::MISSING_PARAMETER, ErrorSubCode::MISSING_NONCE);
            }
            $signHandler = new MD5();
            $verify = $signHandler->verify($input, $sign, $appSecret);

        } else if ($input[RequestParamsConstant::SIGN_TYPE_FIELD] == 'RSA') {
            if (!isset($input[RequestParamsConstant::PUBLIC_KEY_FIELD])) {
                throw new ErrorCodeException(ErrorCode::MISSING_PARAMETER, ErrorSubCode::MISSING_PUBLIC_KEY);
            }
            $publicKey = $input[RequestParamsConstant::PUBLIC_KEY_FIELD];
            $signHandler = new RSA();
            $verify = $signHandler->verify($input, $sign, $publicKey);
        } else {
            throw new ErrorCodeException(ErrorCode::INVALID_PARAMETER, ErrorSubCode::INVALID_SIGNATURE_TYPE);
        }
        if (!$verify) {
            throw new ErrorCodeException(ErrorCode::INVALID_PARAMETER, ErrorSubCode::INVALID_SIGNATURE);
        }
    }
}