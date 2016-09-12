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
 * userMessage              用户消息管理
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

        $data = $this->userOrderListDisposeData();

        $order_obj = new \Yege\Order();
        $order_obj->user_id = $this->now_user_info['id'];
        $order_list = $order_obj->getUserOrderList($data['where'],$data['page'],C("HOME_USER_ORDER_LIST_MAX_ORDER_NUM"));

        //分页
        $page_obj = new \Yege\IndexPage($order_list['count'],C("HOME_USER_ORDER_LIST_MAX_ORDER_NUM"));

        //各种状态的颜色显示
        $state_list = [
            C("STATE_ORDER_WAIT_CONFIRM") => "public_red_color", //待确认
            C("STATE_ORDER_WAIT_DELIVERY") => "public_red_color", //待发货
            C("STATE_ORDER_DELIVERY_ING") => "public_green_color", //配送中
            C("STATE_ORDER_WAIT_SETTLEMENT") => "public_red_color", //待结算
            C("STATE_ORDER_SUCCESS") => "public_green_color", //已完成
            C("STATE_ORDER_CLOSE") => "public_gray_color", //已关闭
            C("STATE_ORDER_DISSENT") => "public_red_color", //有异议
            C("STATE_ORDER_BACK") => "public_gray_color", //已退款
        ];
        $confirm_list = [
            "0" => "public_red_color",
            "1" => "public_green_color",
        ];
        $pay_list = [
            "0" => "public_red_color",
            "1" => "public_green_color",
        ];

        $this->assign("state_list",$state_list);
        $this->assign("confirm_list",$confirm_list);
        $this->assign("pay_list",$pay_list);
        $this->assign("order_list",$order_list);
        $this->assign("page",$page_obj->show());
        $this->display();
    }

    /**
     * 用户订单列表参数处理
     * $return array $data 数据返回
     */
    private function userOrderListDisposeData(){
        $data = ['where'=>[],'data'=>[],'page'=>1];
        $get_info = I("get.");

        //页码
        if(!empty($get_info['page'])){
            $data['page'] = check_int($get_info['page']);
        }
        $data['page'] = $data['page'] > 0 ? $data['page'] : 1;

        $where = [];

        $data['where'] = $where;

        return $data;
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