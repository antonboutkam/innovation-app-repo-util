<?php

namespace Repo\Util\View\Packages;

use Exception;
use Repo\Util\Helper\Packagist;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

class All {

    /**
     * @param OutputInterface $output
     * @throws Exception
     */
    static function show(OutputInterface $output): int {

        $oApi = new Packagist();
        $aInstallablePackages = $oApi->getInstallablePackages();

        if (!is_iterable($aInstallablePackages)) {
            $output->writeln("<error>No installable packages found</error>");
            return Command::FAILURE;
        }

        $aRows = [];
        $sPrevType = null;
        $i = 0;

        $output->writeln("<info>Seeking installable packages in packagist</info>");
        foreach ($aInstallablePackages as $aInstallablePackage) {
            if ($sPrevType && $aInstallablePackage['type'] !== $sPrevType) {
                $aRows[] = new TableSeparator();
            }
            $sPrevType = $aInstallablePackage['type'];
            $i++;

            $bInstalled = (bool)$aInstallablePackage['installed'];

            $color = function ($value) use ($bInstalled) {
                if ($bInstalled) {
                    return "<fg=white;bg=green>{$value}</>";
                }
                return $value;
            };
            $aPackageIndex[$i] = $aInstallablePackage;
            $aRows[] = [
                $color($aInstallablePackage['installed'] ? '-' : $i),
                $color($aInstallablePackage['name']),
                $color($aInstallablePackage['type']),
                $color($aInstallablePackage['url']),
                $color($bInstalled ? 'installed' : ''),
            ];
        }

        $table = new Table($output);
        $table->setHeaders([
            '#',
            'Package name',
            'Package type',
            'Package url',
            'Installed',
        ]);
        $table->setRows($aRows);
        $table->render();
        return Command::SUCCESS;
    }
}
