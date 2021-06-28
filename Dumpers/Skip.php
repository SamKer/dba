<?php

namespace DBA\Dumpers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Skip extends Dumper
{

    public function implementsParams()
    {
        return [
            "localfile"
        ];
    }


    /**
     * Dump to file
     * @param string $file
     */
    public function dump($file)
    {
        $localFile = $this->config['localfile'];
        if(!copy($localFile, $file)) {
            throw new ProcessFailedException("copy $localFile to $file failed");
        }
        $this->io->success("base dumped to $file");
        return true;
    }

    /**
     * @param $file
     * @return mixed
     */
    public function restore()
    {
        // TODO: Implement restore() method.
    }
}
