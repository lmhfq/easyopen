<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/19
 * Time: 下午7:03
 */

namespace lmh\easyopen\Listener;

use Hyperf\Contract\ContainerInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\Utils\Str;
use lmh\easyopen\Annotation\OpenMapping;
use lmh\easyopen\Annotation\OpenService;
use lmh\easyopen\Collector\OpenMappingCollector;

class OpenMappingListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public function process(object $event)
    {
        /**
         * @var OpenMappingCollector $collector
         * @var OpenMapping $annotation
         */
        $collector = $this->container->get(OpenMappingCollector::class);
        $annotationMethods = AnnotationCollector::getMethodsByAnnotation(OpenMapping::class);
        foreach ($annotationMethods as $annotationMethod) {
            $class = $annotationMethod['class'];
            $method = $annotationMethod['method'];
            $classAnnotation = AnnotationCollector::getClassAnnotation($class, OpenService::class);
            if ($classAnnotation == null) {
                continue;
            }
            $prefix = $classAnnotation->prefix;
            if ($prefix) {
                if (Str::contains($prefix, '/')) {
                    $prefix = str_replace('/', '.', $prefix);
                    $prefix = rtrim($prefix, '.');
                }
                $prefix .= ".";
            }
            $annotation = $annotationMethod['annotation'];
            $path = $prefix . $annotation->path;
            $collector->addMapping($annotation->methods, $path, [$class, $method]);
        }
    }
}