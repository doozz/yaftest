<?php
class TestModel extends \core\ModelBase 
{
	public function __construct()
	{
		parent::init();
		$this->db = $this->di['mysql_test'];
		$this->table='adm_users';
	}

	/**
	 * 用户登录判断
	 */
	public function Index() {
		$this->query('SELECT * FROM ' . $this->table . ' WHERE u_id = ? LIMIT 1', [1]);
		var_dump($this->getRow());
		$this->db=$this->di['mysql_cz'];
		$this->atable='cz_users';
		$this->query('SELECT * FROM ' . $this->atable . ' WHERE u_id = ? LIMIT 1', [5]);
		var_dump($this->getRow());
	}
}