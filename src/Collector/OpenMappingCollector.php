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
            //$method = strtoupper($httpMethod);
            $this->staticMapping[$route] = $handler;
        }
    }

    /**
     * @param $route
     * @return bool
     */
    public function hasMapping($route): bool
    {
        if (isset($this->staticMapping[$route])) {
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
     * @param $route
     * @return array|null
     */
    public function getMapping($route): ?array
    {
        if (isset($this->staticMapping[$route])) {
            return $this->staticMapping[$route];
        }
        return null;
    }
}