<?php
namespace DB2S3\Compressors;

use DB2S3\APlugin;

abstract class Compressor extends APlugin implements ICompressor
{

    public $type = "compressor";

}
