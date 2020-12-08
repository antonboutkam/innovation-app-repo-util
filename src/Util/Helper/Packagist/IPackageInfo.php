<?php
namespace Repo\Util\Helper\Packagist;

use Hurah\Types\Type\Composer;
use Hurah\Types\Type\Composer\Name;
use Hurah\Types\Type\Path;
use Hurah\Types\Type\PluginType;
use Hurah\Types\Type\SystemId;

/**
 * Interface IPackageInfo
 * @package Helper\Package\Components
 * @internal
 */
interface IPackageInfo
{
    function getPackageType():PluginType;
    function getComposerName(): Name;
    function isInstalled():bool;
    function getSystemId():SystemId;
    // function getInstallDir():Path;
    function getComposer():Composer;
    function hasGit(): bool;
}
