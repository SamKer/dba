<?php
namespace DBA\Archivers;

use DBA\APlugin;

abstract class Archiver extends APlugin implements IArchiver
{
    public $type = "archiver";
	
}
