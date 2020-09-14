
# easyopen
一个简单易用的接口开放平台，平台封装了常用的参数校验、结果返回等功能，开发者只需实现业务代码即可。

easyopen的功能类似于[淘宝开放平台](http://open.taobao.com/docs/api.htm?spm=a219a.7629065.0.0.6cQDnQ&apiId=4)，它的所有接口只提供一个url，通过参数来区分不同业务。这样做的好处是接口url管理方便了，平台管理者只需维护好接口参数即可。由于参数的数量是可知的，这样可以在很大程度上进行封装。封装完后平台开发者只需要写业务代码，其它功能可以通过配置来完成。


## 示例
使用非常简单，定义自己的控制器和网关路由，注入OpenRequestDispatcher 即可
```
<?php
namespace app\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Lmh\EasyOpen\Dispatcher\OpenRequestDispatcher;

/**
 * @Controller
 */
class GateWayController
{
    /**
     * @RequestMapping(path="/gateway",methods={"GET","POST"})
     * @param RequestInterface $request
     * @param OpenRequestDispatcher $openRequestDispatcher
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(RequestInterface $request, OpenRequestDispatcher $openRequestDispatcher)
    {
        return $openRequestDispatcher->dispatch($request);
    }
}
```
接口类如下，引入OpenService 和 OpenMapping即可
```
<?php
namespace app\Service\merchant;


use Lmh\EasyOpen\Annotation\OpenService;
use Lmh\EasyOpen\Annotation\OpenMapping;
/**
 * @OpenService()
 */
class MerchantService
{
    /**
     * @OpenMapping(path="merchant.create",methods={"GET","POST"})
     * @param array $params
     * @return string
     */
    public function create(array $params = [])
    {
        return json_encode($params);
    }
}

```


- 请求数据：

```
{
    "method": "merchant.create",
    "app_id": "2016072300007148",
    "sign_type":"MD5",
    "nonce":"xsadasd823mxdjrewrew",
    "sign": "2FF9EE4B7908DF976FD11E405529DD67",
    "version": "1.0",
    "content": {
        "merchant_name": "测试公司",
        "city": "杭州"
    }
}
```
