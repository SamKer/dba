<?php
namespace DB2S3\Compressors;

class Zip extends Compressor 
{

	public function checkConfig($config) {

	}

	/**
	 * Compress
	 * @param strinf $file path
	 * @return string $file path compressed
	 */
	public function compress($file) 
	{
		$fileC = "$file.zip";
		$zip = new \ZipArchive();
		$zip->open($fileC,\ZipArchive::CREATE);
		$zip->addFile($file, basename($file));
    		$zip->close();
		
		$this->io->writeln("file compressed to $fileC");
		return $fileC;
	}
}
