<?php

namespace DBA\Dumpers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Mysql extends Dumper
{

    public function implementsParams()
    {
        return [
            "dbhost",
            "dbport",
            "dbname",
            "dbuser",
            "dbpassword"
        ];
    }


    /**
     * Dump to file
     * @param string $file
     */
    public function dump($file)
    {

        $user = $this->config['dbuser'];
        $pwd = $this->config['dbpassword'];
        $db = $this->config['dbname'];
        $port = $this->config['dbport'];
        $host = $this->config['dbhost'];

        $cmd = "mysqldump -u$user -p$pwd -h $host -P $port $db > $file";
        $process = new Process($cmd);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
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
