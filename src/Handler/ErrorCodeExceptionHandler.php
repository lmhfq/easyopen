<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * Time: 下午3:43
 */

namespace Lmh\EasyOpen\Handler;


use Hyperf\ExceptionHandler\ExceptionHandler;
use Lmh\EasyOpen\Exception\ErrorCodeException;
use Lmh\EasyOpen\Http\OpenResponseResultFactory;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ErrorCodeExceptionHandler extends ExceptionHandler
{
    /**
     * @param Throwable $throwable
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 判断被捕获到的异常是希望被捕获的异常
        if ($throwable instanceof ErrorCodeException) {
            // 阻止异常冒泡
            $this->stopPropagation();
            return OpenResponseResultFactory::error($throwable->getCode(), $throwable->getSubErrorCode(), $response);
        }
        // 交给下一个异常处理器
        return $response;
    }

    /**
     * 判断该异常处理器是否要对该异常进行处理
     * @param Throwable $throwable
     * @return bool
     */
    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}