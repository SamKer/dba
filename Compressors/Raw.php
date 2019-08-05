<?php
namespace DBA\Compressors;

class Raw extends Compressor 
{

    public function implementsParams()
    {
     return [];
    }

    /**
	 * Compress
	 * @param strinf $file path
	 * @return string $file path compressed
	 */
	public function compress($file) 
	{
		$this->io->writeln("no compression");
		return $file;
	}
}
