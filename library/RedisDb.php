<?php
class RedisDb  {
	public static $conn = NULL;

	public function __construct($config, $key = 'default')
	{
		$redis = new \Redis();
		if($redis->connect($config['host'], $config['port'], $config['timeout']))
		{
			//$redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
			!empty($config['db']) ? $redis->select($config['db']) : '';
			self::$conn[$key] = $redis;
		}
		else
		{
			throw new \Exception('redis is down');
		}
	}

	public static function getInstance($conf, $key = 'default')
	{
		if(isset(self::$conn[$key]) && self::$conn[$key])
		{
			return self::$conn[$key];
		}

		new RedisDb($conf, $key);
		return self::$conn[$key];
	}
}