<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 用户控制器
 *
 * 相关方法
 * userRegister         用户注册方法(手机注册)
 * userLogin            用户登录方法(手机号登录)
 * userResetPassword    用户重置密码
 * showVerify           验证码方法
 * userLogout           用户退出登录
 */

class UserController extends PublicController {

    public function _initialize(){
        parent::_initialize();
    }

    /**
     * 用户注册方法(手机注册)
     */
    public function userRegister(){
        $this->display();
    }

    /**
     * 用户登录方法(手机号登录)
     */
    public function userLogin(){

        //首先获取用户登录信息
        $user_info = [];
        $user_info = get_login_user_info();

        //已登录就直接跳走
        if(!empty($user_info['user_id'])){
            //跳转至用户登录
            redirect("/");
        }

        $this->display();
    }

    /**
     * 用户重置密码
     */
    public function userResetPassword(){
        $this->display();
    }

    /**
     * 验证码方法
     */
    public function showVerify(){
        //验证码配置信息
        $config = array(
            'fontSize'    =>    20,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useCurve'    =>    false, // 中间的线条
            'imageW'      =>    150,   //验证码宽
            'imageH'      =>    50,    //验证码高
            'codeSet'     =>    '0123456789', //验证码字符集和
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    /**
     * 退出登录
     */
    public function userLogout(){
        //清空session
        unset($_SESSION[C("HOME_USER_ID_SESSION_STR")]);
        //跳去首页
        redirect("/");
    }

}