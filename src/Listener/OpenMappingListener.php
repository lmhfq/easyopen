<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/19
 * Time: 下午7:03
 */

namespace Lmh\EasyOpen\Listener;

use Hyperf\Contract\ContainerInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\Utils\Str;
use Lmh\EasyOpen\Annotation\OpenMapping;
use Lmh\EasyOpen\Annotation\OpenService;
use Lmh\EasyOpen\Collector\OpenMappingCollector;

class OpenMappingListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var StdoutLoggerInterface
     */
    private $logger;

    public function __construct(ContainerInterface $container, StdoutLoggerInterface $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
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
            $this->logger->info(sprintf('Mapped "{[%s ],methods=[%s]}" on %s', $path, implode(',', $annotation->methods), $class . '@' . $method));
        }
    }
}