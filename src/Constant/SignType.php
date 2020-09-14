<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/9/12
 * Time: 下午3:43
 */

namespace Lmh\EasyOpen\Constant;


class SignType
{
    const MD5 = "MD5";

    const RSA = 'RSA';

    const HMAC_SHA256 = "HMAC-SHA256";

    /**
     * get all signtype
     * @return array
     */
    public static function toArray()
    {
        return [
            self::MD5, self::RSA, self::HMAC_SHA256
        ];
    }
}