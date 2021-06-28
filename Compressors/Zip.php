<?php
namespace DBA\Compressors;

class Zip extends Compressor 
{

	public function implementsParams() {
        return [];
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
        $fileU = str_replace(".zip", "",$file);
		$zip = new \ZipArchive();
		if($zip->open($file)) {
		 $zip->extractTo(dirname($fileU));
		 $zip->close();
        }
		$this->io->success("file uncompressed to $fileU");
		return $fileU;
    }
}
