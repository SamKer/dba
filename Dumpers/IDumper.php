<?php
namespace DB2S3\Dumpers;
Interface IDumper 
{
	/**
	 * check config parameters
	 * @param array $config
	 * @return false;
	 * 
	 */
	public function checkConfig($config);

	/**
	 * Run dump database to temp file
	 * @param string $file
	 * @return boolean true on success
	 */
	public function dump($file);

}
