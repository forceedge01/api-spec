<?php

namespace Genesis\ApiSpecTests\Features\ApiSpec\Endpoint;

use Genesis\BehatApiSpec\Contracts\Endpoint;

class User implements Endpoint
{
    public static function getEndpoint(): string
    {
        return '/users';
    }

    public static function getHeaders(): array
    {
        return [
            'accept-language' => 'en',
            'accept' => 'text/html',
        ];
    }

    public static function getSchema(): array
    {
        return [
            'POST' => [
                200 => self::get200POSTSchemaResponse(),
            ],
            'GET' => [
                500 => self::get500GETSchemaResponse(),
                201 => self::get201GETSchemaResponse(),
            ],
        ];
    }

    public static function get200POSTSchemaResponse(): array
    {
        return [
            'headers' => [
                'Host' => [
                    'value' => 'localhost:8090',
                    'type' => self::TYPE_STRING,
                ],
                'Connection' => [
                    'value' => 'close',
                    'type' => self::TYPE_STRING,
                ],
                'X-Powered-By' => [
                    'value' => 'PHP/7.2.26-1+ubuntu18.04.1+deb.sury.org+1',
                    'type' => self::TYPE_STRING,
                ],
                'content-type' => [
                    'value' => 'application/json',
                    'type' => self::TYPE_STRING,
                ],
            ],
            'body' => [
                'success' => [
                    'type' => self::TYPE_BOOLEAN,
                    'optional' => false,
                ],
                'id' => [
                    'type' => self::TYPE_INTEGER,
                    'optional' => false,
                    'min' => null,
                    'max' => null,
                ],
            ],
        ];
    }

    public static function get500GETSchemaResponse(): array
    {
        return [
            'headers' => [
                'Host' => [
                    'value' => 'localhost:8090',
                    'type' => self::TYPE_STRING,
                ],
                'Connection' => [
                    'value' => 'close',
                    'type' => self::TYPE_STRING,
                ],
                'X-Powered-By' => [
                    'value' => 'PHP/7.2.26-1+ubuntu18.04.1+deb.sury.org+1',
                    'type' => self::TYPE_STRING,
                ],
                'content-type' => [
                    'value' => 'application/json',
                    'type' => self::TYPE_STRING,
                ],
            ],
            'body' => [
                'success' => [
                    'type' => self::TYPE_BOOLEAN,
                    'optional' => false,
                ],
                'error' => [
                    'type' => self::TYPE_STRING,
                    'optional' => false,
                    'pattern' => null,
                ],
            ],
        ];
    }

    public static function get201GETSchemaResponse(): array
    {
        return [
            'headers' => [
                'Host' => [
                    'value' => 'localhost:8090',
                    'type' => self::TYPE_STRING,
                ],
                'Connection' => [
                    'value' => 'close',
                    'type' => self::TYPE_STRING,
                ],
                'X-Powered-By' => [
                    'value' => 'PHP/7.2.26-1+ubuntu18.04.1+deb.sury.org+1',
                    'type' => self::TYPE_STRING,
                ],
                'content-type' => [
                    'value' => 'application/json',
                    'type' => self::TYPE_STRING,
                ],
            ],
            'body' => [
                'success' => [
                    'type' => self::TYPE_BOOLEAN,
                    'optional' => false,
                ],
                'msg' => [
                    'type' => self::TYPE_STRING,
                    'optional' => false,
                    'pattern' => null,
                ],
                'id' => [
                    'type' => self::TYPE_STRING,
                    'optional' => false,
                    'pattern' => null,
                ],
            ],
        ];
    }
}
