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

        //时间与地点获取
        $week_list = C("SHOP_SEND_WEEK_LIST");
        $now_week = date("w",time());
        $now_week = $now_week == 0 ? 7 : $now_week;
        foreach($week_list as $key => $val){
            $week_list[$key]['is_now'] = 0;
            $week_list[$key]['is_can'] = 1;
            if($key < $now_week){
                $week_list[$key]['is_can'] = 0;
            }else if($key == $now_week){
                $week_list[$key]['is_now'] = 1;
            }
        }
        $time_list = C("SHOP_SEND_TIME_LIST");
        $user_obj = new \Yege\User();
        $user_obj->user_id = $this->now_user_info['id'];
        $default_address = $user_obj->getDefaultUserReceiptAddress();

        //配送时间段
        $work_list = get_work_time();

        $this->assign("list",$list);
        $this->assign("user_point",$user_point);
        $this->assign("week_list",$week_list);
        $this->assign("time_list",$time_list);
        $this->assign("default_address",$default_address);
        $this->assign("work_list",$work_list);
        $this->display();

    }


}