<?php

namespace DBA;

use Symfony\Component\Yaml\Yaml;

class Config
{

    static public $_instance = null;

    public $conf = null;

    public function __construct()
    {
        $this->conf = Yaml::parseFile(DBA_CONFIG);
        if (!$this->conf) {
            throw new \Exception("aucun fichier de conf n'a été créé");
        }

    }

    static public function getInstance()
    {
        if (self::$_instance === null) {
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
        if (isset(self::getInstance()->conf[$key])) {
            return self::getInstance()->conf[$key];
        } else {
            return false;
        }

    }

    static public function getTarget($key, $io = false)
    {
        $t = self::get('targets');
        if (isset($t[$key])) {
            $conf = $t[$key];
            $conf['target'] = $key;
            if (!isset($conf['dumper'])) {
                throw new \Exception("no dumper defined for $key");
            }

            if (!isset($conf['archiver'])) {
                throw new \Exception("no archiver defined for $key");
            }
            if (!isset($conf['compressor'])) {
                $conf['compressor'] = ['class' => "\\DBA\\Compressors\\Raw"];
            }


            $conf['dumper']['target'] = $key;
            $conf['compressor']['target'] = $key;
            $conf['archiver']['target'] = $key;

            $conf['dumper'] = new $conf['dumper']['class']($key, $conf['dumper'], $io);
            $conf['compressor'] = new $conf['compressor']['class']($key, $conf['compressor'], $io);
            $conf['archiver'] = new $conf['archiver']['class']($key, $conf['archiver'], $io);
            return $conf;
        } else {
            return false;
        }
    }
}
