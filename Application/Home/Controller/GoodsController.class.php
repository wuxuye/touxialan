<?php

namespace Home\Controller;
use Think\Controller;

/**
 * ǰ̨��Ʒ������
 *
 * ��ط���
 * goodsList     ��Ʒ�б�չʾ
 */

class GoodsController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->goods_model = D("Goods");
    }

    /**
     * ��Ʒ�б�չʾ
     */
    public function goodsList(){

        $where = array();

        $list = array();
        $list = $this->goods_model->getGoodsList($where);

        P($list);

        $this->display();
    }

}