<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/24
 * Time: 下午3:46
 */

namespace Lmh\EasyOpen\Support;


interface Validator
{
    /**
     * 验证处理
     * @param $input
     * @return mixed
     */
    public function validate($input);
}