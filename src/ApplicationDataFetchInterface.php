<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/9/12
 * Time: 下午4:17
 */

namespace Lmh\EasyOpen;


use Lmh\EasyOpen\Exception\ApplicationDataFetchException;

interface ApplicationDataFetchInterface
{
    /**
     * @param string $appId
     * @return ApplicationDataFetchInterface
     * @throws ApplicationDataFetchException
     */
    public function make(string $appId): ApplicationDataFetchInterface;

    /**
     * 获取appid对应对secret
     * @param string|null $appId
     * @return string
     */
    public function getSecret(?string $appId = null): string;

    /**
     * 获取appid对应对公钥
     * @param string|null $appId
     * @return string
     */
    public function getPublicKey(?string $appId = null): string;
}