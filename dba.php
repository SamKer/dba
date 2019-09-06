#!/usr/bin/env php
<?php
namespace DBA;
require __DIR__.'/vendor/autoload.php';


//use DBA\Commands\GenerateConfig;
use DBA\Commands\BaseArchive;
use DBA\Commands\BaseList;
use Symfony\Component\Console\Application;

define("PROJECT_DIR", __DIR__ );
define("DBA_CONFIG", __DIR__."/config.yml");
define("DBA_VERSION", json_decode(file_get_contents('./composer.json'))->version);





$application = new Application('DBA', DBA_VERSION);


//$application->add(new GenerateConfig());
$application->add(new BaseArchive());
$application->add(new BaseList());
$application->run();

