<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3bfaa95f55ec54b44d9f10b391b8ef7a
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Dyalogo\\Poomuestras\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Dyalogo\\Poomuestras\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3bfaa95f55ec54b44d9f10b391b8ef7a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3bfaa95f55ec54b44d9f10b391b8ef7a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3bfaa95f55ec54b44d9f10b391b8ef7a::$classMap;

        }, null, ClassLoader::class);
    }
}
