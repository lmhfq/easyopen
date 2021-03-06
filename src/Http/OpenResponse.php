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
use Hyperf\Utils\Context;
use Hyperf\Utils\Contracts\Arrayable;
use Hyperf\Utils\Contracts\Jsonable;
use Hyperf\Utils\Contracts\Xmlable;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class OpenResponse
{

    /**
     * Format data to JSON and return data with Content-Type:application/json header.
     *
     * @param ResponseInterface $response
     * @param array|Arrayable|Jsonable|string $data
     * @return ResponseInterface
     */
    public static function json($data, ?ResponseInterface $response = null): ResponseInterface
    {
        if (is_array($data)) {
            $data = static::toJson($data);
        }
        return static::getResponse($response)->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream($data));
    }

    /**
     * Format data to XML and return data with Content-Type:application/xml header.
     *
     * @param ResponseInterface $response
     * @param array|Arrayable|Xmlable|string $data
     * @param string $root
     * @return ResponseInterface
     */
    public static function xml($data, ?ResponseInterface $response = null, string $root = 'root'): ResponseInterface
    {
        if (is_string($data)) {
            $data = Json::decode($data, true);
        }
        $data = static::toXml($data, null, $root);
        return static::getResponse($response)->withAddedHeader('content-type', 'application/xml; charset=utf-8')
            ->withBody(new SwooleStream($data));
    }

    /**
     * Format data to a string and return data with content-type:text/plain header.
     *
     * @param ResponseInterface $response
     * @param mixed $data will transfer to a string value
     * @return ResponseInterface
     */
    public static function raw($data, ?ResponseInterface $response = null): ResponseInterface
    {
        return static::getResponse($response)
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
        } catch (Throwable $exception) {
            throw new EncodingException($exception->getMessage(), $exception->getCode());
        }
        return $result;
    }

    /**
     * @param ResponseInterface|null $response
     * @return mixed
     */
    protected static function getResponse(?ResponseInterface $response = null)
    {
        if ($response) {
            return $response;
        }
        return Context::get(ResponseInterface::class);
    }
}