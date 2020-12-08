<?php

namespace Repo\Util\Helper\Packagist\Types;

use Hurah\Types\Type\SiteJson;
use Hurah\Types\Type\SystemId;
use Repo\Util\Helper\Packagist\AbstractType;
use Repo\Util\Helper\Packagist\IPackageInfo;

class Site extends AbstractType implements IPackageInfo {

    function getSystemId(): SystemId {

        $oSiteJsonPath = new SiteJson($this->getInstallDir());
        return $oSiteJsonPath->getSystemId();
    }
}
