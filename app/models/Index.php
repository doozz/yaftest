<?php
class IndexModel extends ModelBase{
	protected $table = 'adm_users';
	/**
	 * 用户登录判断
	 */
	public function Index() {
		$this->query('SELECT * FROM ' . $this->table . ' WHERE u_id = ? LIMIT 1', [2]);
		return $this->getRow();
	}
}