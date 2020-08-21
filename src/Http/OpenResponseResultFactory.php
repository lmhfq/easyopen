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
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public static function success(ResponseInterface $response): ResponseInterface
    {
        $openResponseResult = new OpenResponseResult();
        $openResponseResult->setCode(ErrorCode::SUCCESS);
        $openResponseResult->setMsg(ErrorCode::getMessage(ErrorCode::SUCCESS));
        $openResponseResult->setResponse([]);
        return $response->withStatus(200)->json($openResponseResult->toArray());
    }


    /**
     * 失败返回
     * @param int $errorCode
     * @param string $subErrorCode
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public static function error(int $errorCode, string $subErrorCode, ResponseInterface $response): ResponseInterface
    {
        $openResponseResult = new OpenResponseResult();
        $openResponseResult->setCode($errorCode);
        $openResponseResult->setMsg(ErrorCode::getMessage($errorCode));
        $openResponseResult->setSubCode($subErrorCode);
        $openResponseResult->setSubMsg(ErrorSubCode::getMessage($subErrorCode));
        return $response->withStatus(200)->json($openResponseResult->toArray());
    }
}