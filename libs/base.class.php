<?php
/**
 * Base基类
 * @package DingStudio/SSOApp
 * @subpackage BaseLibrary
 * @author David Ding
 * @copyright 2012-2018 DingStudio All Rights Reserved
 */

class BaseKernel {

    static private $_instance = null;
    private $_timezone = null;

    /**
     * Construct Function
     * @param string $timezone
     * @return null
     */
    private function __construct($timezone = 'Asia/Shanghai') {
        $this->_timezone = $timezone;
        require_once(APP_PATH.'config.inc.php');
        require_once(APP_PATH.'libs/database.class.php');
        require_once(APP_PATH.'libs/imgverify.class.php');
        require_once(APP_PATH.'libs/router.class.php');
        require_once(APP_PATH.'libs/api.class.php');
    }

    /**
     * Single Instance Receiver
     * @param string $timezone
     * @return mixed
     */
    public static function getInstance($timezone = 'Asia/Shanghai') {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($timezone);
        }
        return self::$_instance;
    }

    /**
     * Load Router
     */
    public function loadRouter() {
        $router = Router::getInstance($this->_timezone);
        $router->load();
    }

    /**
     * Login Request Handler Function
     * @param string $user
     * @param string $pswd
     * @return boolean
     */
    public function doLogin($user, $pswd) {
        $mydb = DB::getInstance();
        $data = $mydb->QueryData('select * from users where username="'.$user.'" and password="'.$pswd.'"');
        if ($data[0]['username'] == $user && $data[0]['password'] == $pswd) {
            $token = uniqid('sso_');
            $mydb->QueryResult('update users set usertoken="'.$token.'" where username="'.$user.'"');
            setcookie('mysso_user', $user, time() + 3600, '/', constant('domain'));
            setcookie('mysso_sign', $token, time() + 3600, '/', constant('domain'));
            return true;
        }
        else {
            return false;
        }
    }

    public function doRegister($user, $pswd) {
        $mydb = DB::getInstance();
        $data = $mydb->QueryData('select * from users where username="'.$user.'"');
        if ($data[0]['username'] == $user) {
            return false;
        }
        $result = $mydb->QueryResult('insert into users (username, password) values ("'.$user.'", "'.$pswd.'")');
        if ($result == 1) {
            return true;
        }
    }

    /**
     * Destroy User Session Function
     * @return boolean
     */
    public function doLogout() {
        $mydb = DB::getInstance();
        if (!self::isLogin()) {
            return true;
        }
        else {
            setcookie('mysso_user', '', time() - 3600, '/', constant('domain'));
            setcookie('mysso_sign', '', time() - 3600, '/', constant('domain'));
            return true;
        }
    }

    /**
     * Login Status Query Function
     * @return boolean
     */
    public function isLogin() {
        if (isset($_COOKIE['mysso_user']) && isset($_COOKIE['mysso_sign'])) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * API Router Load
     */
    public function loadApi() {
        API::getInstance($this->_timezone)->load();
    }

    /**
     * Login UI
     */
    public function showLoginUI() {
        require_once(APP_PATH.'views/login.htm');
    }

    /**
     * Logout UI
     */
    public function showLogoutUI() {
        require_once(APP_PATH.'views/logout.htm');
    }

    /**
     * Register UI
     */
    public function showRegisterUI() {
        require_once(APP_PATH.'views/register.htm');
    }

    /**
     * UCenter UI
     */
    public function showUCenterUI() {
        require_once(APP_PATH.'views/ucenter.htm');
    }

    /**
     * Error UI
     */
    public function showErrorUI() {
        require_once(APP_PATH.'views/error.htm');
    }
}