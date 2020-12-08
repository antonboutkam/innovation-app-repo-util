<?php

namespace Repo\Util\Helper;

use Hurah\Types\Type\Path;
use Hurah\Types\Util\FileSystem;

class DirectoryStructure {

    static function getSysRoot(): Path {
        return new Path(dirname(__DIR__, 3));
    }

    static function getCommandDir(): Path {
        return FileSystem::makePath(self::getSysRoot(), 'src', 'Util', 'Command');
    }

    static function getDataDir(): Path {
        $oDataDir = FileSystem::makePath(self::getSysRoot(), 'data');
        $oDataDir->makeDir();
        return $oDataDir;
    }
    static function getTempDir():Path
    {
        $oPackageDir = self::getDataDir()->extend('tmp');
        $oPackageDir->makeDir();
        return $oPackageDir;
    }
    static function getPackageDir():Path
    {
        $oPackageDir = self::getDataDir()->extend('packages');
        $oPackageDir->makeDir();
        return $oPackageDir;
    }
}

