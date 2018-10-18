<?php

namespace core;

class ControllerBase extends \Yaf\Controller_Abstract 
{
	protected $di;
	
	public function init()
	{
		$this->di = \Yaf\Registry::get('di'); 
	}
}
