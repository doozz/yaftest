<?php
class IndexModel extends \core\ModelBase 
{
	public function __construct()
	{
		parent::init();
		$this->db = $this->di['test'];
		$this->table='adm_user';
	}

	/**
	 * 用户登录判断
	 */
	public function Index() {
		$this->query('SELECT * FROM ' . $this->table . ' WHERE u_id = ? LIMIT 1', [2]);
		return $this->getRow();
	}
}