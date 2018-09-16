<?php

class Main
{
	static function index($p) {
		$logic = new IndexLogic();
		var_dump($logic->index());
		$redis = \Yaf\Registry::get('redis');
		$redis->setex('test',2,2);
		var_dump($redis->get('test'));
	}
}
