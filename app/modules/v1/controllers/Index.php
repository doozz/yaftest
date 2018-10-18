<?php

use Components\Utils as utils;

class IndexController extends \core\ControllerBase 
{
	public function init()
	{
		parent::init();
		$this->logic = new IndexLogic();
	}

	public function indexAction() 
	{
		//默认Action
		 $request = $this->getRequest();
		 var_dump($request->getParams());
		// $res = $this->logic->index();	
		// echo $this->helper->resRet($res);
		// exit;
	}

	public function testAction() 
	{
		$res = $this->logic->index();	
		// var_dump($this->getRequest()->getPost());
		// $jwt = new utils\Jwt();
		// echo $jwt->make();

	}
}
?>