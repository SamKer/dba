<?php
/**
 * Created by PhpStorm.
 * User: samir.keriou
 * Date: 23/01/19
 * Time: 10:27
 */
namespace DBA\Commands;

use DBA\Archivers\IArchiver;
use DBA\Compressors\ICompressor;
use DBA\Config;
use DBA\Dumpers\IDumper;
use DBA\Exceptions\CommandsExceptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
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
use Symfony\Component\Yaml\Dumper;

class BaseArchive extends Command
{

protected function configure()
    {
        $this->setName("base:archive")
		->setDescription('archive une base')
		->addArgument("target", InputArgument::REQUIRED, "target name", null)
        ->addOption("file", "f", InputOption::VALUE_OPTIONAL, "filepath for skip",null)
            ;
    }


protected function execute(InputInterface $input, OutputInterface $output)
{
	$io = new SymfonyStyle($input, $output);

	$io->title('Archive Base');

	$target = $input->getArgument('target');
	if(!$target) {
        throw new CommandsExceptions('aucun argument target');
	}

	$config = Config::getTarget($target, $io);
	if(!$config) {
        throw new CommandsExceptions("aucune config n'a été définie pour $target");
	}

	$tmp = Config::get('tmp_dir');

	/** @var IDumper $dumper */
    $dumper = $config['dumper'];
    $fileName = $dumper->nameFile($tmp);
    $filetmpraw = "$tmp/$fileName";
	/** @var ICompressor $compressor */
    $compressor = $config['compressor'];
    /** @var IArchiver $archiver */
	$archiver = $config['archiver'];

	//tableau
//	$table = new Table($io);
//        $table
//            ->setHeaders(['Runner', 'done', 'time'])
//	    ->setRows(
//		    [0=>["---","---","---"]]
//            )
//        ;
//        $table->render();
    
	$io->writeln('---');

	//dump
	if (!$dumper->dump($filetmpraw)) {
	    throw new CommandsExceptions("dump failed");
    }

	//compress
	if(! ($filetmp = $compressor->compress($filetmpraw))) {
        throw new CommandsExceptions("compress failed");
    }

	//save
	if(!$archiver->put($filetmp)) {
        throw new CommandsExceptions("archive failed");
    }

	//cleaner
    unlink($filetmpraw);
	if($filetmp !== $filetmpraw) {
        unlink($filetmp);
    }


	$io->writeln('base saved');

	}
}
