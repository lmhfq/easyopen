<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * Time: 下午2:54
 */

namespace Lmh\EasyOpen\Http;

use Hyperf\Utils\Contracts\Arrayable;
use Hyperf\Utils\Contracts\Jsonable;

/**
 * Class OpenResponseResult
 * @package Lmh\EasyOpen\Message
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 */
class OpenResponseResult implements Arrayable, Jsonable
{
    /**
     * @var int 返回码
     */
    private $code;
    /**
     * @var string 返回码描述
     */
    private $msg;
    /**
     * @var string 明细返回码
     */
    private $sub_code;
    /**
     * @var string 明细返回码描述
     */
    private $sub_msg;
    /**
     * @var array
     */
    private $response;

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     */
    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return string
     */
    public function getSubCode(): string
    {
        return $this->sub_code;
    }

    /**
     * @param string $sub_code
     */
    public function setSubCode(string $sub_code): void
    {
        $this->sub_code = $sub_code;
    }

    /**
     * @return string
     */
    public function getSubMsg(): string
    {
        return $this->sub_msg;
    }

    /**
     * @param string $sub_msg
     */
    public function setSubMsg(string $sub_msg): void
    {
        $this->sub_msg = $sub_msg;
    }

    /**
     * @return array
     */
    public function getResponse(): ?array
    {
        return $this->response;
    }

    /**
     * @param array $response
     */
    public function setResponse(?array $response): void
    {
        $this->response = $response;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'msg' => $this->getMsg(),
            'sub_error_code' => $this->getSubCode(),
            'sub_msg' => $this->getSubMsg(),
            'response' => $this->getResponse()
        ];
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Convert the object into something JSON serializable.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}