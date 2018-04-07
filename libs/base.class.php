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
        if ($data[0]['username'] = $user && $data[0]['password'] == $pswd) {
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

    public function loadApi() {
        header('Content-Type: application/json; charset=UTF-8');
        if (!isset($_REQUEST['mod'])) {
            self::outjson(404, 'Module name not found.', null); //未指定模块的错误回显
        }
        switch ($_REQUEST['mod']) {
            case 'login':
                if (!isset($_REQUEST['username']) || !isset($_REQUEST['password'])) {
                    self::outjson(401, 'The username and password are necessary.', null); //登录时未传入完整用户凭据的错误回显
                }
                $username = $_REQUEST['username'];
                $password = $_REQUEST['password'];
                if (self::doLogin($username, $password)) {
                    self::outjson(200, 'Login successfully.', null);
                }
                else {
                    self::outjson(403, 'The user name or password you entered is incorrect, or you do not have authorization to perform this operation.', null);
                }
                break;
            case 'register':
                //TODO
                break;
            case 'logout':
                if (self::doLogout()) {
                    self::outjson(200, 'Logout successfully.', null);
                }
                else {
                    self::outjson(500, 'Logout failed.', null);
                }
                break;
            default:
                self::outjson(404, 'Module not found.', null); //模块不存在的错误回显
                break;
        }
    }

    private function outjson($code = -1, $message = '', $subdata = null) {
        $data = array(
            'code'  =>  $code,
            'message'   =>  $message,
            'data'  =>  $subdata,
            'timestamp' =>  date('YmdHis', time())
        );
        exit(json_encode($data));
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