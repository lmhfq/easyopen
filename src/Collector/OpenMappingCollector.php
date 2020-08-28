<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/19
 * Time: 下午7:10
 */

namespace Lmh\EasyOpen\Collector;


class OpenMappingCollector
{
    /**
     * @var array
     */
    protected $staticMapping = [];

    /**
     * @param array|string $httpMethod
     * @param string $route
     * @param array $handler
     */
    public function addMapping($httpMethod, string $route, $handler)
    {
        if (is_array($httpMethod)) {
            foreach ($httpMethod as $method) {
                //$method = strtoupper($method);
                $this->staticMapping[$route] = $handler;
            }
        } else {
            // $method = strtoupper($httpMethod);
            $this->staticMapping[$route] = $handler;
        }
    }

    /**
     * @param $method
     * @param $route
     * @return bool
     */
    public function hasRoute($method, $route): bool
    {
        if (isset($this->staticMapping[$method][$route])) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getStaticMapping()
    {
        return $this->staticMapping;
    }

    /**
     * @param $method
     * @param $route
     * @return mixed|null
     */
    public function getMapping($method, $route)
    {
        if (isset($this->staticMapping[$method][$route])) {
            return $this->staticMapping[$method][$route];
        }
        return null;
    }
}