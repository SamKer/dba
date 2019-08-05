<?php
namespace DB2S3\Archivers;

use DB2S3\APlugin;

abstract class Archiver extends APlugin implements IArchiver
{
    public $type = "archiver";
	
}
