<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/9/15
 * Time: 下午7:45
 */

namespace HyperfTest\Cases;


use Lmh\EasyOpen\Support\MD5;
use Lmh\EasyOpen\Support\RSA;

class SignValidateTest extends AbstractTestCase
{

    public function testMD5()
    {
        $params = ['foo' => 1, 'bar' => 2];
        $secret = '*n+-z2ET+AL~rKnV!jWHK6nNFuv2yZvG';
        $signHandler = new MD5();
        $sign = $signHandler->sign($params, $secret);
        $verify = $signHandler->verify($params, $sign, $secret);
        $this->assertTrue($verify);
    }

    public function testRSA()
    {
        $params = ['foo' => 1, 'bar' => 2];
        $publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwX/e+aoavoa7IFIkBUFo6UDGzAnBRIhN45bjzdn/+iJlis0TNEAdR92l9uj2Zzn4Zh6eEUKQJ+lqSWjQtBDaw+mzyc0T6+7v7l9XbaQ8X2rTbkp0c3mwwR2O/x33s+YRFqcKg7rmo1DMO/dCKEtgDEKGVqLUxHQxPx5qR2Sw6sZOeGc1jBhgW4OICumpgvijO1gyDO4BJvtT8lZ3V/x02IsFq4MnyAt2mvcZpLuVD4iDpikCdGPl8vRp3BEg1VCo6gs4qQhAYepTGjQCR/xQxeLZM0/+iAGl+EXsNtC20AlKReFHDUuv74iiGlfyORDe1c/9Hv5OoCL/e4RvWI3+YQIDAQAB';
        $privateKey = 'MIIEpAIBAAKCAQEAwX/e+aoavoa7IFIkBUFo6UDGzAnBRIhN45bjzdn/+iJlis0TNEAdR92l9uj2Zzn4Zh6eEUKQJ+lqSWjQtBDaw+mzyc0T6+7v7l9XbaQ8X2rTbkp0c3mwwR2O/x33s+YRFqcKg7rmo1DMO/dCKEtgDEKGVqLUxHQxPx5qR2Sw6sZOeGc1jBhgW4OICumpgvijO1gyDO4BJvtT8lZ3V/x02IsFq4MnyAt2mvcZpLuVD4iDpikCdGPl8vRp3BEg1VCo6gs4qQhAYepTGjQCR/xQxeLZM0/+iAGl+EXsNtC20AlKReFHDUuv74iiGlfyORDe1c/9Hv5OoCL/e4RvWI3+YQIDAQABAoIBAQCgIg6JVzjiy+U4jkG5B7SmtDDQ0pHbAWJUIp9L0EmC28xk3eoyp4yT2N5sQ8cHDnZ/LH9rkUZzgTuwnMl6+yfQUiuzqb/+1LJC9ywHVI+R93oaIAvXNLIOX0Pj5Q0FGDS0JnERKy+LoGYkii954UcPXk6GjCLAodfPgIA7rRysbRYvbfbywNci+g6WKXt0ZzTktCbvcNHyjlv3Q8fNaTo5gSeYMECDuuJ5Opxutkyjbh0Bayw9LL/HPl/jU/t4JSp8/Lm91DRa472VHNDevRIsesezaGZXp/ELLs5QaPYsNaAjSJoNoj8cV04Lbni8P5MMnCGLgeyrIlW23mMgQBdxAoGBAPeoMm8TmchX3RruBPAMLK51XIIP55XmgoMgoVqv6e04+GB0q3v4R7Vfg9aKJnYp/XYRyF0hQlagpYm2ZpzTSFDG3acqm+bylxpdP0r/YhMLgxTqd2Xet2pUJytcZQxd6+kwOvGhBMfWykCJwN+SuPiKJBRkY1oAslGf/QvTwBfHAoGBAMgEnhkbZWEmBpiJb9P2mtK2pbUlCK2U8Q2pxsnXXPB48kUH6AmD7LveoPuUFYxuz1dxLvt+UyxU0/L2gNooqLPPipRVLd0XrnzMUDOE4OzbEg3QRmpnZ8I5ncFKiT1BLXa3nyJIvhhrG5BRmj51XbL/HXvUBViW/IXJnF/X8EiXAoGBAPT2caxc/uejwRg6BrhJ0ohdnbsocDFdaAq7494H0qLKjyMZQ+XDl2pXp9g1ngWTUxnOTRmRJET7ccHdoXHNquH7UpNfGgY+PifdxBUytpeP84BkyuUAfvu1cWj6YxjwbcCh7CvE0S25zyQ3HYgHYN62BSoGgh8sQTVrwqhIMuFRAoGAL+wbTPypX4tSzT0wk+Jj2dR1A1+qIpyq3JEu1bFJzTvNIAjy6US4dZKzk1M5jnoGJ3cwwITVvKteVLoGyT4wnVQ+aae242AHKFNza8SMd3NNcg3SsS+xRnGzgFoxY4G4ONdpwzcWhJc09XD3ubs5Befl0oNL/JvASPWzB6ufIGUCgYAdMkOFQHiN9JIVcIxT2gTIbtPLJld1ZHfF6XbNPtd7eg1HI/Tx9F9E8gqd11Mat3cp7bMVUtLG5I7WFiodOh8+ZlVzAmGJqp3J/AhGafI44s2/Pe+Kw6N5VHgdapOuG7J6RwNpZjufwfmusDYsv/nljNR3VsM64lb7oWf5/8odlw==';
        $signHandler = new RSA();
        $sign = $signHandler->sign($params, $privateKey);
        $verify = $signHandler->verify($params, $sign, $publicKey);
        $this->assertTrue($verify);
    }
}