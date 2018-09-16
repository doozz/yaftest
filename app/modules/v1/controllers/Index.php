<?php

use Components\Utils as utils;

class IndexController extends  Base {
	public function init()
	{
		parent::init();
		$this->logic = new IndexLogic();
	}
	public function indexAction() {//默认Action
		$request = $this->getRequest();
		echo "param id: <span>", $request->getParam('id'), "</span><br/>";
		$res = $this->logic->index();	
		echo $this->helper->resRet($res);
		exit;
	}

	public function testAction() {
		var_dump($this->getRequest()->getPost());
		$jwt = new utils\Jwt();
		echo $jwt->make();

	}
	

}
?>