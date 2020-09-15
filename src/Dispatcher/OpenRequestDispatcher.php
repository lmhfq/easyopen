<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/20
 * Time: 上午10:26
 */

namespace Lmh\EasyOpen\Dispatcher;


use Exception;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Dispatcher\AbstractDispatcher;
use Hyperf\Utils\Contracts\Arrayable;
use Lmh\EasyOpen\Collector\OpenMappingCollector;
use Lmh\EasyOpen\Constant\RequestParamst;
use Lmh\EasyOpen\Exception\ErrorCodeException;
use Lmh\EasyOpen\Handler\ValidatorHandler;
use Lmh\EasyOpen\Http\OpenResponseResultFactory;
use Lmh\EasyOpen\Message\ErrorCode;
use Lmh\EasyOpen\Message\ErrorSubCode;
use Lmh\EasyOpen\OpenValidatorInterface;
use Lmh\EasyOpen\Support\ParamsValidator;
use Lmh\EasyOpen\Support\SignValidator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;

class OpenRequestDispatcher extends AbstractDispatcher
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ValidatorHandler
     */
    private $validatorHandler;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->validatorHandler = $this->container->get(ValidatorHandler::class);
        $this->validatorHandler->addValidator(new ParamsValidator());
        $this->validatorHandler->addValidator(new SignValidator());
    }

    /**
     * 添加自定义验证器具
     * @param OpenValidatorInterface $validator
     */
    public function addValidator(OpenValidatorInterface $validator)
    {
        $this->validatorHandler->addValidator($validator);
    }

    /**
     * @param mixed ...$params
     * @return ResponseInterface
     */
    public function dispatch(...$params): ResponseInterface
    {
        [$request,] = $params;
        /**
         * @var RequestInterface $request
         */
        $contents = $request->getBody()->getContents();
        $contents = json_decode($contents, true);
        /**
         * @var ValidatorHandler $validatorHandler
         */
        $this->validatorHandler->run($contents);
        /**
         * @var OpenMappingCollector $collector
         */
        $collector = $this->container->get(OpenMappingCollector::class);
        $callback = $collector->getMapping($contents[RequestParamst::METHOD_FIELD]);
        if ($callback == null) {
            throw new ErrorCodeException(ErrorCode::INVALID_PARAMETER, ErrorSubCode::INVALID_METHOD);
        }
        [$controller, $action] = $callback;
        /**
         * @var array|Arrayable|mixed|ResponseInterface $response
         */
        try {
            $reflect = new ReflectionClass($controller);
            $reflectionMethod = $reflect->getMethod($action);
        } catch (Exception  $e) {
            throw new ErrorCodeException(ErrorCode::SYSTEM_ERROR, ErrorSubCode::UNKNOW_ERROR);
        }
        $bizContent = $contents[RequestParamst::BIZ_CONTENT_FIELD];
        if ($reflectionMethod->isStatic()) {
            $response = call_user_func($callback, $bizContent);
        } else {
            $response = $reflectionMethod->invoke($reflect->newInstance(), $bizContent);
        }
        return OpenResponseResultFactory::success($response);
    }
}