<?php

namespace DBA\Compressors;

interface ICompressor
{


    /**
     * Compress
     * @param string $file path
     * @return string $file path compressed
     */
    public function compress($file);
}
