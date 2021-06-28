<?php
namespace DBA\Dumpers;
Interface IDumper 
{



	/**
	 * Run dump database to temp file
	 * @param string $file
	 * @return boolean true on success
	 */
	public function dump($file);


    /**
     * Restore base from file
     * @param string $file
     * @return mixed
     */
	public function restore($file);

}
