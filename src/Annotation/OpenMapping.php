<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/19
 * Time: 下午4:20
 */

namespace lmh\easyopen\Annotation;


use Hyperf\HttpServer\Annotation\Mapping;
use Hyperf\Utils\Str;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
final class OpenMapping extends Mapping
{

    public $methods = ['GET', 'POST'];
    
    public function __construct($value = null)
    {
        parent::__construct($value);
        if (isset($value['methods'])) {
            if (is_string($value['methods'])) {
                // Explode a string to a array
                $this->methods = explode(',', Str::upper(str_replace(' ', '', $value['methods'])));
            } else {
                $methods = [];
                foreach ($value['methods'] as $method) {
                    $methods[] = Str::upper(str_replace(' ', '', $method));
                }
                $this->methods = $methods;
            }
        }
    }
}