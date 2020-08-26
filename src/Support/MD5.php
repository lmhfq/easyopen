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
     * @param string $encryptMethod
     * @return string
     */
    public function sign(array $params, $key, $encryptMethod = 'md5')
    {
        unset($params['sign']);
        ksort($params);
        $attributes['key'] = $key;
        return strtoupper(call_user_func_array($encryptMethod, [urldecode(http_build_query($attributes))]));
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