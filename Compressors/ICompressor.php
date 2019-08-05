<?php
namespace DB2S3\Compressors;

interface ICompressor 
{

	/**
	 * @param array $config
	 */
	public function checkConfig($config);

	/**
	 * Compress
	 * @param string $file path
	 * @return string $file path compressed
	 */
	public function compress($file);
}
