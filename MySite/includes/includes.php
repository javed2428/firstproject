<?php
define('ROOT','/var/www/html/firstproject/MySite');
define('DS','/');

//initialize ES
require ROOT.DS.'..'.DS.'vendor/autoload.php';
$client = new Elasticsearch\Client();

//Initialize Predis
require ROOT.DS.'..'.DS."predis/autoload.php";
Predis\Autoloader::register();
$redis = new Predis\Client();