<?php

namespace core;

class ControllerBase extends \Yaf\Controller_Abstract 
{
	protected $di;
	
	public function init()
	{
		\Yaf\Dispatcher::getInstance()->disableView();
		$this->di = \Yaf\Registry::get('di'); 
	}
}
