<?php

use Pagekit\Auth\Auth;
use Pagekit\Auth\Encoder\NativePasswordEncoder;
use Pagekit\Auth\Handler\DatabaseHandler;
use RandomLib\Factory;

return [

    'name' => 'auth',

    'main' => function ($app) {

        $app['auth'] = fn($app) => new Auth($app['events'], $app['auth.handler']);

        $app['auth.password'] = fn() => new NativePasswordEncoder;

        $app['auth.random'] = fn() => (new Factory)->getLowStrengthGenerator();

        $app['auth.handler'] = fn($app) => new DatabaseHandler($app['db'], $app['request.stack'], $app['cookie'], $app['auth.random'], $this->config);

    },

    'autoload' => [

        'Pagekit\\Auth\\' => 'src'

    ],

    'config' => [

        'timeout' => 900,
        'table' => 'auth',
        'cookie'   => [
            'name' => '',
            'lifetime' => 315360000
        ]

    ]

];
