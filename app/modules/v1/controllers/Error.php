<?php
class ErrorController  extends Base {
	public function init()
	{

	}

	public function errorAction($exception) {
		switch($exception->getCode()):
		case 515:
		case 516:
		case 517:
		case 518:
			return $this->_pageNotFound();
		default:
			return $this->_unknownError();
		endswitch;
		//5. render by Yaf
	}
	private function _pageNotFound(){
		echo $this->jsonMsg("page not found v1",404);
		exit;
	}
	private function _unknownError(){
		echo $this->jsonMsg("system error", 500);
		exit;
	}

}