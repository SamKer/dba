<?php
namespace DB2S3\Dumpers;
abstract class Dumper implements IDumper
{

	/**
	 * @param array
	 */
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

	/**
	 * Get config
	 * @return array $config
	 */
	public function getConfig() {
		return $this->config;
	}
}
