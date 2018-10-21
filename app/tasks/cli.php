<?php

require APP_PATH . DIRECTORY_SEPARATOR .'/components/vendor/autoload.php';
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


$dispatcher->getInstance()->disableView();
$config = \Yaf\Application::app()->getConfig();
require APP_PATH."../library/Loader.php";
spl_autoload_register('loader');

$container = new Illuminate\Container\Container();
\Yaf\Registry::set('di', $container); 
require APP_PATH."/library/container.php";
$app->execute([$class, $action], $params);

