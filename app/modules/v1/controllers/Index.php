<?php
class IndexController extends  Base {
	public function init()
	{
		parent::init();
		$this->logic = new IndexLogic();
	}
	public function indexAction() {//默认Action
		$res = $this->logic->index();
		echo $this->jsonMsg($res);
		exit;
	}

	public function testAction() {
		echo 'this is test!';
		exit;
	}
}
?>