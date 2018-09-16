<?php
class Login extends \Yaf\Plugin_Abstract {
	//在路由之前触发，这个是7个事件中, 最早的一个. 但是一些全局自定的工作, 还是应该放在Bootstrap中去完成
	public function routerStartup( \Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		 $routes =  \Yaf\Dispatcher::getInstance()->getRouter();
		 $uri = $request->getRequestUri();
		 $path = explode('/', $uri);
		 $conf = new \Yaf\Config\ini(APP_PATH . '/app/modules/'.$path[1].'/route.ini');
		 $routeConf = new \Yaf\Config\Simple($conf->toArray());
		 $routes->addConfig($routeConf->routes);	
	}
	
}