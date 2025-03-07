<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8d1a50035ddabab45437f92a5273b54f
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Dyalogo\\Script\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Dyalogo\\Script\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit8d1a50035ddabab45437f92a5273b54f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8d1a50035ddabab45437f92a5273b54f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8d1a50035ddabab45437f92a5273b54f::$classMap;

        }, null, ClassLoader::class);
    }
}
