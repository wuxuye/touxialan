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
 * userFeedback         用户问题反馈
 * orderDissent         订单异议
 */

class UserController extends PublicController {

    public function _initialize(){
        parent::_initialize();
        $this->hidden_nav = 1;
    }

    /**
     * 用户注册方法(手机注册)
     */
    public function userRegister(){

        $this->home_head_left_title = "用户注册";

        $this->display();
    }

    /**
     * 用户登录方法(手机号登录)
     */
    public function userLogin(){

        $this->home_head_left_title = "用户登录";

        //已登录就直接跳走
        if(!empty($this->now_user_info['id'])){
            //跳转至首页
            redirect("/");
        }

        $this->display();
    }

    /**
     * 用户重置密码
     */
    public function userResetPassword(){

        $this->home_head_left_title = "重置密码";

        $this->display();
    }

    /**
     * 验证码方法
     */
    public function showVerify(){
        //验证码配置信息
        $config = array(
            'fontSize'    =>    18,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useCurve'    =>    false, // 中间的线条
            'imageW'      =>    150,   //验证码宽
            'imageH'      =>    40,    //验证码高
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

    /**
     * 用户问题反馈
     */
    public function userFeedback(){

        $this->home_head_left_title = "问题反馈";

        //登录检测
        if(empty($this->now_user_info['id'])){
            //记录回跳url
            set_login_back_url();
            //跳转至用户登录
            //redirect("/Home/User/userLogin");
        }

        $feedback_type_list = C("FEEDBACK_TYPE_LIST");
        //去掉订单异议
        unset($feedback_type_list[C("FEEDBACK_TYPE_ORDER_DISSENT")]);

        $this->assign("feedback_type_list",$feedback_type_list);
        $this->display();
    }

    /**
     * 订单异议
     * @param int $order_id 订单id
     */
    public function orderDissent($order_id = 0){

        $this->home_head_left_title = "订单异议";

        //登录检测
        if(empty($this->now_user_info['id'])){
            //记录回跳url
            set_login_back_url();
            //跳转至用户登录
            redirect("/Home/User/userLogin");
        }

        $order_info = [];
        if(!empty($order_id)){
            $order_obj = new \Yege\Order();
            $order_obj->user_id = $this->now_user_info['id'];
            $order_obj->order_id = $order_id;
            $order_info = $order_obj->getUserOrderInfo();
            if(empty($order_info['order_info']['id'])){
                $this->error("错误的订单信息");
            }
            $order_info = $order_info['order_info'];
        }else{
            $this->error("参数错误");
        }

        $this->assign("order_info",$order_info);
        $this->assign("is_dissent",1);
        $this->display("userFeedback");
    }

}