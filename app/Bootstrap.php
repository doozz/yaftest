<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{
	public function _initConfig() {
		//把配置保存起来
		$this->config = Yaf_Application::app()->getConfig();
		Yaf_Registry::set('config', $this->config);

	}

//	public function _initRegisterLocalNamespace()
//	{
//		$loader = Yaf_Loader::getInstance();
//		$loader->registerLocalNamespace(
//			array('Controller','Helper')
//		);
//	}
	public function _initLoader($dispatcher) {
		require APP_PATH."../library/Loader.php";
		spl_autoload_register('loader');
	}
	public function _initPlugin(Yaf_Dispatcher $dispatcher) {
		$login = new Login();
		$dispatcher->registerPlugin($login);
	}
	//载入mysql
	public function _initMysql()
	{
		Yaf_Registry::set('db', PdoMysql::getInstance($this->config["db"]));
	}
	//载入redis
	public function _initRedis()
	{
		Yaf_Registry::set('redis', RedisDb::getInstance($this->config["redis"]));
	}

	//载入方法库
	public function _initLibrary()
	{
		Yaf_Loader::import('Function.php');
	}
//	public function _initPlugin(Yaf_Dispatcher $dispatcher) {
//		//注册一个插件
//	}
//	public function _initRoute(Yaf_Dispatcher $dispatcher) {
//
//		//在这里注册自己的路由协议,默认使用简单路由
//	}
//
//	public function _initView(Yaf_Dispatcher $dispatcher){
//		//在这里注册自己的view控制器，例如smarty,firekylin
//	}
}