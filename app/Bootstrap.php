<?php

class Bootstrap extends \Yaf\Bootstrap_Abstract{
	public function _initConfig() {
		//把配置保存起来
		$this->config = \Yaf\Application::app()->getConfig();
		\Yaf\Registry::set('config', $this->config);


	}


	public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
		$login = new Login();
		$dispatcher->registerPlugin($login);
	}

	// public function _initRules(\Yaf\Dispatcher $dispatcher) {
	// 	$Rules = new Rules();
	// 	$dispatcher->registerPlugin($Rules);
	// }
	public function _initCompose(){
		require APP_PATH . DIRECTORY_SEPARATOR .'/components/vendor/autoload.php';
	}
	public function _initLoader($dispatcher) {
		$dispatcher->getInstance()->disableView();
		require APP_PATH."/library/Loader.php";
		spl_autoload_register('loader');
	}

	public function _initDi() {
		
		$container = new Illuminate\Container\Container();
		//注册容器
		\Yaf\Registry::set('di', $container); 
		//导入依赖插件
		foreach ( $this->config["db"]["name"] as $value) {
			$container->singleton('mysql_'.$value, function (){
				return PdoMysql::getInstance($this->config["db"],$value);
			});
		}
		$container->singleton('redis', function() {
			return RedisDb::getInstance($this->config["redis"]);
		});

		$container->singleton('helper', function() {
			return new Helper();
		});
	}
}