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
    private $subCode;
    /**
     * @var string 明细返回码描述
     */
    private $subMsg;
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
    public function getSubCode(): ?string
    {
        return $this->subCode;
    }

    /**
     * @param string $subCode
     */
    public function setSubCode(string $subCode): void
    {
        $this->subCode = $subCode;
    }

    /**
     * @return string
     */
    public function getSubMsg(): ?string
    {
        return $this->subMsg;
    }

    /**
     * @param string $subMsg
     */
    public function setSubMsg(string $subMsg): void
    {
        $this->subMsg = $subMsg;
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
        $data = [
            'code' => $this->getCode(),
            'msg' => $this->getMsg(),
        ];
        if ($this->getSubCode()) {
            $data['sub_error_code'] = $this->getSubCode();
        }
        if ($this->getSubMsg()) {
            $data['sub_msg'] = $this->getSubMsg();
        }
        if ($this->getResponse()) {
            $data['response'] = $this->getResponse();
        }
        return $data;
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