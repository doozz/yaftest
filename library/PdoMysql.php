<?php
class PdoMysql  {
	/**
	 * 数据库实例
	 *
	 * @var array
	 */
	public static $instance = NULL;
	public static $count = 1;
	/**
	 * 创建数据库实例
	 *
	 * @param object 数据库配置属性,包含 host|port|user|password|dbname|charset 6个属性
	 * @param string DB编号key
	 */
	public function __construct($config, $key='default')
	{
		try
		{
			// 数据库连接信息
			$dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$key};charset={$config['charset']}";
			// 驱动选项
			$option = array(\PDO::ATTR_ERRMODE=> \PDO::ERRMODE_EXCEPTION, // 如果出现错误抛出错误警告
				\PDO::ATTR_ORACLE_NULLS=> \PDO::NULL_TO_STRING, // 把所有的NULL改成""
				\PDO::ATTR_TIMEOUT=> 30);
			// 创建数据库驱动对象
			self::$instance[$key] = new \Pdo($dsn, $config['username'], $config['password'], $option);
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage(), $e->getCode());
		}
	}

	/**
	 * 创建数据库实例
	 *
	 * @return object 当前对象
	 */
	public static function getInstance($dbConf, $key = 'default')
	{
		if(isset(self::$instance[$key]) && self::$instance[$key] !== null)
		{
			return self::$instance[$key];
		}

		new PdoMysql($dbConf, $key);
		return self::$instance[$key];
	}
}