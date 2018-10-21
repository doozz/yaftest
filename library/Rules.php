<?php
use  Components\Utils as utils;

Class Rules  extends \Yaf\Plugin_Abstract {
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
        $result = $utils->parseMethod($request);
  
        if (!empty($result)) {
        	$this->di = \Yaf\Registry::get('di'); 
        	$this->di['helper']->resRet($result);
        }
	}	
}