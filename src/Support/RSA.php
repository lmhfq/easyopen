<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/24
 * Time: 下午5:54
 */

namespace Lmh\EasyOpen\Support;

class RSA
{
    /**
     * 流程
     * 1、删除参数前后空格和空值参数
     * 2、删除sign
     * 3、排序
     * 4、签名
     * @param $content
     * @param $privateKey
     * @return string
     */
    public function sign($content, $privateKey)
    {
        $content = $this->getSignContent($content);
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        openssl_sign($content, $sign, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }

    /**
     * 流程
     * 1、从报文取出签名sign并值解密
     * 2、删除sign
     * 3、排序
     * 5、验证
     * @param $content
     * @param $sign
     * @param $publicKey
     * @return bool
     */
    public function verify($content, $sign, $publicKey): bool
    {
        $content = $this->getSignContent($content);
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($publicKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        return openssl_verify($content, base64_decode($sign), $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }

    /**
     * @param $params
     * @param bool $isVerify
     * @return string
     */
    private function getSignContent($params, $isVerify = false)
    {
        unset($params['sign']);
        ksort($params);
        return urldecode(http_build_query($params));
    }
}