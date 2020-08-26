<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Lmh\EasyOpen;


class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'commands' => [
            ],
            'listeners' => [
                \Lmh\EasyOpen\Listener\OpenMappingListener::class,
            ],
            'exceptions' => [
                'handler' => [
                    'http' => [
                        \Lmh\EasyOpen\Handler\ErrorCodeExceptionHandler::class
                    ],
                ]
            ]
        ];
    }
}
