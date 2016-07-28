<?php

namespace Home\Controller;
use Think\Controller;
use Yege\Attr;

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
     * 商品列表展示
     */
    public function goodsList(){

        $list = [];
        $list = $this->goods_model->getColumnList();
        if($list['state'] == 1){

            //逐个栏目获取商品
            $goods_list = [];
            if(!empty($list['data'])){
                $Attr = new \Yege\Attr();
                foreach($list['data'] as $key => $val){
                    //获取属性列表
                    $temp_list = $where = $attr_id_list = $attr_id_list_temp = [];
                    $attr_id_list_temp = $Attr->getChildList($val['attr_id']);
                    if(!empty($attr_id_list_temp)){
                        foreach($attr_id_list_temp as $attr_info){
                            $attr_id_list[] = $attr_info['attr_id'];
                        }
                        $attr_id_list[] = $val['attr_id'];
                        $where['attr.id'] = ['in',$attr_id_list];
                        $temp_list = $this->goods_model->getGoodsList($where,1,C("HOME_GOODS_LIST_ONE_COLUMN_MAX_GOODS_NUM"));
                        if($temp_list['state'] == 1){
                            $goods_list[$val['attr_id']] = $temp_list['list'];
                        }
                    }
                }
            }
            $this->assign("list",$list['data']);
            $this->assign("goods_list",$goods_list);
            $this->display();
        }else{
            $this->error($list['message']);
        }

    }

}