<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 前台用户清单控制器
 *
 * 相关方法
 * cartList                    清单列表展示
 */

class CartController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->cart_model = D("Cart");
        $this->order_model = D("Order");
        $this->nav_param = 'cart';
    }

    /**
     * 清单列表展示
     */
    public function cartList(){

        //无效订单删除逻辑
        $wait_delete_order = cookie("wait_delete_order");
        if(!empty($wait_delete_order)){
            //无论如何 先删除掉这个cookie
            cookie("wait_delete_order",null);
            $order_obj = new \Yege\Order();
            $order_obj->user_id = $this->now_user_info['id'];
            $order_obj->deleteInvalidOrder($wait_delete_order);
        }

        $list = [];
        $list = $this->cart_model->getCartList($this->now_user_info['id']);

        //获取用户积分信息
        $point_info = [];
        $Point = new \Yege\Point();
        $Point->user_id = $this->now_user_info['id'];
        $point_info = $Point->getUserPointInfo();
        $user_point = 0;
        if($point_info['state'] == 1 && !empty($point_info['result']['point_value'])){
            $user_point = $point_info['result']['point_value'];
        }

        $this->assign("list",$list);
        $this->assign("user_point",$user_point);
        $this->display();

    }


}