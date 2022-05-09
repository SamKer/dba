<?php
namespace DBA;
require __DIR__.'/vendor/autoload.php';


use DBA\Commands\BaseArchive;
use DBA\Commands\BaseLast;
use DBA\Commands\BaseList;
use DBA\Commands\BaseRestore;
use DBA\Commands\BucketCreate;
use DBA\Commands\BucketList;
use DBA\Commands\BucketPolicy;
use DBA\Commands\Pharme;
use Symfony\Component\Console\Application;

define("PROJECT_DIR", __DIR__ );
define("DBA_CONFIG", __DIR__."/config.yml");
define("DBA_VERSION", json_decode(file_get_contents('./composer.json'))->version);





$application = new Application('DBA', DBA_VERSION);


$application->add(new Pharme());
$application->add(new BaseArchive());
$application->add(new BaseList());
$application->add(new BaseLast());
$application->add(new BaseRestore());

$application->add(new BucketList());
$application->add(new BucketPolicy());
$application->add(new BucketCreate());

$application->run();

