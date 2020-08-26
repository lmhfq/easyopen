<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/24
 * Time: 下午3:50
 */

namespace Lmh\EasyOpen\Support;


use Hyperf\Utils\ApplicationContext;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Rule;
use Lmh\EasyOpen\Constant\RequestParamsConstant;
use Lmh\EasyOpen\Exception\ErrorCodeException;
use Lmh\EasyOpen\Message\ErrorCode;
use Lmh\EasyOpen\Message\ErrorSubCode;

class ParamsValidator implements Validator
{
    public function validate($input)
    {
        /**
         * @var ValidatorFactoryInterface $factory
         */
        try {
            $container = ApplicationContext::getContainer();
            $factory = $container->get(ValidatorFactoryInterface::class);
        } catch (\Throwable $exception) {
            throw new ErrorCodeException(ErrorCode::SYSTEM_ERROR, ErrorSubCode::UNKNOW_ERROR);
        }
        $rules = [
            RequestParamsConstant::APP_ID_FIELD => 'required|max:20',
            RequestParamsConstant::METHOD_FIELD => 'required',
            RequestParamsConstant::BIZ_CONTENT_FIELD => 'required',
            RequestParamsConstant::SIGN_TYPE_FIELD => [
                'required', Rule::in(['RSA','MD5']),
            ],
        ];
        $messages = [
            RequestParamsConstant::APP_ID_FIELD . '.required' => ErrorSubCode::MISSING_APP_ID,
            RequestParamsConstant::APP_ID_FIELD . '.max' => ErrorSubCode::INVALID_APP_ID,
            RequestParamsConstant::METHOD_FIELD . '.required' => ErrorSubCode::MISSING_METHOD,
            RequestParamsConstant::BIZ_CONTENT_FIELD . '.required' => ErrorSubCode::MISSING_BIZ_CONTENT,
            RequestParamsConstant::SIGN_TYPE_FIELD . '.required' => ErrorSubCode::MISSING_SIGNATURE_TYPE,
            RequestParamsConstant::SIGN_TYPE_FIELD . '.in' => ErrorSubCode::INVALID_SIGNATURE_TYPE,
        ];
        $validator = $factory->make($input, $rules, $messages);
        if ($validator->passes()) {
            return true;
        }
        $messages = $validator->errors()->getMessages();
        foreach ($messages as $message) {
            throw new ErrorCodeException(ErrorCode::INVALID_PARAMETER, $message[0]);
            break;
        }
        return false;
    }
}