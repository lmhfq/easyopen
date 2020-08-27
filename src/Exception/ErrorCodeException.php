<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * Time: 下午3:29
 */

namespace Lmh\EasyOpen\Exception;


use Lmh\EasyOpen\Message\ErrorCode;
use Throwable;

class ErrorCodeException extends \InvalidArgumentException
{
    /**
     * @var string
     */
    private $subErrorCode;
    /**
     * @var array
     */
    private $params;

    /**
     * @return string
     */
    public function getSubErrorCode(): string
    {
        return $this->subErrorCode;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }


    public function __construct(int $code = 0, string $subErrorCode = null, array $params = [], Throwable $previous = null)
    {
        $message = ErrorCode::getMessage($code);
        $this->subErrorCode = $subErrorCode;
        $this->params = $params;
        parent::__construct($message, $code, $previous);
    }
}