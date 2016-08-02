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
        $this->nav_param = 'cart';
    }

    /**
     * 清单列表展示
     */
    public function cartList(){

        $list = [];

        $this->assign("list",$list['data']);
        $this->display();

    }


}