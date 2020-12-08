<?php

namespace Repo\Util\Helper\Packagist\Types;

use Exception;
use Hurah\Types\Type\SystemId;
use Repo\Util\Helper\Packagist\AbstractType;
use Repo\Util\Helper\Packagist\IPackageInfo;
use Hurah\Types\Type\SiteJson;

class Api extends AbstractType implements IPackageInfo{

    function getSystemId(): SystemId {

        $oSiteJsonPath = new SiteJson($this->getInstallDir());
        return $oSiteJsonPath->getSystemId();
    }
}

