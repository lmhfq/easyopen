<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/21
 * Time: 下午5:57
 */

namespace Lmh\EasyOpen\Http;


use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Exception\Http\EncodingException;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Codec\Xml;
use Hyperf\Utils\Contracts\Arrayable;
use Hyperf\Utils\Contracts\Jsonable;
use Hyperf\Utils\Contracts\Xmlable;
use Psr\Http\Message\ResponseInterface;

class OpenResponse
{

    /**
     * Format data to JSON and return data with Content-Type:application/json header.
     *
     * @param ResponseInterface $response
     * @param array|Arrayable|Jsonable $data
     * @return ResponseInterface
     */
    public static function json(ResponseInterface $response, $data): ResponseInterface
    {
        $data = static::toJson($data);
        return $response->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream($data));
    }

    /**
     * Format data to XML and return data with Content-Type:application/xml header.
     *
     * @param ResponseInterface $response
     * @param array|Arrayable|Xmlable $data
     * @param string $root
     * @return ResponseInterface
     */
    public static function xml(ResponseInterface $response, $data, string $root = 'root'): ResponseInterface
    {
        $data = static::toXml($data, null, $root);
        return $response->withAddedHeader('content-type', 'application/xml; charset=utf-8')
            ->withBody(new SwooleStream($data));
    }

    /**
     * Format data to a string and return data with content-type:text/plain header.
     *
     * @param ResponseInterface $response
     * @param mixed $data will transfer to a string value
     * @return ResponseInterface
     */
    public static function raw(ResponseInterface $response, $data): ResponseInterface
    {
        return $response
            ->withAddedHeader('content-type', 'text/plain; charset=utf-8')
            ->withBody(new SwooleStream((string)$data));
    }

    /**
     * @param array|Arrayable|Xmlable $data
     * @param null|mixed $parentNode
     * @param mixed $root
     * @return string
     * @throws EncodingException when the data encoding error
     */
    protected static function toXml($data, $parentNode = null, $root = 'root'): string
    {
        return Xml::toXml($data, $parentNode, $root);
    }

    /**
     * @param array|Arrayable|Jsonable $data
     * @return string
     * @throws EncodingException when the data encoding error
     */
    protected static function toJson($data): string
    {
        try {
            $result = Json::encode($data);
        } catch (\Throwable $exception) {
            throw new EncodingException($exception->getMessage(), $exception->getCode());
        }
        return $result;
    }
}