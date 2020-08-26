<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/24
 * Time: 下午3:44
 */

namespace Lmh\EasyOpen\Handler;


use Lmh\EasyOpen\Support\Validator;

class ValidatorHandler
{
    /**
     * @var array
     */
    protected $validators = [];

    /**
     * @param Validator $validator
     */
    public function addValidator(Validator $validator)
    {
        $this->validators[] = $validator;

    }

    /**
     * 参数验证
     * @param $contents
     */
    public function run($contents)
    {
        foreach ($this->validators as $validator) {
            /**
             * @var Validator $validator
             */
            $validator->validate($contents);
        }
    }
}