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
use lmh\easyopen\Annotation\OpenMapping;
use lmh\easyopen\Annotation\OpenService;
use lmh\easyopen\Collector\OpenMappingCollector;

/**
 * @Listener
 */
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
        // TODO: Implement process() method.
        /**
         * @var OpenMappingCollector $collector
         */
        $collector = $this->container->get(OpenMappingCollector::class);
        $annotationMethods = AnnotationCollector::getMethodsByAnnotation(OpenMapping::class);
        /**
         * @var OpenMapping $annotation
         */
        foreach ($annotationMethods as $annotationMethod) {
            $class = $annotationMethod['class'];
            $method = $annotationMethod['method'];
            $classAnnotation = AnnotationCollector::getClassAnnotation($class, OpenService::class);
            if ($classAnnotation == null) {
                continue;
            }
            $annotation = $annotationMethod['annotation'];
            $collector->addMapping($annotation->methods, $annotation->path, [$class, $method]);
        }
    }
}