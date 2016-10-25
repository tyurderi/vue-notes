<?php

error_reporting(E_ALL);
ini_set("display_errors", "on");

require_once __DIR__ . '/vendor/autoload.php';

$app = Favez\Mvc\App::instance([
    'config' => [
        'modules' => [
            'frontend' => [
                'controller' => [
                    'namespace'     => 'Notes\\Controllers\\',
                    'class_suffix'  => 'Controller',
                    'method_suffix' => 'Action'
                ]
            ]
        ],
        'view' => [
            'theme_path' => 'themes/',
            'cache_path' => 'cache/twig/'
        ],
        'database' => [
            'host' => 'localhost',
            'shem' => 'notes',
            'user' => 'root',
            'pass' => 'vagrant'
        ],
        'app' => [
            'path'       => __DIR__ . '/',
            'cache_path' => 'cache/'
        ],
        'debug' => true
    ],
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$app->any('/[{controller}[/{action}]]', 'frontend:{controller}:{action}');

$app->run();