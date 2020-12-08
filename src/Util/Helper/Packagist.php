<?php

namespace Repo\Util\Helper;

use Hurah\Types\Type\Composer;
use Hurah\Types\Util\FileSystem;
use Hurah\Types\Util\JsonUtils;
use Exception;
use GuzzleHttp\Client;
use Repo\Util\Helper\Packagist\InfoFactory;
use Repo\Util\Helper\Packagist\IPackageInfo;

class Packagist
{
    function getPackageDetails(string $sPackageName):IPackageInfo
    {
        $oCient = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://packagist.org',
        ]);

        $oResponse = $oCient->get("p/$sPackageName.json");
        $aBody = [];
        if($oResponse->getStatusCode() === 200)
        {
            $sBody = (string)$oResponse->getBody();
            $aBody = JsonUtils::decode($sBody);
        }
        /**
         * @todo we are returning just the first package we find, need to give the user a list of installable versions
         * instead.
         */
        return InfoFactory::get(Composer::fromArray(current(current(current($aBody)))));
    }

    function getInstallablePackages()
    {
        $aPackageTypes = [
            ['composer-type' => 'novum-domain', 'type' => 'domain'],
            ['composer-type' => 'hurah-domain', 'type' => 'domain'],

            ['composer-type' => 'novum-site', 'type' => 'site'],
            ['composer-type' => 'hurah-site', 'type' => 'site'],

            ['composer-type' => 'novum-api', 'type' => 'api'],
            ['composer-type' => 'hurah-api', 'type' => 'api'],
        ];

        $oCient = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://packagist.org',
        ]);

        $sVendorDir = DirectoryStructure::getSysRoot()->extend('vendor');
        $aOut = [];
        foreach ($aPackageTypes as $aPackageType)
        {
            $aQuery = ['type' => $aPackageType['composer-type'], 'per_page' => 100];
            $oResponse = $oCient->get('packages/list.json', ['query' => $aQuery]);
            if($oResponse->getStatusCode() === 200)
            {
                $sBody = (string) $oResponse->getBody();
                $aBody = JsonUtils::decode($sBody);

                foreach ($aBody['packageNames'] as $sPackageName)
                {
                    $sPackageDir = FileSystem::makePath($sVendorDir, $sPackageName);

                    if(empty($sPackageName))
                    {
                        continue;
                    }
                    $aDetails = self::getPackageDetails($sPackageName);
                    $aOut[] = [
                        'name' => $sPackageName,
                        'type' => $aPackageType['type'],
                        'details' => $aDetails,
                        'url' => "https://repo.packagist.org/p/{$sPackageName}.json",
                        'installed' => is_dir($sPackageDir)
                    ];
                }
            }
            else
            {
                throw new Exception("Got wrong statuscode " . $oResponse->getStatusCode() . " from packagist.");
            }
        }

        return $aOut;
    }

}
