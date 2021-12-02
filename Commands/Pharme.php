<?php
/**
 * Created by PhpStorm.
 * User: samir.keriou
 * Date: 23/01/19
 * Time: 10:27
 */

namespace DBA\Commands;

use FilesystemIterator;
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

class Pharme extends Command
{

    const FILTER = "\\DBA\\Commands\\Pharme::filter";

    static private $excludes_dir = [
        "docker",
        "var",
        ".idea",
        ".git",
        "Tests"

    ];
    static private array $exclude_files = [
        ".env"
    ];

    protected function configure()
    {
        $this->setName("phar:build")
            ->setDescription('generate phar file')
            ->addArgument("output", InputArgument::OPTIONAL, "output directory", PROJECT_DIR . "/var/build");
    }


    /**
     * @param $file
     * @param $key
     * @param \RecursiveDirectoryIterator $iterator
     * @return bool
     */
    static public function filter(\SplFileInfo $file, string $key, \RecursiveDirectoryIterator $iterator)
    {
        $relativePath = str_replace(PROJECT_DIR."/", "", $key);
        foreach (self::$excludes_dir as $exc) {
            if (str_starts_with($relativePath, $exc)) {
                dump("excluding $relativePath");
                return false;
            }
        }
        return !in_array($relativePath, self::$exclude_files);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $io = new SymfonyStyle($input, $output);
            $io->title('Build DBA phar  (v-' . DBA_VERSION . ')');

            $outputDir = $input->getArgument("output");
            $file = "$outputDir/dba-" . DBA_VERSION . ".phar";
            $phar = new \Phar($file);

            $innerIterator = new \RecursiveDirectoryIterator(PROJECT_DIR, FilesystemIterator::SKIP_DOTS);
            $iterator = new \RecursiveIteratorIterator(new \RecursiveCallbackFilterIterator($innerIterator, self::FILTER));
            //$phar->buildFromDirectory(PROJECT_DIR, $includePattern);
            $phar->buildFromIterator($iterator, PROJECT_DIR);

            if (file_exists($file)) {
                $io->success("phar build into $file");
            } else {
                $io->error("build failed: no file created at $file");
            }
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }
}
