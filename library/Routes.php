<?php
class routes extends \Yaf\Plugin_Abstract 
{
	public function routerStartup( \Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		 $routes =  \Yaf\Dispatcher::getInstance()->getRouter();
		 $uri = $request->getRequestUri();
		 $path = explode('/', $uri);
		 $conf = new \Yaf\Config\ini(APP_PATH . '/app/modules/'.$path[1].'/route.ini');
		 $routeConf = new \Yaf\Config\Simple($conf->toArray());
		 $routes->addConfig($routeConf->routes);	
	}	
}