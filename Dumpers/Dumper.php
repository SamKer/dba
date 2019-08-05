<?php
namespace DB2S3\Dumpers;
use DB2S3\APlugin;

abstract class Dumper extends APlugin implements IDumper
{

    public $type = "dumper";


}
