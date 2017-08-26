<?php
require(dirname(__FILE__).'/rb.php');
require(dirname(__FILE__).'/functions.php');

//setHeaders();

define('STARTTIME', time() + microtime());

define('DB_SERVER', "localhost");
define('DB_USERNAME', "postgres");
define('DB_PASSWORD', "root");
define('DB_DATABASE', "parlatube");

define('BASE_HREF', 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('LIMIT_TO_IP', $_SERVER['REMOTE_ADDR']);
define('TRACK', true);
define('CHECK_URL', FALSE);
define('ALLOWED_CHARS', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
define('CACHE', false);
define('CACHE_DIR', dirname(__FILE__) . '/cache/');

date_default_timezone_set('Europe/Ljubljana');

//R::setup('mysql:host='.DB_SERVER.';dbname='.DB_DATABASE, DB_USERNAME, DB_PASSWORD);
R::setup('pgsql:host='.DB_SERVER.';dbname='.DB_DATABASE, DB_USERNAME,DB_PASSWORD); //postgresql
R::setAutoResolve(true);
R::freeze(false);


	if(isset($_GET['err'])){
		error_reporting (E_ALL);
		ini_set('display_errors',true);
		var_dump($_SERVER);

	}


//define('__CMS_ROOT__', str_replace('\\', '/', $_SERVER["DOCUMENT_ROOT"].'/'));
//require_once(__CMS_ROOT__.$addedFolder.'php/include/rb.php');
//require_once(__CMS_ROOT__.$addedFolder.'php/include/functions.php');


?>