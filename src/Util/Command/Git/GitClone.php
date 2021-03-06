<?php

namespace Repo\Util\Command\Git;

use Exception;
use Hurah\Types\Exception\InvalidArgumentException;
use Hurah\Types\Type\Composer;
use Hurah\Types\Type\GitUri;
use Repo\Util\Helper\DirectoryStructure;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class GitClone extends Command {
    protected function configure() {
        $this->setName("git:clone");
        $this->addArgument('uri', InputArgument::REQUIRED | InputArgument::IS_ARRAY);
        $this->setDescription("Installs a git repo locally");
        $this->setHelp(<<<EOT
First clones the git repo from the destination uri into a temporary location. Then inspects the composer.json file and
moves the package to a destination that resembles the package name, so vendor/service.
EOT);
    }

    protected function initialize(InputInterface $input, OutputInterface $output) {
        $aUris = $input->getArgument('uri');
        while(empty($aUris))
        {
            $helper = $this->getHelper('question');
            $question = new Question('<question>Please provide a git url: </question>', false);

            $sAnswer = $helper->ask($input, $output, $question);

            try {
                new GitUri($sAnswer);
            }
            catch (Exception $e)
            {
                $output->writeln("<error>$sAnswer is not a valid git uri</error>");
            }
            $aUris[] = $sAnswer;
        }

        parent::initialize($input, $output); // TODO: Change the autogenerated stub
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        $finder = new ExecutableFinder();
        $gitBin = $finder->find('git');

        foreach ($input->getArgument('uri') as $sUri)
        {
            $sTmpDirName = substr(md5($sUri),0, 10);
            $oPackagePath = DirectoryStructure::getTempDir()->extend($sTmpDirName);
            $aCommand = [$gitBin, 'clone', $sUri, "{$oPackagePath}"];

            $oCommand = new Process($aCommand);
            $result = $oCommand->run();
            if($result == Command::SUCCESS)
            {
                $output->writeln("<info>Git repo $sUri cloned</info>");
            }
            else
            {
                $output->writeln("<error>Git repo $sUri cloned</error>");
            }

            $oComposer = Composer::fromPath($oPackagePath->extend('composer.json'));

            $oGitDestination = DirectoryStructure::getPackageDir()->extend($oComposer->getName());

            $output->writeln("<comment>Moving {$oPackagePath} to {$oGitDestination}</comment>");
            $oPackagePath->move($oGitDestination);

        }



        return Command::SUCCESS;
    }

}
