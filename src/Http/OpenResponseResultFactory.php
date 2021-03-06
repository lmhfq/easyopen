<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * Time: 下午3:45
 */

namespace Lmh\EasyOpen\Http;


use Lmh\EasyOpen\Message\ErrorCode;
use Lmh\EasyOpen\Message\ErrorSubCode;
use Psr\Http\Message\ResponseInterface;

class OpenResponseResultFactory
{
    /**
     * 成功返回
     * @param array $data
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public static function success(array $data, ?ResponseInterface $response = null): ResponseInterface
    {
        $openResponseResult = new OpenResponseResult();
        $openResponseResult->setCode(ErrorCode::SUCCESS);
        $openResponseResult->setMsg(ErrorCode::getMessage(ErrorCode::SUCCESS));
        $openResponseResult->setResponse($data);
        return OpenResponse::json($openResponseResult->toArray(), $response);
    }


    /**
     * 失败返回
     * @param int $errorCode
     * @param string $subErrorCode
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public static function error(int $errorCode, string $subErrorCode, array $params = [], ?ResponseInterface $response = null): ResponseInterface
    {
        $openResponseResult = new OpenResponseResult();
        $openResponseResult->setCode($errorCode);
        $openResponseResult->setMsg(ErrorCode::getMessage($errorCode) ?? '服务不可用');
        $openResponseResult->setSubCode($subErrorCode);
        //常量不支持空数组，否则报错
        $subMsg = ErrorSubCode::getMessage($subErrorCode, $params ?: ['']) ?? '未知错误';
        $openResponseResult->setSubMsg($subMsg);
        return OpenResponse::json($openResponseResult->toArray(), $response);
    }

    /**
     * 失败返回
     * @param int $errorCode
     * @param string $subErrorCode
     * @param string $subErrorMessage
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public static function fail(int $errorCode, string $subErrorCode, string $subErrorMessage, ?ResponseInterface $response = null): ResponseInterface
    {
        $openResponseResult = new OpenResponseResult();
        $openResponseResult->setCode($errorCode);
        $openResponseResult->setMsg(ErrorCode::getMessage($errorCode) ?? '服务不可用');
        $openResponseResult->setSubCode($subErrorCode);
        $openResponseResult->setSubMsg($subErrorMessage);
        return OpenResponse::json($openResponseResult->toArray(), $response);
    }
}