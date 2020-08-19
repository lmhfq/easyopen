<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/19
 * Time: 下午9:53
 */

namespace lmh\easyopen;


use Hyperf\Contract\ContainerInterface;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use lmh\easyopen\Collector\OpenMappingCollector;

class OpenController
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @RequestMapping(path="",methods={"GET","POST"})
     * @throws \ReflectionException
     */
    public function index(RequestInterface $request)
    {
        $content = $request->getBody()->getContents();
        //参数校验
        //签名校验
        $params = json_decode($content, true);
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
        $reflectionMethod = $reflect->getMethod($callback[1]);
        if ($reflectionMethod->isStatic()) {
            $result = call_user_func($callback, $params['content']);
        } else {
            $result = $reflectionMethod->invoke($reflect->newInstance(), $params['content']);
        }
        return $result;
    }
}