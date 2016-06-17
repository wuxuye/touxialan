<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 前台商品控制器
 *
 * 相关方法
 * goodsList     商品列表展示
 */

class GoodsController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->goods_model = D("Goods");
    }

    /**
     * 分类页
     */
    public function index(){
        $attr = new \Yege\Attr();
        $attr->attr_id = 11;
        P($attr->getContainById());
    }

    /**
     * 商品列表展示
     */
    public function goodsList(){

        $where = array();

        $list = array();
        $list = $this->goods_model->getGoodsList($where);

        P($list);

        $this->display();
    }

}