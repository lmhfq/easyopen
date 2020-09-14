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
use Lmh\EasyOpen\Constant\RequestParamst;
use Lmh\EasyOpen\Constant\SignType;
use Lmh\EasyOpen\Exception\ErrorCodeException;
use Lmh\EasyOpen\Message\ErrorCode;
use Lmh\EasyOpen\Message\ErrorSubCode;
use Lmh\EasyOpen\OpenValidatorInterface;

class ParamsValidator implements OpenValidatorInterface
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
            RequestParamst::APP_ID_FIELD => 'required',
            RequestParamst::METHOD_FIELD => 'required',
            RequestParamst::BIZ_CONTENT_FIELD => 'required',
            RequestParamst::SIGN_TYPE_FIELD => [
                'required', Rule::in(SignType::toArray()),
            ],
        ];
        $messages = [
            RequestParamst::APP_ID_FIELD . '.required' => ErrorSubCode::MISSING_APP_ID,
            RequestParamst::METHOD_FIELD . '.required' => ErrorSubCode::MISSING_METHOD,
            RequestParamst::BIZ_CONTENT_FIELD . '.required' => ErrorSubCode::MISSING_BIZ_CONTENT,
            RequestParamst::SIGN_TYPE_FIELD . '.required' => ErrorSubCode::MISSING_SIGNATURE_TYPE,
            RequestParamst::SIGN_TYPE_FIELD . '.in' => ErrorSubCode::INVALID_SIGNATURE_TYPE,
        ];
        $validator = $factory->make($input, $rules, $messages);
        if ($validator->passes()) {
            return true;
        }
        $messages = $validator->errors()->getMessages();
        foreach ($messages as $message) {
            throw new ErrorCodeException(ErrorCode::MISSING_PARAMETER, $message[0]);
            break;
        }
        return false;
    }
}