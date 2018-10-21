<?php
class IndexLogic  extends \core\LogicBase
{
	public function __construct()
	{
		parent::init();
		$this->model = new IndexModel();
	}

	public function index() 
	{
		return $res = $this->model->index();
	}

}