<?php
/**
 * Created by PhpStorm.
 * User: samir.keriou
 * Date: 23/01/19
 * Time: 10:27
 */
namespace DB2S3\Commands;

use DB2S3\Config;
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

class BaseArchive extends Command
{

protected function configure()
    {
        $this->setName("base:archive")
		->setDescription('archive une base')
		->addArgument("target", InputArgument::REQUIRED, "target name", null)
            ;
    }


protected function execute(InputInterface $input, OutputInterface $output)
{
	$io = new SymfonyStyle($input, $output);

	$io->title('Archive Base');


	$target = $input->getArgument('target');
	if(!$target) {
		throw new \Exception('aucun argument target');
	}
	
	$config = Config::getTarget($target, $io);
	if(!$config) {
		throw new \Exception("aucune config n'a été définie pour $target");
	}	

	$tmp = Config::get('tmp_dir');
	$filetmpraw = $tmp."/". $target."_".(new \DateTime())->format('Y-m-d_His').".sql";
	
	$dumper = $config['dumper'];
	$compressor = $config['compressor'];
	$archiver = $config['archiver'];

	//dump
	if (!$dumper->dump($filetmpraw)) {
	    throw new \Exception("dump failed");
    }

	//compress
	if(! ($filetmp = $compressor->compress($filetmpraw))) {
        throw new \Exception("compress failed");
    }

	//save
	if(!$archiver->put($filetmp)) {
	    throw new \Exception("archive failed");
    }

	//cleaner
    unlink($filetmpraw);
	if($filetmp !== $filetmpraw) {
        unlink($filetmp);
    }


	$io->writeln('base saved');

	}
}
