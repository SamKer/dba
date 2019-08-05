<?php
/**
 * Created by PhpStorm.
 * User: samir.keriou
 * Date: 23/01/19
 * Time: 10:27
 */
namespace DBA\Commands;

use DBA\Config;
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

class BaseList extends Command
{

protected function configure()
    {
        $this->setName("base:list")
		->setDescription('list archives')
		->addArgument("target", InputArgument::REQUIRED, "target name", null)
            ;
    }


protected function execute(InputInterface $input, OutputInterface $output)
{
	$io = new SymfonyStyle($input, $output);

	$target = $input->getArgument('target');
	if(!$target) {
		throw new \Exception('no argument target');
	}
        $io->title("Archives List $target");
	
	$config = Config::getTarget($target, $io);
	if(!$config) {
		throw new \Exception("no config defined for $target");
	}	

	$tmp = Config::get('tmp_dir');
        $filetmp = $tmp."/". $target."_".(new \DateTime())->format('Y-m-d_His').".sql";
	
	$dumper = $config['dumper'];
	$compressor = $config['compressor'];
	$archiver = $config['archiver'];


	//list from archiver
	$list = $archiver->list($target);


	//tableau
	$table = new Table($io);
        $table
            ->setHeaders(['Date', 'File', 'Size'])
	    ->setRows(
		    $list
            )
        ;
        $table->render();


	$io->writeln('---');
	


	}
}
