<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台AJAX控制器
 *
 * 相关方法
 * ====== 商品相关 ======
 * ajaxUnshelveGoods  下架商品
 * ajaxShelveGoods    上架商品
 * ajaxDeleteGoods    删除商品
 * ====== 属性相关 ======
 * ajaxGetAttrList  根据属性id获取属性列表
 * ajaxAddAttr      添加属性
 * ajaxDeleteAttr   删除属性
 *
 */

class AjaxController extends PublicController {

    public function _initialize(){
        parent::_initialize();

    }

    /**
     * 下架商品
     */
    public function ajaxUnshelveGoods(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $post_info = I("post.");
        $goods_id = intval($post_info['goods_id']);

        $goods_result = array();
        $goods_obj = new \Yege\Goods();
        $goods_obj->goods_id = $goods_id;
        $goods_result = $goods_obj->unshelveGoods();
        if($goods_result['state'] == 1){
            $result['state'] = 1;
            $result['message'] = "下架成功";
        }else{
            $result['message'] = $goods_result['message'];
        }

        $this->ajaxReturn($result);
    }

    /**
     * 上架商品
     */
    public function ajaxShelveGoods(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $post_info = I("post.");
        $goods_id = intval($post_info['goods_id']);

        $goods_result = array();
        $goods_obj = new \Yege\Goods();
        $goods_obj->goods_id = $goods_id;
        $goods_result = $goods_obj->shelveGoods();
        if($goods_result['state'] == 1){
            $result['state'] = 1;
            $result['message'] = "上架成功";
        }else{
            $result['message'] = $goods_result['message'];
        }

        $this->ajaxReturn($result);
    }

    /**
     * 删除商品
     */
    public function ajaxDeleteGoods(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $post_info = I("post.");
        $goods_id = intval($post_info['goods_id']);

        $goods_result = array();
        $goods_obj = new \Yege\Goods();
        $goods_obj->goods_id = $goods_id;
        $goods_result = $goods_obj->deleteGoods();
        if($goods_result['state'] == 1){
            $result['state'] = 1;
            $result['message'] = "删除成功";
        }else{
            $result['message'] = $goods_result['message'];
        }

        $this->ajaxReturn($result);
    }

    /**
     * 根据属性id获取属性列表
     */
    public function ajaxGetAttrList(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $post_info = I("post.");
        $post_info['attr_id'] = intval($post_info['attr_id']);
        if($post_info['attr_id'] >= 0){
            $attr_obj = new \Yege\Attr();
            $attr_obj->attr_id = $post_info['attr_id'];
            $attr_list = $attr_obj->getAttrListById();
            if($attr_list['state'] == 1){
                //数据检测
                if(!empty($attr_list['list'])){
                    $result['state'] = 1;
                    $result['message'] = "获取成功";
                    $result['attr_list'] = $attr_list['list'];
                }else{
                    $result['message'] = "未能获取属性信息";
                }
            }else{
                $result['message'] = $attr_list['message'];
            }
        }else{
            $result['message'] = "属性id错误";
        }

        $this->ajaxReturn($result);
    }

    /**
     * 添加属性
     */
    public function ajaxAddAttr(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $post_info = I("post.");
        $attr_name = trim($post_info['attr_name']);
        $attr_parent_id = intval($post_info['attr_parent_id']);
        $attr_obj = new \Yege\Attr();
        $attr_obj->attr_name = $attr_name;
        $attr_obj->attr_parent_id = $attr_parent_id;
        $add_result = array();
        $add_result = $attr_obj->addAttr();
        if($add_result['state'] == 1){
            $result['state'] = 1;
            $result['message'] = "添加成功";
        }else{
            $result['message'] = "添加失败：".$add_result['message'];
        }

        $this->ajaxReturn($result);
    }

    /**
     * 删除属性
     */
    public function ajaxDeleteAttr(){

        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $post_info = I("post.");
        $attr_id = intval($post_info['attr_id']);

        $attr_obj = new \Yege\Attr();
        $attr_obj->attr_id = $attr_id;
        $result_info = array();
        $result_info = $attr_obj->deleteAttr();
        if($result_info['state'] == 1){
            $result['state'] = 1;
            $result['is_empty'] = $result_info['is_empty'];
            $result['parent_parent_id'] = 0;
            //尝试拿到父级的父级
            if($result['is_empty'] == 1){
                $info = $parent_info = array();
                $info = $attr_obj->getInfo($attr_id);
                if($info['state'] == 1){
                    $parent_info = $attr_obj->getInfo($info['result']['attr_parent_id']);
                    if($parent_info['state'] == 1){
                        $result['parent_parent_id'] = $parent_info['result']['attr_parent_id'];
                    }
                }
            }
            $result['message'] = "删除成功";
        }else{
            $result['message'] = "删除失败";
        }

        $this->ajaxReturn($result);
    }


}