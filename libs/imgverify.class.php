<?php
/**
 * 图形验证码服务类
 * Notice：这是一个单实例设计模式下工作的类
 * @package DingStudio/SSOApp
 * @subpackage ImgVerifyHelper
 * @author David Ding
 * @copyright 2012-2018 DingStudio All Rights Reserved
 */

class ImgVerify {

    static private $_instance = null; // 定义空实例

    /**
     * 构造函数
     */
    private function __construct() {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * 统一预留实例化入口
     * @return instance 实例化对象
     */
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 创建验证码
     * @param integer $num 验证码长度
     * @param integer $w 图形宽度
     * @param integer $h 图形高度
     * @return mixed
     */
    public function createVerifyCode($num, $w, $h) {
        $code = "";
        for ($i = 0; $i < $num; $i++) {
            $code .= rand(0, 9);
        }
        $_SESSION["mysso_authcode"] = $code;
        header("Content-type: image/png");
        $im = imagecreate($w, $h);
        $black = imagecolorallocate($im, 0, 0, 0);
        $gray = imagecolorallocate($im, 200, 200, 200);
        $bgcolor = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $gray);
        imagerectangle($im, 0, 0, $w - 1, $h - 1, $black);
        $style = array (
            $black, $black, $black, $black, $black, $gray, $gray, $gray, $gray, $gray
        );
        imagesetstyle($im, $style);
        $y1 = rand(0, $h);
        $y2 = rand(0, $h);
        $y3 = rand(0, $h);
        $y4 = rand(0, $h);
        imageline($im, 0, $y1, $w, $y3, IMG_COLOR_STYLED);
        imageline($im, 0, $y2, $w, $y4, IMG_COLOR_STYLED);
        for ($i = 0; $i < 80; $i++) {
            imagesetpixel($im, rand(0, $w), rand(0, $h), $black);
        }
        $strx = rand(3, 8);
        for ($i = 0; $i < $num; $i++) {
            $strpos = rand(1, 6);
            imagestring($im, 5, $strx, $strpos, substr($code, $i, 1), $black);
            $strx += rand(8, 12);
        }
        imagepng($im);
        imagedestroy($im);
    }

    /**
     * 校验验证码
     * @param string $code
     * @return boolean
     */
    public function checkVerifyCode($code) {
        if (!isset($_SESSION['mysso_authcode'])) {
            return true;
        }
        else if ($_SESSION['mysso_authcode'] == $code) {
            return true;
        }
        else {
            return false;
        }
    }
}
