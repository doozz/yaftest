<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit176e1bd87f1c43efc7df07ba2343fcdc
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lcobucci\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lcobucci\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/lcobucci/jwt/src',
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
