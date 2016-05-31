<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 用户中心控制器
 *
 * 相关方法
 * index                    用户中心首页
 * userEditPassword         用户密码修改
 * userReceiptAddressList   用户收货地址列表
 */

class UserCenterController extends UserController {

    public $user_info = [];

    public function _initialize(){
        parent::_initialize();

        //首先获取用户登录信息
        $info = [];
        $info = get_login_user_info();

        if(empty($info['user_id'])){
            //记录回跳url
            set_login_back_url();
            //跳转至用户登录
            redirect("/Home/User/userLogin");
        }

        $this->user_info = $info;
    }

    /**
     * 用户中心首页
     */
    public function index(){
        $this->display();
    }

    /**
     * 用户密码修改
     */
    public function userEditPassword(){
        $this->display();
    }

    /**
     * 用户收货地址列表
     */
    public function userReceiptAddressList(){
        $user_obj = new \Yege\User();
        $user_obj->user_id = $this->user_info['user_id'];
        $address_list = [];
        $address_list = $user_obj->getUserReceiptAddress();

        $this->assign("address_list",$address_list['list']);
        $this->assign("can_add",count($address_list['list']) < C("HOME_USER_MAX_RECEIPT_ADDRESS_NUM") ? 1 : 0);
        $this->display();
    }

    /**
     * 增加收货地址
     */
    public function addReceiptAddress(){
        if(IS_POST){
            $user_obj = new \Yege\User();
            $user_obj->user_id = $this->user_info['user_id'];
            $address_list = [];
            $address_list = $user_obj->getUserReceiptAddress();
            if(count($address_list['list']) < C("HOME_USER_MAX_RECEIPT_ADDRESS_NUM")){
                $user_model = D("User");
                $post_info = I("post.");
                $result = [];
                $result = $user_model->addReceiptAddress($this->user_info['user_id'],$post_info);
            }else{
                $this->error("用户已达到最大收货地址上限");
            }
        }else{
            $this->display();
        }
    }

}