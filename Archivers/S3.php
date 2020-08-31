<?php

namespace DBA\Archivers;

use Aws\Credentials\Credentials;
use Aws\S3\MultipartUploader;
use Aws\S3\MultipartUploadException;

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
            "bucket"
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
    }


    private function createConnexion()
    {
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
//            'ssl.certificate_authority' => '/tmp/ScalityCa.crt',
//            'ssl.certificate_authority' => false,
            'http' => [
                'connect_timeout' => 0,
//              'verify'=> "/tmp/ScalityCa.crt"
//                'verify'=> false
            ],
//                'client_defaults' => ['verify' => false]
        ]);


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
            'ACL' => 'public-read',
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
        //$url = $result['Location'];
        $url = $this->config['endpoint'] . "/" . $this->config['bucket'] . "/" . $name;
        $this->io->success("base archived to {$url}");

        //TODO purge old file !! important
        //purge if too much files
        $list = $this->list();
//        dump($list);die;
//        $nlast = $this->getConfig()['nlast'];
//        if(count($list) > $nlast) {
//            $toPurge = array_slice($list, $nlast- count($list));
//            foreach ($toPurge as $k => $v) {
//                $this->io->success("delete old file {$v['file']}");
//                $this->delete($v['file']);
//            }
//        }
        return true;


    }

    /**
     * Get file by name
     * @param string $filename
     * @return string $file
     */
    public function get($filename)
    {
        //TODO
        throw new \Exception("method get not implemented yet");
    }


    /**
     * Get Last Archive
     * @param string $target
     * @return string $filename
     */
    public function last($target)
    {
        //TODO
        throw new \Exception("method last not implemented yet");
    }

    /**
     * Get All archive for target
     * @param string|false $target
     * @return array $list
     */
    public function list($target = false)
    {
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
                        'size' => ((integer)$object['Size'] / 1000 / 1000) . " Mo"
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
    public function delete($filename)
    {
        //TODO
        throw new \Exception("method delete not implemented yet");
    }

}
