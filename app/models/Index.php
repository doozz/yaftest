<?php
class IndexModel extends \core\ModelBase 
{
	public function __construct()
	{
		parent::init();
		//$this->db = $this->di['mysql_test'];
		$this->table='admin';
	}

	/**
	 * 用户登录判断
	 */
	public function Index() 
	{
		$this->query('SELECT * FROM ' . $this->table . ' WHERE admin_id = ? LIMIT 1', [2]);
		var_dump( $this->getRow());exit;
	}
}