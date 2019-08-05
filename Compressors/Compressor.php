<?php
namespace DBA\Compressors;

use DBA\APlugin;

abstract class Compressor extends APlugin implements ICompressor
{

    public $type = "compressor";

}
