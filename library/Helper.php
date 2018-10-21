<?php

Class Helper 
{
	public function resRet($data = [], $code = 200)
	{
		$ret = ['code' => $code];
		if($code != 200)
			$ret['msg'] = $data;
		else if(!empty($data))
			$ret['data'] = $data;

		echo json_encode($ret);
		exit;
	}
}