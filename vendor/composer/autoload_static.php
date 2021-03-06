<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7360c32c8609453ddcacd900e30e20a7
{
    public static $files = array (
        '2371fe58591751a9b725c6706865644e' => __DIR__ . '/..' . '/lichtner/fluentpdo/FluentPDO/FluentPDO.php',
        '253c157292f75eb38082b5acb06f3f01' => __DIR__ . '/..' . '/nikic/fast-route/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stash\\' => 6,
            'Slim\\' => 5,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Cache\\' => 10,
        ),
        'N' => 
        array (
            'Notes\\' => 6,
        ),
        'I' => 
        array (
            'Interop\\Container\\' => 18,
        ),
        'F' => 
        array (
            'Favez\\ORM\\' => 10,
            'Favez\\Mvc\\' => 10,
            'FastRoute\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stash\\' => 
        array (
            0 => __DIR__ . '/..' . '/tedivm/stash/src/Stash',
        ),
        'Slim\\' => 
        array (
            0 => __DIR__ . '/..' . '/slim/slim/Slim',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'Notes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Interop\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/container-interop/container-interop/src/Interop/Container',
        ),
        'Favez\\ORM\\' => 
        array (
            0 => __DIR__ . '/..' . '/favez/orm/src',
        ),
        'Favez\\Mvc\\' => 
        array (
            0 => __DIR__ . '/..' . '/favez/favez/src',
        ),
        'FastRoute\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/fast-route/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
        'P' => 
        array (
            'Pimple' => 
            array (
                0 => __DIR__ . '/..' . '/pimple/pimple/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7360c32c8609453ddcacd900e30e20a7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7360c32c8609453ddcacd900e30e20a7::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit7360c32c8609453ddcacd900e30e20a7::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
