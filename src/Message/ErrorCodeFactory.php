<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * Time: 下午2:32
 */

namespace Lmh\EasyOpen\Message;

/**
 * Class ErrorCodeFactory
 * @package Lmh\EasyOpen\Message
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * @method OpenResponseResult $getError(int $errorCode, string $subErrorCode)
 */
class ErrorCodeFactory
{
    /**
     * @param int $errorCode
     * @param string $subErrorCode
     * @return OpenResponseResult
     */
    public static function getError(int $errorCode, string $subErrorCode)
    {
        $openResponseResult = new OpenResponseResult();
        $openResponseResult->setCode($errorCode);
        $openResponseResult->setMsg(ErrorCode::getMessage($errorCode));
        $openResponseResult->setSubCode($subErrorCode);
        $openResponseResult->setSubMsg(ErrorSubCode::getMessage($subErrorCode));
        return $openResponseResult;
    }
}