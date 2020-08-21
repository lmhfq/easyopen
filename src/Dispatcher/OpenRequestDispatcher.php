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
use Hyperf\Validation\ValidatorFactory;
use Lmh\EasyOpen\Constant\RequestParamsConstant;
use Lmh\EasyOpen\OpenRequestParams;
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
        //$this->validate($contents);
        //签名校验 TODO
        $requestParams = new OpenRequestParams();
        $requestParams->method = $contents['method'];

        /**
         * @var OpenMappingCollector $collector
         */
        $collector = $this->container->get(OpenMappingCollector::class);
        $mapping = $collector->getStaticMapping();
        if (!isset($mapping[$contents['method']])) {

        }
        $callback = $mapping[$contents['method']];

        try {
            $reflect = new \ReflectionClass($callback[0]);
        } catch (\Exception  $e) {

            //TODO
        }
        /**
         * @var array|Arrayable|mixed|ResponseInterface $response
         */
        $reflectionMethod = $reflect->getMethod($callback[1]);
        if ($reflectionMethod->isStatic()) {
            $response = call_user_func($callback, $contents['content']);
        } else {
            $response = $reflectionMethod->invoke($reflect->newInstance(), $contents['content']);
        }
        /**
         * @var ResponseInterface $responseInterface
         */
        $responseInterface = Context::get(ResponseInterface::class);
        if (is_string($response)) {
            $response = $responseInterface->withAddedHeader('content-type', 'text/plain')->withBody(new SwooleStream($response));
        }
        return $response->withAddedHeader('Server', 'Hyperf');
    }

    /**
     * @param array $input
     */
    protected function validate(array $input)
    {
        /**
         * @var ValidatorFactory $factory
         */
        try {
            $factory = $this->container->get(ValidatorFactoryInterface::class);
        }catch (\Exception $exception){

        }

      
        $rules = [
            RequestParamsConstant::APP_ID_NAME => 'required|max:20',
            RequestParamsConstant::BIZ_CONTENT_NAME => 'required',
        ];
        $messages = [
//            RequestParamsConstant::APP_ID_NAME . '.required' => 'A title is required',
//            RequestParamsConstant::BIZ_CONTENT_NAME . '.required' => 'A message is required',
        ];
        $validator = $factory->make($input, $rules);
        if (!$validator->passes()) {
            $validator->errors();
            var_dump(1);exit;
        }
    }

}