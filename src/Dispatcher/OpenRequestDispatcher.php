<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/20
 * Time: 上午10:26
 */

namespace Lmh\EasyOpen\Dispatcher;


use Hyperf\Contract\ContainerInterface;
use Hyperf\Dispatcher\AbstractDispatcher;
use Hyperf\Utils\Contracts\Arrayable;
use Lmh\EasyOpen\Constant\RequestParamsConstant;
use Lmh\EasyOpen\Exception\ErrorCodeException;
use Lmh\EasyOpen\Handler\ValidatorHandler;
use Lmh\EasyOpen\Http\OpenResponseResultFactory;
use Lmh\EasyOpen\Message\ErrorCode;
use Lmh\EasyOpen\Message\ErrorSubCode;
use Lmh\EasyOpen\Support\ParamsValidator;
use Lmh\EasyOpen\Support\SignValidator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Lmh\EasyOpen\Collector\OpenMappingCollector;

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
        /**
         * @var RequestInterface $request
         */
        $contents = $request->getBody()->getContents();
        $contents = json_decode($contents, true);  
        //参数校验
        /**
         * @var ValidatorHandler $validatorHandler
         */
        $validatorHandler = $this->container->get(ValidatorHandler::class);
        $validatorHandler->addValidator(new ParamsValidator());
        $validatorHandler->addValidator(new SignValidator());
        $validatorHandler->run($contents);
        /**
         * @var OpenMappingCollector $collector
         */
        $collector = $this->container->get(OpenMappingCollector::class);
        $mapping = $collector->getStaticMapping();
        if (!isset($mapping[$contents[RequestParamsConstant::METHOD_FIELD]])) {
            throw new ErrorCodeException(ErrorCode::INVALID_PARAMETER, ErrorSubCode::INVALID_METHOD);
        }
        $callback = $mapping[$contents[RequestParamsConstant::METHOD_FIELD]];
        /**
         * @var array|Arrayable|mixed|ResponseInterface $response
         */
        try {
            $reflect = new \ReflectionClass($callback[0]);
            $reflectionMethod = $reflect->getMethod($callback[1]);
        } catch (\Exception  $e) {
            throw new ErrorCodeException(ErrorCode::SYSTEM_ERROR, ErrorSubCode::UNKNOW_ERROR);
        }
        $bizContent = $contents[RequestParamsConstant::BIZ_CONTENT_FIELD];
        var_dump($reflectionMethod->isStatic());exit;
        if ($reflectionMethod->isStatic()) {
            $response = call_user_func($callback, $bizContent);
        } else {
            $response = $reflectionMethod->invoke($reflect->newInstance(), $bizContent);
        }
        return OpenResponseResultFactory::success($response);
    }
}