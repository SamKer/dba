<?php

namespace DBA\Dumpers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Mongo extends Dumper
{

    public function implementsParams()
    {
        return [
            "dbhost",
            "dbport",
            "dbname",
            "dbuser",
            "dbpassword",
            "dbauthentication"
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
        $dbauth = $this->config['dbauthentication'];

        $cmd = "mysqldump -u $user -p $pwd --host $host --port $port --authenticationDatabase $dbauth -d $db -o $file";
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
