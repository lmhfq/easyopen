<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/24
 * Time: 下午5:54
 */

namespace Lmh\EasyOpen\Support;


use Lmh\EasyOpen\Constant\RequestParamsConstant;

class RSA
{
    /**
     * @param $content        string 待签名的内容
     * @param $privateKeyPem  string 私钥
     * @return string         签名值的Base64串
     */
    public function sign($content, $privateKeyPem)
    {
        $content = $this->getSignContent($content);
        $priKey = $privateKeyPem;
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        openssl_sign($content, $sign, $res, OPENSSL_ALGO_SHA256);
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * 流程
     * 1、从报文取出签名sign值
     * 2、删除空的参数
     * 3、删除sign sign_type
     * 4、排序
     * 5、验证
     * @param $content
     * @param $sign
     * @param $publicKeyPem
     * @return bool
     */
    public function verify($content, $sign, $publicKeyPem): bool
    {
        $content = $this->getSignContent($content);
        $pubKey = $publicKeyPem;
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        ($res) or die('RSA公钥错误。请检查公钥文件格式是否正确');

        //调用openssl内置方法验签，返回bool值
        return openssl_verify($content, base64_decode($sign), $res, OPENSSL_ALGO_SHA256) === 1;
    }

    /**
     * @param $params
     * @param bool $isVerify
     * @return string
     */
    private function getSignContent($params, $isVerify = false)
    {
        $bizParams = [];
        if (isset($params[RequestParamsConstant::BIZ_CONTENT_FIELD])) {
            $bizParams = $params[RequestParamsConstant::BIZ_CONTENT_FIELD];
            unset($params[RequestParamsConstant::BIZ_CONTENT_FIELD]);
        }
        if ($bizParams) {
            $params[RequestParamsConstant::BIZ_CONTENT_FIELD] = json_encode($bizParams, JSON_UNESCAPED_UNICODE);
        }
        unset($params['sign']);
        if ($isVerify) {
            unset($params['sign_type']);
        }
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if ($v != '' && "@" != substr($v, 0, 1)) {
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }
}