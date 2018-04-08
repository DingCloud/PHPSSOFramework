<?php
/**
 * 访问请求全局路由封装类
 * Notice：这是一个单实例设计模式下工作的类
 * @package DingStudio/SSOApp
 * @subpackage RouterApp
 * @author David Ding
 * @copyright 2012-2018 DingStudio All Rights Reserved
 */

class Router extends BaseKernel {

    static private $_instance = null;

    /**
     * Construct Function
     * @param string $timezone
     * @return null
     */
    private function __construct($timezone = 'Asia/Shanghai') {
        if (!defined('APP_PATH')) {
            header('Content-Type: text/plain; Charset=UTF-8');
            exit('APP_PATH isn\'t defined.');
        }
        date_default_timezone_set($timezone);
        if (!session_id()) {
            session_start();
        }
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
        if (!isset($_REQUEST['action'])) {
            self::redirect('./index.php?action=login');
        }
        else {
            switch ($_REQUEST['action']) {
                case 'login':
                    if (BaseKernel::isLogin()) {
                        self::redirect('./index.php?action=ucenter');
                    }
                    BaseKernel::showLoginUI();
                    break;
                case 'register':
                    BaseKernel::showRegisterUI();
                    break;
                case 'logout':
                    if (!BaseKernel::isLogin()) {
                        self::redirect('./index.php?action=login');
                    }
                    else if (BaseKernel::doLogout()) {
                        BaseKernel::showLogoutUI();
                    }
                    else {
                        BaseKernel::showErrorUI();
                    }
                    break;
                case 'ucenter':
                    if (!BaseKernel::isLogin()) {
                        self::redirect('./index.php?action=login');
                    }
                    else {
                        BaseKernel::showUCenterUI();
                    }
                    break;
                case 'api':
                    BaseKernel::loadApi();
                    break;
                default:
                    BaseKernel::showErrorUI();
                    break;
            }
        }
    }

    /**
     * Request Redirect Function
     * @param string $target_url
     * @return null
     */
    public function redirect($target_url = '') {
        if ($target_url != '') {
            header('Location: '.$target_url);
            exit();
        }
    }
}