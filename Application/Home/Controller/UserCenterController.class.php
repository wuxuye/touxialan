<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 用户中心控制器
 *
 * 相关方法
 * index                    用户中心首页
 * userOrderList            我的订单
 * userEditPassword         用户密码修改
 * userReceiptAddressList   用户收货地址列表
 * userAddReceiptAddress    增加收货地址
 * userEditReceiptAddress   编辑收货地址
 */

class UserCenterController extends UserController {

    public function _initialize(){
        parent::_initialize();

        //首先获取用户登录信息
        if(empty($this->now_user_info['id'])){
            //记录回跳url
            set_login_back_url();
            //跳转至用户登录
            redirect("/Home/User/userLogin");
        }

        $this->user_center_tag_list = C("HOME_USER_CENTER_TAG_LIST");
    }

    /**
     * 用户中心首页
     */
    public function index(){
        //跳去我的订单页
        redirect("/Home/UserCenter/userOrderList");
    }

    /**
     * 我的订单
     */
    public function userOrderList(){
        $this->active_tag = "order";
        $this->display();
    }

    /**
     * 用户密码修改
     */
    public function userEditPassword(){
        $this->active_tag = "password";
        $this->display();
    }

    /**
     * 用户收货地址列表
     */
    public function userReceiptAddressList(){
        $this->active_tag = "address";
        $user_obj = new \Yege\User();
        $user_obj->user_id = $this->now_user_info['id'];
        $address_list = [];
        $address_list = $user_obj->getUserReceiptAddress();

        $this->assign("address_list",$address_list['list']);
        $this->assign("can_add",count($address_list['list']) < C("HOME_USER_MAX_RECEIPT_ADDRESS_NUM") ? 1 : 0);
        $this->display();
    }

    /**
     * 增加收货地址
     */
    public function userAddReceiptAddress(){
        $this->active_tag = "address";
        $this->display();
    }

    /**
     * 编辑收货地址
     * @param int $id 收货地址id
     */
    public function userEditReceiptAddress($id = 0){
        $this->active_tag = "address";
        $user_obj = new \Yege\User();
        $user_obj->user_id = $this->now_user_info['id'];
        //获取详情
        $info = [];
        $info = $user_obj->getUserReceiptAddressInfo($id);
        if($info['state'] == 1){
            $this->assign("info",$info['info']);
            $this->display();
        }else{
            $this->error($info['message']);
        }
    }

}