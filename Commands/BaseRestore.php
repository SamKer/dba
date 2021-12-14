<?php
/**
 * Created by PhpStorm.
 * User: samir.keriou
 * Date: 23/01/19
 * Time: 10:27
 */
namespace DBA\Commands;

use DBA\Config;
use DBA\Exceptions\CommandsExceptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Helper\Table;

class BaseRestore extends Command
{

protected function configure()
    {
        $this->setName("base:restore")
		->setDescription('restore')
		->addArgument("target", InputArgument::REQUIRED, "target name", null)
            ;
    }


protected function execute(InputInterface $input, OutputInterface $output)
{
	$io = new SymfonyStyle($input, $output);

	$target = $input->getArgument('target');
	if(!$target) {
		throw new CommandsExceptions('no argument target');
	}

    $io->title("Restore Database $target");
	$tmpDir = Config::get("tmp_dir");
	$config = Config::getTarget($target, $io);
	if(!$config) {
        throw new CommandsExceptions("no config defined for $target");
	}
    //last from archiver
	$archiver = $config['archiver'];
	$fileRestored = $archiver->last($target, "$tmpDir");
	$compressor = $config['compressor'];
	$file = $compressor->uncompress($fileRestored);

	$dumper = $config['dumper'];
	$dumper->restore($file);

	$io->writeln('---');
	}
}
