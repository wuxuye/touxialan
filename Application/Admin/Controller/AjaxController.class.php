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
 * ====== 商品标签相关 ======
 * ajaxDeleteTag            删除标签
 * ajaxGetTagsListByGoodsId 根据商品id获取标签列表
 * ====== 属性相关 ======
 * ajaxGetAttrList  根据属性id获取属性列表
 * ajaxAddAttr      添加属性
 * ajaxDeleteAttr   删除属性
 *
 */

class AjaxController extends PublicController {

    public $result = [];
    public $post_info = [];

    public function _initialize(){
        parent::_initialize();

        //初始化结果
        $this->result['state'] = 0;
        $this->result['message'] = "未知错误";

        //获取提交参数
        $this->post_info = I("post.");
    }

    /**
     * 下架商品
     */
    public function ajaxUnshelveGoods(){

        $goods_id = intval($this->post_info['goods_id']);

        $goods_result = array();
        $goods_obj = new \Yege\Goods();
        $goods_obj->goods_id = $goods_id;
        $goods_result = $goods_obj->unshelveGoods();
        if($goods_result['state'] == 1){
            $this->result['state'] = 1;
            $this->result['message'] = "下架成功";
        }else{
            $this->result['message'] = $goods_result['message'];
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 上架商品
     */
    public function ajaxShelveGoods(){

        $goods_id = intval($this->post_info['goods_id']);

        $goods_result = array();
        $goods_obj = new \Yege\Goods();
        $goods_obj->goods_id = $goods_id;
        $goods_result = $goods_obj->shelveGoods();
        if($goods_result['state'] == 1){
            $this->result['state'] = 1;
            $this->result['message'] = "上架成功";
        }else{
            $this->result['message'] = $goods_result['message'];
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 删除商品
     */
    public function ajaxDeleteGoods(){

        $goods_id = intval($this->post_info['goods_id']);

        $goods_result = array();
        $goods_obj = new \Yege\Goods();
        $goods_obj->goods_id = $goods_id;
        $goods_result = $goods_obj->deleteGoods();
        if($goods_result['state'] == 1){
            $this->result['state'] = 1;
            $this->result['message'] = "删除成功";
        }else{
            $this->result['message'] = $goods_result['message'];
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 删除标签
     */
    public function ajaxDeleteTag(){
        $tag_id = intval($this->post_info['tag_id']);

        $tag_result = array();
        $tag_obj = new \Yege\Tag();
        $tag_obj->tag_id = $tag_id;
        $tag_result = $tag_obj->deleteTag();
        if($tag_result['state'] == 1){
            $this->result['state'] = 1;
            $this->result['message'] = "删除成功";
        }else{
            $this->result['message'] = $tag_result['message'];
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 根据商品id获取标签列表
     */
    public function ajaxGetTagsListByGoodsId(){
        $this->post_info['goods_id'] = intval($this->post_info['goods_id']);
        $tag_obj = new \Yege\Tag();
        $list = $tag_obj->getTagsListByGoodsId($this->post_info['goods_id']);
        $temp_list = array();
        foreach($list as $tag){
            if(!empty($tag['id'])){
                $temp_list[] = $tag['id'];
            }
        }
        //去重
        $temp_list = array_unique($temp_list);
        //将指定商品的标签id列表转换为json串返回
        $this->result['state'] = 1;
        $this->result['message'] = "获取成功";
        $this->result['tag_id_json'] = json_encode($temp_list);
        $this->ajaxReturn($this->result);
    }

    /**
     * 根据属性id获取属性列表
     */
    public function ajaxGetAttrList(){

        $this->post_info['attr_id'] = intval($this->post_info['attr_id']);
        if($this->post_info['attr_id'] >= 0){
            $attr_obj = new \Yege\Attr();
            $attr_obj->attr_id = $this->post_info['attr_id'];
            $attr_list = $attr_obj->getAttrListById();
            if($attr_list['state'] == 1){
                //数据检测
                if(!empty($attr_list['list'])){
                    $this->result['state'] = 1;
                    $this->result['message'] = "获取成功";
                    $this->result['attr_list'] = $attr_list['list'];
                }else{
                    $this->result['message'] = "未能获取属性信息";
                }
            }else{
                $this->result['message'] = $attr_list['message'];
            }
        }else{
            $this->result['message'] = "属性id错误";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 添加属性
     */
    public function ajaxAddAttr(){

        $attr_name = trim($this->post_info['attr_name']);
        $attr_parent_id = intval($this->post_info['attr_parent_id']);
        $attr_obj = new \Yege\Attr();
        $attr_obj->attr_name = $attr_name;
        $attr_obj->attr_parent_id = $attr_parent_id;
        $add_result = array();
        $add_result = $attr_obj->addAttr();
        if($add_result['state'] == 1){
            $this->result['state'] = 1;
            $this->result['message'] = "添加成功";
        }else{
            $this->result['message'] = "添加失败：".$add_result['message'];
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 删除属性
     */
    public function ajaxDeleteAttr(){

        $attr_id = intval($this->post_info['attr_id']);

        $attr_obj = new \Yege\Attr();
        $attr_obj->attr_id = $attr_id;
        $result_info = array();
        $result_info = $attr_obj->deleteAttr();
        if($result_info['state'] == 1){
            $this->result['state'] = 1;
            $this->result['is_empty'] = $result_info['is_empty'];
            $this->result['parent_parent_id'] = 0;
            //尝试拿到父级的父级
            if($this->result['is_empty'] == 1){
                $info = $parent_info = array();
                $info = $attr_obj->getInfo($attr_id);
                if($info['state'] == 1){
                    $parent_info = $attr_obj->getInfo($info['result']['attr_parent_id']);
                    if($parent_info['state'] == 1){
                        $this->result['parent_parent_id'] = $parent_info['result']['attr_parent_id'];
                    }
                }
            }
            $this->result['message'] = "删除成功";
        }else{
            $this->result['message'] = "删除失败";
        }

        $this->ajaxReturn($this->result);
    }


}