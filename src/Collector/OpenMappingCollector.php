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

    public function addMapping($httpMethod, $route, $handler)
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

    public function hasRoute($method, $route): bool
    {
        if (isset($this->staticMapping[$method][$route])) {
            return true;
        }
        return false;
    }

    public function getStaticMapping()
    {
        return $this->staticMapping;
    }


    public function getMapping($method, $route)
    {
        if (isset($this->staticMapping[$method][$route])) {
            return $this->staticMapping[$method][$route];
        }
        return null;
    }
}