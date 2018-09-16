<?php

define("APP_PATH",  realpath(dirname(__FILE__) . '/../../')); 
$app  = new Yaf\Application(APP_PATH . "/conf/application.ini");
$class = $action = '';
$params = [];

foreach($argv as $k => $arg) {
    if($k === 1)
        $class = $arg;
    elseif($k === 2)
        $action = $arg;
    elseif($k >= 3)
        $params = $arg;
}


$config = \Yaf\Application::app()->getConfig();
require APP_PATH."../library/Loader.php";
spl_autoload_register('loader');

\Yaf\Registry::set('db', PdoMysql::getInstance($config["db"]));
\Yaf\Registry::set('redis', RedisDb::getInstance($config["redis"]));

$app->execute([$class, $action], $params);

