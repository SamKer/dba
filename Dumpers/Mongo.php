<?php

namespace DBA\Dumpers;

use DBA\Exceptions\DumpersExceptions;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Mongo extends Dumper
{

    public function implementsParams(): array
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
        $cmd = "mongodump --host $host --port $port --authenticationDatabase=$db --username=$user --password=$pwd --archive=$file";
        $process = new Process($cmd);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new DumpersExceptions($process);
        }
        $this->io->success("base dumped to $file");
        return true;
    }

    /**
     * @param $file
     * @return mixed
     */
    public function restore($file)
    {

        $user = $this->config['dbuser'];
        $pwd = $this->config['dbpassword'];
        $db = $this->config['dbname'];
        $port = $this->config['dbport'];
        $host = $this->config['dbhost'];

        $cmd = "mongorestore --host $host  --port $port --authenticationDatabase=$db --username=$user --password=$pwd --archive=$file";

        $process = new Process($cmd);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new DumpersExceptions($process);
        }
        $this->io->success("base restored from $file");
        return true;

    }
}