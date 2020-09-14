<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/9/12
 * Time: 下午4:17
 */

namespace Lmh\EasyOpen;


interface ApplicationDataFetchInterface
{
    /**
     * @param string $appId
     * @return ApplicationDataFetchInterface
     */
    public function make(string $appId): ApplicationDataFetchInterface;

    /**
     * 获取appid对应对secret
     * @return string
     */
    public function getSecret(?string $appId): string;

    /**
     * 获取appid对应对公钥
     * @return string
     */
    public function getPublicKey(?string $appId): string;
}