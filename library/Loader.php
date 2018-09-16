<?php
/** 自动加载器 modules */
function Loader($class) {
	require APP_PATH . DIRECTORY_SEPARATOR .'/components/vendor/autoload.php';

	$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	$file = APP_PATH . DIRECTORY_SEPARATOR . '/app/logics/' . $class . '.php';

	if( file_exists($file)) {
		require $file;
		return;
	}

	$file = APP_PATH . DIRECTORY_SEPARATOR . '/' . $class . '.php';
	if( file_exists($file)) {
		require $file;
		return;
	}

	$file = APP_PATH . DIRECTORY_SEPARATOR . '/app/tasks/' . $class . 'Task.php';

	if( file_exists($file)) {
		require $file;
		return;
	}
	
}