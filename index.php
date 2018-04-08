<?php
define('APP_PATH', dirname(__FILE__).'/');
require_once(APP_PATH.'libs/base.class.php');
BaseKernel::getInstance('Asia/Shanghai')->loadRouter();