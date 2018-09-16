<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends \Yaf\Bootstrap_Abstract{
	public function _initConfig() {
		//把配置保存起来
		$this->config = \Yaf\Application::app()->getConfig();
		\Yaf\Registry::set('config', $this->config);


	}

//	public function _initRegisterLocalNamespace()
//	{
//		$loader = Yaf_Loader::getInstance();
//		$loader->registerLocalNamespace(
//			array('Controller','Helper')
//		);
//	}
	public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
		$login = new Login();
		$dispatcher->registerPlugin($login);
	}

	// public function _initRules(\Yaf\Dispatcher $dispatcher) {
	// 	$Rules = new Rules();
	// 	$dispatcher->registerPlugin($Rules);
	// }

	public function _initLoader($dispatcher) {
		$dispatcher->getInstance()->disableView();
		require APP_PATH."../library/Loader.php";
		spl_autoload_register('loader');
	}


	//载入mysql
	public function _initMysql()
	{
		\Yaf\Registry::set('db', PdoMysql::getInstance($this->config["db"]));
	}
	//载入redis
	public function _initRedis()
	{
		\Yaf\Registry::set('redis', RedisDb::getInstance($this->config["redis"]));
	}

	//载入方法库
	public function _initLibrary()
	{
		\Yaf\Loader::import('Function.php');
	}
}