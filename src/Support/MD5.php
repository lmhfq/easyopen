<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/24
 * Time: 下午5:54
 */

namespace Lmh\EasyOpen\Support;


class MD5
{
    /**
     * @param array $params
     * @param $key
     * @return string
     */
    public function sign(array $params, $key)
    {
        unset($params['sign']);
        ksort($params);
        $params['key'] = $key;
        $params = urldecode(http_build_query($params));
        return strtoupper(md5($params));
    }

    /**
     * @param $content
     * @param $sign
     * @param $appSecret
     * @return bool
     */
    public function verify($content, $sign, $appSecret): bool
    {
        $signStr = $this->sign($content, $appSecret);
        return $signStr == $sign;
    }
}