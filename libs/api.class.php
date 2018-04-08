<?php
/**
 * 访问请求全局路由封装类
 * Notice：这是一个单实例设计模式下工作的类
 * @package DingStudio/SSOApp
 * @subpackage RouterApp
 * @author David Ding
 * @copyright 2012-2018 DingStudio All Rights Reserved
 */

class API extends BaseKernel {

    static private $_instance = null;

    /**
     * Construct Function
     * @param string $timezone
     * @return null
     */
    private function __construct($timezone = 'Asia/Shanghai') {
        $this->_timezone = $timezone;
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
     * Router Loader Function
     * @return null
     */
    public function load() {
        if (!isset($_REQUEST['mod'])) {
            self::outjson(404, 'Module name not found.', null); //未指定模块的错误回显
        }
        switch ($_REQUEST['mod']) {
            case 'login':
                if (!isset($_REQUEST['username']) || !isset($_REQUEST['password'])) {
                    self::outjson(401, 'The username and password are necessary.', null); //登录时未传入完整用户凭据的错误回显
                }
                $username = $_REQUEST['username'];
                $password = sha1($_REQUEST['password']);
                if (self::doLogin($username, $password)) {
                    self::outjson(200, 'Login successfully.', null);
                }
                else {
                    self::outjson(403, 'The user name or password you entered is incorrect, or you do not have authorization to perform this operation.', null);
                }
                break;
            case 'register':
                if (!isset($_REQUEST['username']) || !isset($_REQUEST['password2']) || !isset($_REQUEST['verifycode'])) {
                    self::outjson(405, 'The username and password are necessary.', null); //注册时未传入完整用户凭据的错误回显
                }
                $username = $_REQUEST['username'];
                $password = sha1($_REQUEST['password2']);
                $vcode = $_REQUEST['verifycode'];
                $imgcheck = ImgVerify::getInstance();
                if (!$imgcheck->checkVerifyCode($vcode)) {
                    session_destroy();
                    self::outjson(402, 'Invalid validation code.', null); //注册时未传入完整用户凭据的错误回显
                }
                else {
                    session_destroy();
                    if (self::doRegister($username, $password)) {
                        self::outjson(200, 'Register successfully.', null);
                    }
                    else {
                        self::outjson(500, 'Register failed', null);
                    }
                }
                break;
            case 'logout':
                if (self::doLogout()) {
                    self::outjson(200, 'Logout successfully.', null);
                }
                else {
                    self::outjson(500, 'Logout failed.', null);
                }
                break;
            case 'getVerifyCode':
                $imgcheck = ImgVerify::getInstance();
                $imgcheck->createVerifyCode(5, 60, 20);
                break;
            default:
                self::outjson(404, 'Module not found.', null); //模块不存在的错误回显
                break;
        }
    }

    /**
     * JSON Output
     * @param integer $code Status Code
     * @param string $message Message Text
     * @param array $subdata Data Model
     * @return string JSON String
     */
    private function outjson($code = -1, $message = '', $subdata = null) {
        header('Content-Type: application/json; charset=UTF-8');
        $data = array(
            'code'  =>  $code,
            'message'   =>  $message,
            'data'  =>  $subdata,
            'timestamp' =>  date('YmdHis', time())
        );
        exit(json_encode($data));
    }
}