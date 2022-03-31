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
	 * @param string $file path
	 * @return string $file path compressed
	 */
	public function compress($file) 
	{
		$this->io->writeln("no compression");
		return $file;
	}

    /**
     * Uncompress
     * @param string $file
     * @return string $file path uncompressed
     */
    public function uncompress($file)
    {
        $this->io->writeln("no uncompression");
		return $file;
    }
}
