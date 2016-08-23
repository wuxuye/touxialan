<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 前台订单控制器
 *
 * 相关方法
 * orderInfo        订单详情页
 */

class OrderController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        //首先获取用户登录信息
        if(empty($this->now_user_info['id'])){
            //记录回跳url
            set_login_back_url();
            //跳转至用户登录
            redirect("/Home/User/userLogin");
        }

        $this->goods_model = D("Goods");
    }

    /**
     * 订单详情页
     */
    public function orderInfo($order_id = 0){

        $this->nav_param = 'cart';

        $order_obj = new \Yege\Order();
        $order_obj->user_id = $this->now_user_info['id'];
        $order_obj->order_id = $order_id;
        $order_info = $order_obj->getUserOrderInfo();

        if(empty($order_info)){
            //将页面跳去 我的清单 页
            redirect("/Home/Cart/cartList");
        }

        //提示信息
        $order_info["tip_message"] = $order_info['can_confirm'] == 1 ?
            "确认订单信息无误后 <span class='public_tip_color'>请用稍后收货时的联系号码</span> 编辑短信 ‘QR".$order_info['order_info']['order_code']."’ 发送至手机号 <span class='public_tip_color'>".C('WEB_USE_MOBILE')."</span> 以完成订单确认。" :
            "订单中的部分商品 <span class='public_tip_color'>已被下架或是删除</span> ，也有可能运气不好 <span class='public_tip_color'>库存不足了</span> , 请返回 <a href='/Home/Cart/cartList' class='public_tip_color' >我的清单</a> 重新选择商品";

        if($order_info['can_confirm'] != 1){
            //不能确认的订单 记一个cookie
            cookie("wait_delete_order",$order_info['order_info']['order_code'],3600);
        }

        if(!empty($order_info)){
            $this->assign("order_info",$order_info);
            $this->display();
        }else{
            $this->error("没能获取订单信息");
        }

    }


}