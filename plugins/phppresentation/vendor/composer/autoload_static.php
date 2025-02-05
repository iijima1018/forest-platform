<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita5fe155ae412290391b9b65d486160ba
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpOffice\\PhpPresentation\\' => 26,
            'PhpOffice\\Common\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpOffice\\PhpPresentation\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phppresentation/src/PhpPresentation',
        ),
        'PhpOffice\\Common\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/common/src/Common',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PclZip' => __DIR__ . '/..' . '/pclzip/pclzip/pclzip.lib.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita5fe155ae412290391b9b65d486160ba::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita5fe155ae412290391b9b65d486160ba::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita5fe155ae412290391b9b65d486160ba::$classMap;

        }, null, ClassLoader::class);
    }
}
