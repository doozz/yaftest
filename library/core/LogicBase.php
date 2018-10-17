<?php

namespace core;

class LogicBase 
{
	protected $di;
	
	public function init()
	{
		$this->di = \Yaf\Registry::get('di'); 
	}
}
