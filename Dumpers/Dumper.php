<?php

namespace DBA\Dumpers;

use DBA\APlugin;
use function Aws\default_http_handler;

abstract class Dumper extends APlugin implements IDumper
{

    /** @var string */
    public string $type = "dumper";

    /**
     * get the generic file name based on dumper class
     * @return string $file
     */
    public function nameFile(): string
    {
        $c = $this->getConfig();
        $t = $c['target'];
        $d = (new \DateTime())->format('Y-m-d_His');
        switch (get_class($this)) {
            case "DBA\Dumpers\Mongo":
                $filetmpraw = "$t" . "_" . "$d.bson";
                break;
            case "DBA\Dumpers\Mysql":
            case "DBA\Dumpers\Pgsql":
                $filetmpraw = "$t" . "_" . "$d.sql";
                break;
            case "DBA\Dumpers\Skip":
            default :
                $filetmpraw = "$t" . "_" . "$d.raw";
                break;
        }
        return $filetmpraw;

    }

}
