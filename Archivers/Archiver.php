<?php
namespace DB2S3\Archivers;

abstract class Archiver implements IArchiver 
{

	protected $config = [];

	/**
	 * @param array $config
	 * @param Symfony\Component\Console\Style\SymfonyStyle $io
	 */
	public function __construct($config, $io=false) {
		$this->config = $config;
		$this->checkConfig($this->config);
		$this->io = $io;
	}

	
}
