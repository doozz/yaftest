<?php

class Base extends \Yaf\Controller_Abstract {
	public function init()
	{
		\Yaf\Dispatcher::getInstance()->disableView();
		$this->helper = new Helper;
	}
}
