<?php

namespace DBA\Archivers;

use Aws\Credentials\Credentials;
use Aws\S3\Exception\S3Exception;
use Aws\S3\MultipartUploader;
use Aws\S3\MultipartUploadException;
use DBA\Exceptions\ArchiversExceptions;

class S3 extends Archiver
{

    /**
     * @var Client
     */
    private $client;

    public function implementsParams()
    {
        return [
            "endpoint",
            "keyname",
            "secret",
            "bucket",
        ];
    }


    private function createConnexion()
    {
        if (!$this->client) {
            $credentials = new Credentials($this->config['keyname'], $this->config['secret']);
            $this->client = new \Aws\S3\S3Client([
                'version' => 'latest',
                'region' => 'eu-west-1',
                'credentials' => $credentials,
                'endpoint' => $this->config['endpoint'],
                'force_path_style' => true,
                'use_path_style_endpoint' => true,
                //            'debug' => true,
                //            'stats' => true,
                'http' => [
                    'connect_timeout' => 0,
                    //                'verify'=> "/home/samir.keriou/ScalityCa.crt"
                ],
                ''
            ]);
        }

    }

    /**
     * Put file to
     * @param string $file path to file
     * @return boolean true on success
     */
    public function put($file)
    {
        $this->createConnexion();
        $name = basename($file);
        $result = $this->client->createMultipartUpload([
            'Bucket' => $this->config['bucket'],
            'Key' => $name,
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'ACL' => 'private',
            'Metadata' => [
                'type' => 'posgresql',
                'format' => 'raw'
            ]
        ]);

        $uploadId = $result['UploadId'];

        try {
            $file = fopen($file, 'r');
            $partNumber = 1;

            while (!feof($file)) {
                $result = $this->client->uploadPart([
                    'Bucket' => $this->config['bucket'],
                    'Key' => $name,
                    'UploadId' => $uploadId,
                    'PartNumber' => $partNumber,
                    'Body' => fread($file, 5 * 1024 * 1024),
                ]);
                $parts['Parts'][$partNumber] = [
                    'PartNumber' => $partNumber,
                    'ETag' => $result['ETag'],
                ];
                $this->io->writeln("Uploading part {$partNumber} of {$name}");
                $partNumber++;
            }

            fclose($file);
        } catch (S3Exception $e) {
            $result = $this->client->abortMultipartUpload([
                'Bucket' => $this->config['bucket'],
                'Key' => $name,
                'UploadId' => $uploadId
            ]);

            $this->io->writeln("Upload of {$name} failed");
        }

        // Complete the multipart upload.
        $result = $this->client->completeMultipartUpload([
            'Bucket' => $this->config['bucket'],
            'Key' => $name,
            'UploadId' => $uploadId,
            'MultipartUpload' => $parts,
        ]);
        $url = $this->config['endpoint'] . "/" . $this->config['bucket'] . "/" . $name;
        $this->io->success("base archived to {$url}");

        //purge if too much files
        $list = $this->list();
        $nlast = $this->getConfig()['nlast'];
        if (count($list) > $nlast) {
            $toPurge = array_slice($list, $nlast - count($list));
            foreach ($toPurge as $v) {
                $this->io->success("delete old file {$v['file']}");
                $this->delete($v['file']);
            }
        }
        return true;


    }

    /**
     * Get file by name
     * @param string $filename
     * @param $saveTo
     * @return string $file
     * @throws \Exception
     */
    public function get($filename, $saveTo)
    {
        $saveTo = $saveTo . '/' . $filename;
        $result = $this->client->getObject(array(
            'Bucket' => $this->config['bucket'],
            'Key' => $filename,
//            'SaveAs' => $saveTo => not working ???
        ));
        $r = '';
        $result['Body']->rewind();
        while ($data = $result['Body']->read(1024)) {
            $r .= $data;
        }

        if ($r === '') {
            throw new ArchiversExceptions("file downloaded empty: $filename");
        }
        file_put_contents($saveTo, $r);
        if (!file_exists($saveTo)) {
            throw new ArchiversExceptions("donwload fail: $filename");
        }
        $this->io->success("Getting file {$filename}");

        return $saveTo;
    }


    /**
     * Get Last Archive
     * @param string $target
     * @param $saveTo
     * @return string $filename
     * @throws \Exception
     */
    public function last($target, $saveTo)
    {
        $list = $this->list($target);
        $filename = $list[0]['file'];
        return $this->get($filename, $saveTo);
    }

    /**
     * Get All archive for target
     * @param string|false $target
     * @return array $list
     */
    public function list($target = false)

    {
        if (!$target) {
            $target = $this->config['target'];

        }

        $this->createConnexion();
        $results = $this->client->getPaginator('ListObjects', [
            'Bucket' => $this->config['bucket']
        ]);
        $list = [];
        foreach ($results as $result) {
            if ($result['IsTruncated'] === true || (!isset($result['Contents']) || !is_array($result['Contents']))) {
                $this->io->writeln("bucket " . $result['bucket'] . " is empty");
                continue;
            }
            foreach ($result['Contents'] as $object) {
                if (preg_match("#^" . $target . "_([0-9]{4}-[0-9]{2}-[0-9]{2}_[0-9]{6}.*)#", $object['Key'], $matches)) {
                    $list[$object['Key']] = [
                        'date' => $matches[1],
                        'file' => $object['Key'],
                        'size' => $this->getHumanReadableSize($object['Size'])
                    ];
                }
            }
        }
        rsort($list);
        return $list;
    }

    /**
     * Delete archive
     * @param string $filename
     * @return boolean true on success
     */
    public function delete($filename): bool
    {
        $this->createConnexion();
        $this->client->deleteObject([
            "Bucket" => $this->config['bucket'],
            "Key" => $filename
        ]);
        return true;
    }


    public function listBuckets()
    {
        $this->createConnexion();
        //Listing all S3 Bucket
        $buckets = $this->client->listBuckets();
        $list = [];
        foreach ($buckets['Buckets'] as $bucket) {
            $list[] = [$bucket['Name']];
        }
        return $list;
    }


    public function listPolicy()
    {
        $this->createConnexion();
        //Listing all S3 Bucket
        $response = $this->client->getBucketPolicy([
            'Bucket' => $this->config['bucket']
        ]);
        return $response->get('Policy');
    }


    public function createBucket($bucketName)
    {
        $this->createConnexion();
        return $this->client->createBucket(array(
            'Bucket' => $bucketName
        ));
    }
}
