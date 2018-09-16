#### 代码目录
```shell
- app
--|-- controllers //主控制器
------|-- Error.php
--|-- logics  //逻辑层
------|-- IndexLogic.php 
--|-- models  //数据层
------|-- Index.php
--|tasks //cli
------|-- cli.php
--|Bootstrap.php //初始化方法文件
-modules //其他模块
------|-- v1
------|-- v2
- conf
--|-- application.ini  //配置文件   
- library 
- doc
--|--router.go
------|-- Helper.php
------|-- Base.php //controllerBase
------|-- ModelBase.php //mysql封装类
------|-- PdoMysql.php //mysql连接
------|-- RedisDb.php //redis连接
- public 
--|-- index.php  //入口文件
- components //composer文件
```

#### application.ini

```php
[product]
;支持直接写PHP中的已定义常量
application.directory=APP_PATH "/app/"
application.library=APP_PATH "/library/"
application.modules=v1,Index

application.dispatcher.throwException=true //在出错的时候, 抛出异常
application.dispatcher.catchException=true //在有未捕获的异常的时候, 控制权会交给ErrorController的errorAction方法, 可以通过$request->getException()获得此异常对象

db.host = "localhost"
db.username = "root"
db.password = ""
db.name = "test"
db.port = 3306
db.charset="utf8"


redis.host = "127.0.0.1"
redis.timeout = 3
redis.db = 1
redis.port = 6379
```

#### index.php

这是一个入口文件，你的nginx或apache配置的跟目录应该指向这个文件。

```php
<?php

define("APP_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向public的上一级 */

$app  = new Yaf\Application(APP_PATH . "/conf/application.ini");//配置文件
$app->bootstrap()->run(); //启动
```

#### bootstrap.php

实际的初始化方法按照自己的实际需要进行添加：

```php
<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends \Yaf\Bootstrap_Abstract{
	//加载配置
	public function _initConfig() {
		$this->config = \Yaf\Application::app()->getConfig();
		\Yaf\Registry::set('config', $this->config);
	}

	//加载logic层跟composer以及utils类
	public function _initLoader($dispatcher) {
		$dispatcher->getInstance()->disableView();
		require APP_PATH."../library/Loader.php";
		spl_autoload_register('loader');
	}

	//加载插件
	public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
		$login = new Login();
		$dispatcher->registerPlugin($login);
	}

	//载入mysql
	public function _initMysql() {
		\Yaf\Registry::set('db', PdoMysql::getInstance($this->config["db"]));
	}

	//载入redis
	public function _initRedis() {
		\Yaf\Registry::set('redis', RedisDb::getInstance($this->config["redis"]));
	}
}
```

#### controllers/error.php

主要模块在modules目录下，所以这个只有一个通用处理错误errorController,在有未捕获的异常的时候, 跳转到此函数执行。


```php
<?php
class ErrorController  extends Base {
	public function init()
	{

	}

	public function errorAction($exception) {
		switch($exception->getCode()):
		case 515:
		case 516:
		case 517:
		case 518:
			return $this->_pageNotFound();
		default:
			return $this->_unknownError();
		endswitch;
		//5. render by Yaf
	}
	private function _pageNotFound(){
		echo $this->jsonMsg("page not found v1",404);
		exit;
	}
	private function _unknownError(){
		echo $this->jsonMsg("system error", 500);
		exit;
	}

}
```

#### modules/ 

此目录为主要控制层模块，可添加V1,V2目录作为版本控制。以下我们新建v1/controller/Index.php

```php
<?php

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class IndexController extends  Base {
	public function init()
	{
		parent::init();
		$this->logic = new IndexLogic();//载入逻辑层
	}

	public function indexAction() { //默认Action
		$res = $this->logic->index();	
		echo $this->helper->resRet($res);
		exit;
	}


	public function jwtAction() {
		$token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
            ->setAudience('http://example.org') // Configures the audience (aud claim)
            ->setId(time(), true) // Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
            ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
            ->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
            ->set('uid', 1) // Configures a new claim, called "uid"
            ->getToken(); // Retrieves the generated token

		$token->getHeaders(); // Retrieves the token headers
		$token->getClaims(); // Retrieves the token claims	
		echo $token."<br>"; // The string representation of the object is a JWT string (pretty easy, right?)
	}
}
?>
```

#### logics/IndexLogic.php 

这个是逻辑层，理论上数据的进出过滤等都应该在controller层。拆出逻辑层是为了方便多模块调用。


```php
class IndexLogic {
	public function __construct()
	{
		$this->model = new IndexModel();//载入数据层
	}
	public function index() { //默认Action
		$res = $this->model->index();
		return $res;
	}
}
```

#### models/Index.php 

这个是逻辑层，理论上数据的进出过滤等都应该在controller层。拆出逻辑层是为了方便多模块调用。


```php
class IndexModel extends ModelBase{
	protected $table = 'adm_users';

	public function Index() {
		$this->query('SELECT * FROM ' . $this->table . ' WHERE u_id = ? LIMIT 1', [2]);
		return $this->getRow();
	}
}
```


#### cli.php

在命令行下使用。

```php
define("APP_PATH",  realpath(dirname(__FILE__) . '/../../')); 
$app  = new Yaf\Application(APP_PATH . "/conf/application.ini");
$class = $action = '';
$params = [];

foreach($argv as $k => $arg) {
    if($k === 1)
        $class = $arg;
    elseif($k === 2)
        $action = $arg;
    elseif($k >= 3)
        $params = $arg;
}

//获取配置
$config = \Yaf\Application::app()->getConfig();

//自动加载tasks目录
require APP_PATH."../library/Loader.php";
spl_autoload_register('loader');

//载入mysql
\Yaf\Registry::set('db', PdoMysql::getInstance($config["db"]));
//载入redis
\Yaf\Registry::set('redis', RedisDb::getInstance($config["redis"]));

$app->execute([$class, $action], $params);
```
