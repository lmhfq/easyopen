
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
    "sign": "hq1j1tBCQkJCecJbU1I+9VyRDPyjzBjT6ok7S6QWT72ebJ7nNmTJFy5GLh0Zw9lyciT/1Qd7dDeF RVwqxHW10xzv8qBqjGNq4S1TH1sEukMBk7emkD78javGS0m+6KIEtK1K5gePgqy3HRpxqrD58jqZIOu5FIxY5m 5a93CJC/o=",
    "version": "1.0",
    "content": {
        "body": "scsafcsa",
        "total_fee": "0.04",
        "spbill_create_ip": "120.0.0.1",
        "auth_code": "5",
        "store_id": "9",
        "out_trade_no": "9"
    }
}
```


##待完善点
