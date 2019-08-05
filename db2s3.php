#!/usr/bin/env php
<?php
namespace DB2S3;
require __DIR__.'/vendor/autoload.php';


use DB2S3\Commands\GenerateConfig;
use DB2S3\Commands\BaseArchive;
use DB2S3\Commands\BaseList;
use Symfony\Component\Console\Application;

define("PROJECT_DIR", __DIR__ );
define("DB2S3_CONFIG", __DIR__."/config.yml");
define("DB2S3_VERSION", "1.0.0");





$application = new Application('DB2S3', DB2S3_VERSION);


$application->add(new GenerateConfig());
$application->add(new BaseArchive());
$application->add(new BaseList());
$application->run();

