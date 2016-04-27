<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 用户控制器
 *
 * 相关方法
 * userLogin     用户登录方法(手机号登录)
 * userRegister  用户注册方法(手机注册)
 * showVerify    验证码方法
 * userLogout    用户退出登录
 */

class UserController extends PublicController {

    /**
     * 用户登录方法(手机号登录)
     */
    public function userLogin(){

        if(IS_POST){
            //有参数提交时逻辑
            $post_info = I("post.");
            //对验证码进行判断
            if(!empty($post_info['verify'])){
                $Verify = new \Think\Verify();
                if($Verify->check($post_info['verify'])){
                    //手机登录逻辑
                    $user_obj = new \Yege\User();
                    $user_obj->user_mobile = $post_info['mobile'];
                    $user_obj->user_password = $post_info['password'];
                    $login_result = array();
                    $login_result = $user_obj->userLoginByMobile();
                    if($login_result['state'] == 1){
                        echo $user_obj->user_id."<br>";
                        echo '登陆成功';exit;
                    }else{
                        echo '登陆失败：'.$login_result['message'];exit;
                    }
                }else{
                    echo '验证码错误';exit;
                }
            }else{
                echo '验证码为空';exit;
            }
        }

        $this->display();

    }

    /**
     * 用户注册方法(手机注册)
     */
    public function userRegister(){

        if(IS_POST){
            //有参数提交时逻辑
            $post_info = I("post.");
            //对验证码进行判断
            if(!empty($post_info['verify'])){
                $Verify = new \Think\Verify();
                if($Verify->check($post_info['verify'])){
                    //手机注册逻辑
                    $user_obj = new \Yege\User();
                    $user_obj->user_mobile = $post_info['mobile'];
                    $user_obj->user_password = $post_info['password'];
                    $register_result = array();
                    $register_result = $user_obj->userRegisterByMobile();
                    if($register_result['state'] == 1){
                        echo $user_obj->user_id."<br>";
                        echo '注册成功';exit;
                    }else{
                        echo '注册失败：'.$register_result['message'];exit;
                    }
                }else{
                    echo '验证码错误';exit;
                }
            }else{
                echo '验证码为空';exit;
            }
        }

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
        echo '退出成功';
    }

}