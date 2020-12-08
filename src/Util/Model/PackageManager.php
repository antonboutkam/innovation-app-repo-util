<?php

namespace Repo\Util\Model;

use Hurah\Types\Type\Composer;
use Repo\Util\Helper\Packagist;

class PackageManager
{
    /**
     * @return Packagist\PackageIterator
     * @throws \Exception
     */
    function getAll():Packagist\PackageIterator
    {
        $oApi = new Packagist();
        $aInstallablePackages = $oApi->getInstallablePackages();

        $oPackageIterator = new Packagist\PackageIterator();
        foreach ($aInstallablePackages as $aPackage)
        {

            $oPackageIterator->add($aPackage['details']);
        }
        return $oPackageIterator;

    }

}
