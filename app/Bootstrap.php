<?php

class Bootstrap extends \Yaf\Bootstrap_Abstract
{
	//加载配置
	public function _initConfig() 
	{
		$this->config = \Yaf\Application::app()->getConfig();
		\Yaf\Registry::set('config', $this->config);
	}

	//路由插件
	public function _initPlugin(\Yaf\Dispatcher $dispatcher) 
	{
		$routes = new routes();
		$dispatcher->registerPlugin($routes);
	}
	
	//加载compose
	public function _initCompose()
	{
		require APP_PATH . DIRECTORY_SEPARATOR .'/components/vendor/autoload.php';
	}

	//挂载logic目录
	public function _initLoader($dispatcher) 
	{
		$dispatcher->getInstance()->disableView();
		require APP_PATH."/library/Loader.php";
		spl_autoload_register('loader');
	}

	//设置容器
	public function _initDi() 
	{
		$container = new Illuminate\Container\Container();
		\Yaf\Registry::set('di', $container); 
		require APP_PATH."/library/container.php";
	}

	//参数过滤插件
	public function _initRules(\Yaf\Dispatcher $dispatcher) 
	{
		$Rules = new Rules();
		$dispatcher->registerPlugin($Rules);
	}
}