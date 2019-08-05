<?php
namespace DB2S3;
use Symfony\Component\Yaml\Yaml;

class Config
{

	static public $_instance = null;
	
	public $conf = null;

	public function __construct() {
		$this->conf = Yaml::parseFile(DB2S3_CONFIG);
	        if(!$this->conf) {
                	throw new \Exception("aucun fichier de conf n'a été créé");
		}

	}
	
	static public function getInstance()
	{
		if(self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	/**
	 * Get conf
	 * @param string $key
	 * @return mixed $value
	 */
	static public function get($key) 
	{
		if(isset(self::getInstance()->conf[$key])) {
			return self::getInstance()->conf[$key];
		} else {
			return false;
		}
	
	}

	static public function getTarget($key,$io=false) {
		$t = self::get('targets');
		if(isset($t[$key])) {
			$conf = $t[$key];
			if(!isset($conf['dumper'])) {
				throw new \Exception("no dumper defined for $key");
			}
			if(!isset($conf['archiver'])) {
                                throw new \Exception("no archiver defined for $key");
			}
			if(!isset($conf['compressor'])) {
                                $conf['compressor'] = ['class' => "\\DB2S3\\Compressors\\Raw"];
                        }
			$conf['dumper'] = new $conf['dumper']['class']($conf['dumper'],$io);
			$conf['compressor'] = new $conf['compressor']['class']($conf['compressor'],$io);
			$conf['archiver'] = new $conf['archiver']['class']($conf['archiver'],$io);
			return $conf;
		}else {
			return false;
		}
	}
}
