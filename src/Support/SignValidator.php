<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/24
 * Time: 下午4:36
 */

namespace Lmh\EasyOpen\Support;


use Hyperf\Utils\ApplicationContext;
use Lmh\EasyOpen\ApplicationDataFetchInterface;
use Lmh\EasyOpen\Constant\RequestParamst;
use Lmh\EasyOpen\Constant\SignType;
use Lmh\EasyOpen\Exception\ApplicationDataFetchException;
use Lmh\EasyOpen\Exception\ErrorCodeException;
use Lmh\EasyOpen\Message\ErrorCode;
use Lmh\EasyOpen\Message\ErrorSubCode;
use Lmh\EasyOpen\OpenValidatorInterface;

class SignValidator implements OpenValidatorInterface
{
    /**
     * @param $input
     */
    public function validate($input)
    {
        $container = ApplicationContext::getContainer();
        /**
         * @var ApplicationDataFetchInterface $factory
         */
        $factory = $container->get(ApplicationDataFetchInterface::class);
        $appId = $input[RequestParamst::APP_ID_FIELD] ?? '';
        $sign = $input[RequestParamst::SIGN_FIELD] ?? '';
        $signType = $input[RequestParamst::SIGN_TYPE_FIELD] ?? '';
        try {
            $factory->make($appId);
        } catch (ApplicationDataFetchException $e) {
            throw new ErrorCodeException(ErrorCode::INVALID_PARAMETER, ErrorSubCode::INVALID_APP_ID);
        }
        switch ($signType) {
            case SignType::MD5:
                $nonce = $input[RequestParamst::NONCE_FIELD] ?? '';
                if (!$nonce) {
                    throw new ErrorCodeException(ErrorCode::MISSING_PARAMETER, ErrorSubCode::MISSING_NONCE);
                }
                $signHandler = new MD5();
                $verify = $signHandler->verify($input, $sign, $factory->getSecret($appId));
                break;
            case SignType::RSA:
                $signHandler = new RSA();
                $verify = $signHandler->verify($input, $sign, $factory->getPublicKey($appId));
                break;
            default:
                throw new ErrorCodeException(ErrorCode::INVALID_PARAMETER, ErrorSubCode::INVALID_SIGNATURE_TYPE);
                break;
        }
        if (!$verify) {
            throw new ErrorCodeException(ErrorCode::INVALID_PARAMETER, ErrorSubCode::INVALID_SIGNATURE);
        }
    }
}