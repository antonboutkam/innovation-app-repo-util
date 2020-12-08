<?php
namespace Repo\Util\Helper\Packagist;

use Hurah\Types\Type\Composer;
use LogicException;
use ReflectionClass;
use Repo\Util\Helper\Packagist\IPackageInfo;

/**
 * Seeks locally installed packages and collects information on them.
 *
 * Class Local
 * @package Cli\Composer\Package\Helper
 * @internal
 */
class InfoFactory {

    /**
     * Return a package info object based on it's composer.json file.
     * @param Composer $oComposer
     */
    static function get(Composer $oComposer):IPackageInfo {
        $sFqn = '\\Repo\\Util\\Helper\\Packagist\\Types\\' . ucfirst((string) $oComposer->getType());
        $oReflector = new ReflectionClass($sFqn);
        if(!$oReflector->implementsInterface(IPackageInfo::class))
        {
            throw new LogicException("No supported info object for plugin type {$oComposer->getType()} available.");
        }

        return new $sFqn($oComposer);
    }
}
