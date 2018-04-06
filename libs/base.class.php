<?php
/**
 * Base基类
 * @package DingStudio/SSOApp
 * @subpackage BaseLibrary
 * @author David Ding
 * @copyright 2012-2018 DingStudio All Rights Reserved
 */

class BaseKernel extends DB {

    /**
     * Construct Function
     * @return null
     */
    public function __construct() {
        require(APP_PATH.'config.inc.php');
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
        print_r($data);
    }

    /**
     * Destroy User Session Function
     * @return boolean
     */
    public function doLogout() {
        if (!self::isLogin()) {
            return true;
        }
        else {
            setcookie('mysso_user', '', time() - 3600, '/', constant('domain'));
            setcookie('mysso_sign', '', time() - 3600, '/', constant('domain'));
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