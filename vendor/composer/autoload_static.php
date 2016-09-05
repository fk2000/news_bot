<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit00f58344e9acaf7f819d90f1a823fe3b
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Abraham\\TwitterOAuth\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Abraham\\TwitterOAuth\\' => 
        array (
            0 => __DIR__ . '/..' . '/abraham/twitteroauth/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit00f58344e9acaf7f819d90f1a823fe3b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit00f58344e9acaf7f819d90f1a823fe3b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
