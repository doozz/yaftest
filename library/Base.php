<?php
class Base extends Yaf_Controller_Abstract {
	public function init()
	{
	echo "base";
	}

	protected function jsonMsg($data, $code=200)
	{
		echo json_encode(['ret' => $code, 'msg' => $data]);
		return false;
	}
}
