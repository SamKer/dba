<?php
namespace DB2S3\Compressors;

abstract class Compressor implements ICompressor
{

	protected $config;

	protected $io;

	/**
	 * @param array $config
	 * @param SymfonyStyle $io
	 */
	public function __construct($config, $io=false) 
	{

		$this->config = $config;
		$this->checkConfig($this->config);
		$this->io = $io;
	}
}
