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
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Hyperf\Utils\Contracts\Arrayable;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Lmh\EasyOpen\Constant\RequestParamsConstant;
use Lmh\EasyOpen\Exception\ErrorCodeException;
use Lmh\EasyOpen\Http\OpenRequestParams;
use Lmh\EasyOpen\Http\OpenResponse;
use Lmh\EasyOpen\Http\OpenResponseResultFactory;
use Lmh\EasyOpen\Message\ErrorCode;
use Lmh\EasyOpen\Message\ErrorSubCode;
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

        //参数校验 TODO
        $this->validate($contents);
        //签名校验 TODO
        $requestParams = new OpenRequestParams();
        $requestParams->method = $contents['method'];

        /**
         * @var OpenMappingCollector $collector
         */
        $collector = $this->container->get(OpenMappingCollector::class);
        $mapping = $collector->getStaticMapping();
        if (!isset($mapping[$contents[RequestParamsConstant::METHOD_NAME]])) {

        }
        $callback = $mapping[$contents[RequestParamsConstant::METHOD_NAME]];

        try {
            $reflect = new \ReflectionClass($callback[0]);
        } catch (\Exception  $e) {

            //TODO
        }
        /**
         * @var array|Arrayable|mixed|ResponseInterface $response
         */
        $reflectionMethod = $reflect->getMethod($callback[1]);
        $parameter = $contents[RequestParamsConstant::BIZ_CONTENT_NAME];
        if ($reflectionMethod->isStatic()) {
            $response = call_user_func($callback, $parameter);
        } else {
            $response = $reflectionMethod->invoke($reflect->newInstance(), $parameter);
        }
        return OpenResponseResultFactory::success($response);
    }

    /**
     * @param array $input
     */
    protected function validate(array $input)
    {

        /**
         * @var ValidatorFactoryInterface $factory
         */
        try {
            $factory = $this->container->get(ValidatorFactoryInterface::class);
        } catch (\Throwable $exception) {
            throw new ErrorCodeException(ErrorCode::SYSTEM_ERROR, ErrorSubCode::UNKNOW_ERROR);
        }
        $rules = [
            RequestParamsConstant::APP_ID_NAME => 'required|max:20',
            RequestParamsConstant::BIZ_CONTENT_NAME => 'required',
        ];
        $messages = [
            RequestParamsConstant::APP_ID_NAME . '.required' => ErrorSubCode::MISSING_APP_ID,
            RequestParamsConstant::BIZ_CONTENT_NAME . '.required' => ErrorSubCode::MISSING_BIZ_CONTENT,
        ];

        $validator = $factory->make($input, $rules, $messages);
        if ($validator->fails()) {
            //new \Hyperf\Utils\MessageBag();
            $messages = $validator->errors()->getMessages();
            foreach ($messages as $message) {
                throw new ErrorCodeException(ErrorCode::INVALID_PARAMETER, $message[0]);
            }
            // throw new ErrorCodeException(ErrorCode::INVALID_PARAMETER, $messages[]);
        }
    }

}