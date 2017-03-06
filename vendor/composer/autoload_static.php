<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf4526b7e302ed9871f259f8b0c766462
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Flc\\Alidayu\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Flc\\Alidayu\\' => 
        array (
            0 => __DIR__ . '/..' . '/flc/alidayu/src/Alidayu',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf4526b7e302ed9871f259f8b0c766462::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf4526b7e302ed9871f259f8b0c766462::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}