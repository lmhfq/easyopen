<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: lmh <lmh@weiyian.com>
 * Date: 2020/8/17
 * Time: 下午2:50
 */

namespace easyopen\annotations;

use Hyperf\HttpServer\Annotation\RequestMapping;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
final class OpenMapping extends RequestMapping
{

}