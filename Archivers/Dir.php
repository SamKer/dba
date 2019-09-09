<?php

namespace DBA\Archivers;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Dir extends Archiver
{


    public function implementsParams()
    {
        return [
            "directory"
        ];
    }

    /**
     * Check config
     * @param array $config
     * @return boolean true on success
     */
    public function checkConfig($config)
    {
        parent::checkConfig($config);
        $fs = new Filesystem();
        if (!$fs->exists($config['directory'])) {
            throw new \Exception("directory " . $config['directory'] . " is not exist");
        }
    }


    /**
     * Put file to
     * @param string $file path to file
     * @return boolean true on success
     */
    public function put($file)
    {
        $dest = $this->config['directory'] . "/" . basename($file);
        $fs = new Filesystem();
        $fs->copy($file, $dest);
        if (!$fs->exists($dest)) {
            throw new \Exception("archiver failed to put file to $dest");
        }
        $this->io->success("Uploaded {$file} to {$dest}");
        return true;
    }

    /**
     * Get file by name
     * @param string $filename
     * @return string $file
     */
    public function get($filename)
    {

    }


    /**
     * Get Last Archive
     * @param string $target
     * @return string $filename
     */
    public function last($target)
    {

    }

    /**
     * Get All archive for target
     * @param string|false $target
     * @return array $list
     */
    public function list($target = false)
    {
        $nlast = $this->getConfig()['nlast'];
        $list = [];

        $finder = new Finder();
        $files = $finder->files()->in($this->config['directory'])->contains($this->target);
        $i = 1;
        foreach ($files as $file) {
            dump("$i <=> $nlast");
            if($i > $nlast) {

                //suppression old
                $this->delete($file->getBasename());
            } else {
                $date = (new \DateTime())->setTimestamp($file->getATime())->format("Y-m-d H:i:s");
                $list[$date] = [
                    "date" => $date,
                    "file" => $file->getBasename(),
                    "size" => $this->getHumanReadableSize($file->getSize())
                ];
            }
            $i++;
        }

        krsort($list);
        return $list;
    }

    /**
     * Delete archive
     * @param string $filename
     * @return boolean true on success
     */
    public function delete($filename)
    {
        $dest = $this->config['directory'] . "/" . $filename;
        if(!unlink($dest)) {
            throw new \Exception("delete $filename failed");
        }

    }

}
