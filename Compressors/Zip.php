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
}
