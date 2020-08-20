<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/20
 * Time: 上午10:26
 */

namespace lmh\easyopen\Dispatcher;


use Hyperf\Contract\ContainerInterface;
use Hyperf\Dispatcher\AbstractDispatcher;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Hyperf\Utils\Contracts\Arrayable;
use Psr\Http\Message\ResponseInterface;
use lmh\easyopen\Collector\OpenMappingCollector;

class OpenRequestDispatcher extends AbstractDispatcher
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function dispatch(...$params)
    {
        [$request,] = $params;
        $contents = $request->getBody()->getContents();
        //参数校验 TODO
        //签名校验 TODO
        $contents = json_decode($contents, true);

        /**
         * @var OpenMappingCollector $collector
         */
        $collector = $this->container->get(OpenMappingCollector::class);
        $mapping = $collector->getStaticMapping();
        if (!isset($mapping[$contents['method']])) {

        }
        $callback = $mapping[$contents['method']];

        try {
            $reflect = new \ReflectionClass($callback[0]);
        } catch (\Exception  $e) {

            //TODO
        }
        /**
         * @var array|Arrayable|mixed|ResponseInterface $response
         */
        $reflectionMethod = $reflect->getMethod($callback[1]);
        if ($reflectionMethod->isStatic()) {
            $response = call_user_func($callback, $contents['content']);
        } else {
            $response = $reflectionMethod->invoke($reflect->newInstance(), $contents['content']);
        }
        /**
         * @var ResponseInterface $responseInterface
         */
        $responseInterface = Context::get(ResponseInterface::class);
        if (is_string($response)) {
            $response = $responseInterface->withAddedHeader('content-type', 'text/plain')->withBody(new SwooleStream($response));
        }
        return $response->withAddedHeader('Server', 'Hyperf');
    }

}