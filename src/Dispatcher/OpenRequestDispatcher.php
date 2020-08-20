<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/20
 * Time: 上午10:26
 */

namespace lmh\easyopen\Listener;


use Hyperf\Contract\ContainerInterface;
use Hyperf\Dispatcher\AbstractDispatcher;
use Hyperf\Utils\Context;
use Hyperf\Utils\Contracts\Arrayable;
use Psr\Http\Message\ResponseInterface;
use lmh\easyopen\Collector\OpenMappingCollector;
use Psr\Http\Message\ServerRequestInterface;

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
        $request = Context::set(ServerRequestInterface::class, $request);
        $contents = $request->getBody()->getContents();
        //参数校验
        //签名校验
        $contents = json_decode($contents, true);
        /**
         * @var OpenMappingCollector $collector
         */
        $collector = $this->container->get(OpenMappingCollector::class);
        $mapping = $collector->getStaticMapping();
        if (!in_array($params['method'], $mapping)) {

        }
        $callback = $mapping[$params['method']];
        try {
            $reflect = new \ReflectionClass($callback[0]);
        } catch (\Exception  $e) {
        }
        /**
         * @var array|Arrayable|mixed|ResponseInterface $response
         */
        $reflectionMethod = $reflect->getMethod($callback[1]);
        if ($reflectionMethod->isStatic()) {
            $response = call_user_func($callback, $params['content']);
        } else {
            $response = $reflectionMethod->invoke($reflect->newInstance(), $params['content']);
        }
        return $response->withAddedHeader('Server', 'Hyperf');
    }
}