<?php

namespace Repo\Util\Helper\Packagist\Types;

use Hurah\Types\Type\Composer;
use Hurah\Types\Type\Composer\Name;
use Hurah\Types\Type\Path;
use Hurah\Types\Type\PluginType;
use Hurah\Types\Type\SystemId;
use Repo\Util\Helper\Packagist\AbstractType;
use Repo\Util\Helper\Packagist\IPackageInfo;

class Domain extends AbstractType implements IPackageInfo {

    public function getPackageType(): PluginType {
        return new PluginType(PluginType::DOMAIN);
    }
    function getSystemId(): SystemId {
        echo $this->getComposer();
        return new SystemId($this->getComposer()->getExtra()['system_id']);
    }

}
