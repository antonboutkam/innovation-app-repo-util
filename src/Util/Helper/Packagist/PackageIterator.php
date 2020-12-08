<?php

namespace Repo\Util\Helper\Packagist;

use Repo\Util\Helper\BaseIterator;

class PackageIterator extends BaseIterator {

    function __construct()
    {
        $this->position = 0;
        $this->array = [];
    }
    public function current(): IPackageInfo {

        return $this->array[$this->position];
    }
    public function add(IPackageInfo $oPackageInfo)
    {
        $this->array[$this->position] = $oPackageInfo;
        ++$this->position;
    }
}
