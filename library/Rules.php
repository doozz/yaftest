<?php
use  Components\Utils as utils;
Class Rules  extends \Yaf\Plugin_Abstract {
	//在路由之前触发，这个是7个事件中, 最早的一个. 但是一些全局自定的工作, 还是应该放在Bootstrap中去完成
	public function dispatchLoopStartup( \Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		$uri = $request->getRequestUri();
		$path = explode('/', $uri);
		$rulesFile =  APP_PATH . '/app/modules/'.$path[1].'/rules/'. $request->getControllerName().'Rules.php';
		
		$rules = file_exists($rulesFile) ? include $rulesFile : false;
		
		if (!$rules) {
            $this->_sanReq = $request->getParams();
            return true;
        }
     
        $utils = new utils\RulesParse($rules[$request->getActionName()]);
        $utils->parse($request);
	}	
}