<?php
namespace DBA\Compressors;


class Tar extends Compressor
{

	public function implementsParams() {
        return [];
	}


    /**
	 * Compress
	 * @param string $file path
	 * @return string $file path compressed
	 */
	public function compress($file) 
	{
            $fileC = "$file.tar";
            $tar = new \PharData("$fileC");
            $tar->addFile($file, basename($file));
            $tar->compress(\Phar::GZ);
            unlink($fileC);
            $fileC .= '.gz';

            $this->io->success("file compressed to $fileC");
            return $fileC;
	}

    /**
     * Uncompress
     * @param string $file
     * @return string $file path uncompressed
     */
    public function uncompress($file)
    {
        $fileU = str_replace(".tar.gz", "",$file);
        $tar = new \PharData($file);

        $tar->extractTo(dirname($fileU));

        $this->io->success("file uncompressed to $fileU");
        return $fileU;
    }
}
