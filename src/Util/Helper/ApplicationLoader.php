<?php

namespace Repo\Util\Helper;

use DirectoryIterator;
use Exception;
use Hurah\Types\Type\Path;
use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

class ApplicationLoader {


    /**
     * ApplicationLoader constructor.
     * @throws Exception
     */
    function __construct()
    {
        $application = new Application();
        $aCommands = $this->getCommands();
        foreach ($aCommands as $oCommand)
        {
            $application->add($oCommand);
        }
        return $application->run();

    }

    private function getCommands() : array {
        $oCommandsRoot = DirectoryStructure::getCommandDir();

        $aCommandCandidates = [];

        $oFinder = new Finder();
        $oFinder->directories()->in($oCommandsRoot)->depth('< 3');

        foreach ($oFinder as $oFile) {
            $oDirectoryIterator = new DirectoryIterator($oFile);
            foreach ($oDirectoryIterator as $oPossibleCommandFile)
            {
                if($oPossibleCommandFile->isFile())
                {
                    $aCommandCandidates[] = $this->makeNs($oCommandsRoot, new Path($oPossibleCommandFile->getPathname()));
                }
            }
        }
        $aCommands = [];
        foreach ($aCommandCandidates as $sNamespace)
        {
            $aCommands[] = new $sNamespace;
        }
        return $aCommands;
    }
    private function makeNs(Path $oCommandDir, Path $oFile):string
    {
        $sFilePart = str_replace([$oCommandDir, '.php'], '', $oFile);
        $aComponents = explode(DIRECTORY_SEPARATOR, $sFilePart);
        return '\\Repo\\Util\\Command' . join('\\', $aComponents);

    }


}
