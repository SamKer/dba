<?php
namespace DB2S3\Archivers;
Interface IArchiver 
{


	/**
	 * Check config
	 * @param array $config
	 * @return boolean true on success
	 */
	public function checkConfig($config);

	/**
	 * Put file to
	 * @param string $file path to file
	 * @return boolean true on success
	 */
	public function put($file);

	/**
	 * Get file by name
	 * @param string $filename
	 * @return string $file
	 */
	public function get($filename);

	/**
	 * Get Last Archive
	 * @param string $target
	 * @return string $file
	 */
	public function last($target);

	/**
	 * Get All archive for target
	 * @param string|false $target
	 * @return array $list
	 */
	public function list($target = false);

	/**
	 * Delete archive
	 * @param string $filename
	 * @return boolean true on success
	 */
	public function delete($filename);
}
