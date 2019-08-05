<?php
namespace DB2S3\Compressors;

class Raw extends Compressor 
{

	/**
	 * Compress
	 * @param strinf $file path
	 * @return string $file path compressed
	 */
	public function compress($file) 
	{
		$this->io->writeln("no compression")
		return $file;
	}
}
