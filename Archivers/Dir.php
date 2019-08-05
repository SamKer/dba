<?php
namespace DB2S3\Archivers;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Dir extends Archiver
{



	/**
         * Check config
         * @param array $config
         * @return boolean true on success
         */
	public function checkConfig($config)
	{
	    if(!isset($config['directory'])) {
	        throw new \Exception("param 'directory' is not given");
        }
	    $fs = new Filesystem();
	    if(!$fs->exists($config['directory'])) {
	        throw new \Exception("directory ".$config['directory']." is not exist");
        }

	}


	 /**
         * Put file to
         * @param string $file path to file
         * @return boolean true on success
         */
	public function put($file)
	{
        $dest = $this->config['directory']."/". basename($file);
        $fs = new Filesystem();
        $fs->copy($file, $dest);
        if(!$fs->exists($dest)) {
            throw new \Exception("archiver failed to put file to $dest");
        }
        $this->io->writeln("Uploaded {$file} to {$dest}");
        return true;
	}

        /**
         * Get file by name
         * @param string $filename
         * @return string $file
         */
	public function get($filename)
	{

	}


        /**
	 * Get Last Archive
	 * @param string $target
         * @return string $filename
         */
	public function last($target)
	{

	}

        /**
	 * Get All archive for target
	 * @param string|false $target
         * @return array $list
         */
	public function list($target = false)
	{
		$list = [];

		$finder = new Finder();
		$files = $finder->files()->in($this->config['directory']);
        foreach ($files as $file) {
            $list[] = [
                "date" => (new \DateTime())->setTimestamp($file->getATime())->format("Y-m-d H:i:s"),
                "file" => $file->getBasename(),
                "size" => ((integer) $file->getSize() / 1000 / 1000) . " Mo"
                ];
        }

		return $list;
	}

        /**
         * Delete archive
         * @param string $filename
         * @return boolean true on success
         */
	public function delete($filename)
	{

	}

}
