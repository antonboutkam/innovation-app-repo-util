<?php

namespace Repo\Util\Helper\Packagist;

use Hurah\Types\Type\Composer;
use Hurah\Types\Type\Composer\Name;
use Hurah\Types\Type\Path;
use Hurah\Types\Type\PluginType;
use Hurah\Types\Util\FileSystem;
use LogicException;
use Repo\Util\Helper\DirectoryStructure;

abstract class AbstractType {


    private Composer $oComposer;

    function getInstallDir():Path {
        $oPackageRoot = DirectoryStructure::getPackageDir();

        $oInstallLocation = $oPackageRoot->extend($this->oComposer->getName());
        if($oInstallLocation->isFile())
        {
            throw new LogicException("File in the way at $oInstallLocation");
        }
        if(!$oInstallLocation->isDir())
        {
            $oInstallLocation->makeDir();
        }
        return $oInstallLocation;
    }

    function getComposer(): Composer {
        return $this->oComposer;
    }

    function __construct(Composer $oComposer) {
        $this->oComposer = $oComposer;
    }

    public function getPackageType(): PluginType {
        return $this->oComposer->getType();
    }

    public function getComposerName(): Name {
        return $this->oComposer->getName();
    }

    public function isInstalled(): bool {
        return is_dir($this->getInstallDir()->extend('.git'));
    }
    function getServiceName() : Composer\ServiceName
    {
        return Composer\ServiceName::fromSystemId($this->getSystemId());
    }

    /**
     * @return Composer\Vendor
     */
    function getVendor() : Composer\Vendor
    {
        return Composer\Vendor::fromSystemId($this->getSystemId());
    }

    public function hasGit(): bool {
        return file_exists($this->getGitPath());
    }

    public function getGitPath():?Path {
        return FileSystem::makePath($this->getInstallDir(), '.git');
    }
    public function getGitConfig(): ?array {
        return Git::getConfig($this->getGitPath());
    }

}
