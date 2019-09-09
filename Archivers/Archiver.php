<?php
namespace DBA\Archivers;

use DBA\APlugin;

abstract class Archiver extends APlugin implements IArchiver
{
    public $type = "archiver";

    /**
     *
     * @param $config
     * @throws \Exception
     */
    public function checkConfig($config) {
        if(!isset($config['nlast'])) {
            $config['nlast'] = 10;
        }
        parent::checkConfig($config);
    }

	
}
