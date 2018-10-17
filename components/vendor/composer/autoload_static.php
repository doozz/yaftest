<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit176e1bd87f1c43efc7df07ba2343fcdc
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Container\\' => 14,
        ),
        'L' => 
        array (
            'Lcobucci\\JWT\\' => 13,
        ),
        'I' => 
        array (
            'Illuminate\\Contracts\\' => 21,
            'Illuminate\\Container\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Lcobucci\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/lcobucci/jwt/src',
        ),
        'Illuminate\\Contracts\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/contracts',
        ),
        'Illuminate\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/illuminate/container',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit176e1bd87f1c43efc7df07ba2343fcdc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit176e1bd87f1c43efc7df07ba2343fcdc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
