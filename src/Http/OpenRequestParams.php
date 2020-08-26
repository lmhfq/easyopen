<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/20
 * Time: 下午3:30
 */

namespace Lmh\EasyOpen\Http;

/**
 * Class OpenRequestParams
 * @package Lmh\EasyOpen\Http
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/24
 * @property string $app_id
 * @property string $method
 * @property string $sign
 * @property string $timestamp
 * @property string $version
 * @property string $biz_content
 */
class OpenRequestParams
{
    /**
     * @var string
     */
    public $app_id;
    /**
     * @var string
     */
    public $method;
    /**
     * @var string
     */
    public $format = "JSON";
    /**
     * @var string
     */
    public $sign_type = 'RSA2';
    /**
     * @var string
     */
    public $sign;
    /**
     * @var string
     */
    public $timestamp;
    /**
     * @var string
     */
    public $version = '1.0';
    /**
     * @var array
     */
    public $biz_content;
}