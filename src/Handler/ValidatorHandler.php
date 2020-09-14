<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/24
 * Time: 下午3:44
 */

namespace Lmh\EasyOpen\Handler;


use Lmh\EasyOpen\OpenValidatorInterface;

class ValidatorHandler
{
    /**
     * @var array
     */
    protected $validators = [];

    /**
     * @param OpenValidatorInterface $validator
     */
    public function addValidator(OpenValidatorInterface $validator)
    {
        $this->validators[] = $validator;

    }

    /**
     * run validate
     * @param array $contents
     */
    public function run(array $contents)
    {
        foreach ($this->validators as $validator) {
            /**
             * @var OpenValidatorInterface $validator
             */
            $validator->validate($contents);
        }
    }
}