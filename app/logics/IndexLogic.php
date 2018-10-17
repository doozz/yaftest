<?php
class IndexLogic  extends \core\LogicBase
{
	public function __construct()
	{
		parent::init();
		$this->model = new IndexModel();
	}
	public function index() {//默认Action
		// $number = 2;
		// if($number>1) {
		// 	throw new Exception("Value must be 1 or below", 500);
		// }
		// return true;
		$this->di['helper']->test();
		//$res = $this->model->index();
		//return $res;
	}

}