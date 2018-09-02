<?php
class IndexLogic {
	public function __construct()
	{
		$this->model = new IndexModel();
	}
	public function index() {//默认Action
		$res = $this->model->index();
		return $res;
	}

}