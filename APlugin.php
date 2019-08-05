<?php


namespace DBA;


use Symfony\Component\Console\Style\SymfonyStyle;

abstract class APlugin implements IPlugin
{

    /** string target */
    protected $target = "";

    /**
     * @param array
     */
    protected $config = [];

    /**
     * @var bool|SymfonyStyle
     */
    protected $io = false;

    /**
     * @var array
     */
    protected $requiredParams = [];

    /**
     * @param array $config
     * @param SymfonyStyle $io
     */
    public function __construct($target, $config, $io=false) {
        $this->target = $target;
        $this->config = $config;
        $this->requiredParams = $this->implementsParams();
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

    /**
     *
     * @param $config
     * @throws \Exception
     */
    public function checkConfig($config) {
        foreach ($this->requiredParams as $p) {
            if(!isset($this->config[$p])) {
                throw new \Exception("param $p is not defined for Archiver");
            }
        }
    }

    /**
     * define resquired params
     * @param $params
     */
    protected function defineRequiredParams($params) {
        $this->requiredParams = $params;
    }


    /**
     * get a readable size
     * @param timestamp $t
     */
    protected function getHumanReadableSize($t) {
        $t = (integer) $t;
        if(0 < $t && $t <= 1000) {
            return round($t,2) . " o";
        } else if(1000 < $t && $t <= 1000000) {
            return round(((integer) $t / 1000),2) . " Ko";
        } else if(1000000 < $t && $t <= 1000000000) {
            return round(($t / 1000000),2) . " Mo";
        } else {
            return round(($t / 1000000000),2) . " Go";
        }
    }
}