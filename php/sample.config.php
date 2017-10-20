<?php
require(dirname(__FILE__).'/rb.php');
require(dirname(__FILE__).'/functions.php');

//setHeaders();

define('DB_SERVER', "localhost");
define('DB_USERNAME', "postgres");
define('DB_PASSWORD', "root");
define('DB_DATABASE', "parlatube");

define('LIMIT_TO_IP', $_SERVER['REMOTE_ADDR']);
define('ALLOWED_CHARS', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

define('CACHE', true);
define('CACHE_DIR', dirname(__FILE__) . '/../cache/');
//define('CACHE_DIR', '/home/parladaddy/parlasnippet/cache/');
define('CACHELIFETIME', 20);
define('CACHELIFETIME_SNIPPET_SINGLE', 10);
define('CACHELIFETIME_SNIPPET_LAST', 10);
define('CACHELIFETIME_SNIPPET_ALL', 10);
define('CACHELIFETIME_VIDEO_SINGLE', 10);
define('CACHELIFETIME_PLAYLIST_SINGLE', 10);
define('CACHELIFETIME_ALL', 10);

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