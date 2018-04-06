<?php
define('APP_PATH', dirname(__FILE__).'/');
require_once(APP_PATH.'libs/database.class.php');
require_once(APP_PATH.'libs/base.class.php');
require_once(APP_PATH.'libs/router.class.php');
Router::getInstance('Asia/Shanghai')->load();
