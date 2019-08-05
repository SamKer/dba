<?php
/**
 * Created by PhpStorm.
 * User: samir.keriou
 * Date: 23/01/19
 * Time: 10:27
 */
namespace DB2S3\Commands;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
class GenerateConfig extends Command
{

protected function configure()
    {
        $this->setName("config:create")
        ->setDescription('generate config')
            ;
    }


protected function execute(InputInterface $input, OutputInterface $output)
{
	$io = new SymfonyStyle($input, $output);
	$io->title('Configuration');

	$fs = new Filesystem();
	if(!$fs->exists(DB2S3_CONFIG)) {
		$fs->touch(DB2S3_CONFIG);	
	}

	$conf = Yaml::parseFile(DB2S3_CONFIG);
	if(!$conf) {
		$conf = [
			"version" => DB2S3_VERSION,
			"targets" => [],
			"archiveurs" => [],
		];
	}
	
	$helper = $this->getHelper('question');
	$question = new Question('Quel est le nom de la config ? ', null);
	$nomplan = $helper->ask($input, $output, $question);	
	if(!$nomplan) {
		$io->writeln("vous devez spécifier un nom");
		die;
	}
	if(isset($conf['targets'][$nomplan])) {
		$io->writeln("ce nom existe déjà");
		die;
	}

	$question = new ChoiceQuestion('Quel est le type de la base cible ? ',
	       ['pgsql'], 0);
        $conf['targets'][$nomplan]['dbtype'] = $helper->ask($input, $output, $question);



	$question = new Question('Quel est le host de la base cible ? ', 'localhost');
	$conf['targets'][$nomplan]['dbhost'] = $helper->ask($input, $output, $question);

	$question = new Question('Quel est le nom de la base cible ? ', 'mybase');
	$conf['targets'][$nomplan]['dbname'] = $helper->ask($input, $output, $question);

        $question = new Question('Quel est le login la base cible ? ', 'myuser');
        $conf['targets'][$nomplan]['dbuser'] = $helper->ask($input, $output, $question);

	$question = new Question('Quel est le password la base cible ? ', 'mypassword');
	$question->setHidden(true);
	$question->setHiddenFallback(false);
        $conf['targets'][$nomplan]['dbpassword'] = $helper->ask($input, $output, $question);

	$question = new ChoiceQuestion('Quel est la cible pour archivage ? ', 
		['ScalityS3'],
		0
	);
        $conf['targets'][$nomplan]['archiveur'] = $helper->ask($input, $output, $question);


	$yaml = Yaml::dump($conf);

	file_put_contents(DB2S3_CONFIG, $yaml);
	


}
}
