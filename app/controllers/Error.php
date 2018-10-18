<?php
class ErrorController extends \core\ControllerBase 
{
	public function init()
	{
		parent::init();
	}

	public function errorAction($exception) 
	{
		var_dump($exception);
		switch($exception->getCode()):
		case 515:
		case 516:
		case 517:
		case 518:
			return $this->_pageNotFound();
		default:
			return $this->_unknownError();
		endswitch;
		
	}
	private function _pageNotFound(){
		echo $this->di['helper']->resRet("page not found",404);
		exit;
	}
	private function _unknownError(){
		echo $this->di['helper']->resRet("system error",500);
		exit;
	}

}