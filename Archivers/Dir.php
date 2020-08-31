<?php

namespace DBA\Archivers;


use DBA\Config;
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

        //purge if too much files
        $list = $this->list();
        $nlast = $this->getConfig()['nlast'];
        if(count($list) > $nlast) {
            $toPurge = array_slice($list, $nlast- count($list));
            foreach ($toPurge as $k => $v) {
                $this->io->success("delete old file {$v['file']}");
                $this->delete($v['file']);
            }
        }
        return true;
    }

    /**
     * Get file by name
     * @param string $filename
     * @param string $saveTo dir to save file
     * @return string $file
     */
    public function get($filename, $saveTo)
    {
        $dest = $saveTo.'/'.$filename;
        $orig = $this->config['directory'] . "/" . $filename;
//        $dest = Config::get('tmp_dir') . "/" . $filename;
        $fs = new Filesystem();
        if (!$fs->exists($orig)) {
            throw new \Exception("no archive found at $orig");
        }
        $fs->copy($orig, $dest);
        if (!$fs->exists($dest)) {
            throw new \Exception("archiver failed to download file to $dest");
        }
        $this->io->success("downloaded {$filename} to {$dest}");
        return true;
    }


    /**
     * Get Last Archive
     * @param string $target
     * @param string $saveTo
     * @return string $filename
     */
    public function last($target, $saveTo)
    {
        $list = $this->list();
        $last = array_shift($list);
        return $this->get($last['file'], $saveTo);
    }

    /**
     * Get All archive for target
     * @param string|false $target
     * @return array $list
     */
    public function list($target = false)
    {
        $list = [];
        $finder = new Finder();
        $files = $finder->files()->in($this->config['directory'])->contains($this->target);
        foreach ($files as $file) {
                $date = (new \DateTime())->setTimestamp($file->getATime())->format("Y-m-d H:i:s");
                $list[$date] = [
                    "date" => $date,
                    "file" => $file->getBasename(),
                    "size" => $this->getHumanReadableSize($file->getSize())
                ];
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
        return true;
    }

}
