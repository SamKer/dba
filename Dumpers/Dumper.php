<?php
namespace DBA\Dumpers;
use DBA\APlugin;

abstract class Dumper extends APlugin implements IDumper
{

    public $type = "dumper";


}
