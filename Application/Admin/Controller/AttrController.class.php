<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台属性管理控制器
 *
 * 相关方法
 * attrList                 属性列表
 * disposePostParam（私有）  属性列表参数判断
 * addAttr                  属性添加
 * editAttr                 编辑属性
 *
 */

class AttrController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->attr_model = D("Attr");

    }

    /**
     * 属性列表
     * @param int $parent_id 当前父级id
     */
    public function attrList($parent_id = 0){

        $dispose = array();
        $dispose = $this->disposePostParam();

        $list = array();
        $list = $this->attr_model->getAttrList($dispose['where'],$parent_id);

        $this->assign("parent_id",$parent_id);
        $this->assign("list",$list['list']);
        $this->assign("dispose",$dispose);
        $this->display();
    }

    /**
     * 属性列表参数判断
     * @return array $result
     */
    private function disposePostParam(){
        $result = array();
        $result['where'] = array();

        $post_info = array();
        $post_info = I("post.");



        return $result;
    }

    /**
     * 属性添加
     * @param int $parent_id 当前父级id
     */
    public function addAttr($parent_id = 0){
        $this->assign("parent_id",$parent_id);
        $this->display();
    }

    /**
     * 编辑属性
     * @param int $id 属性id
     */
    public function editAttr($id = 0){
        $attr_info = array();
        $attr_obj = new \Yege\Attr();
        $attr_info = $attr_obj->getInfo($id);

        if($attr_info['state'] == 1){

            if(IS_POST){
                $post_info = I("post.");
                $attr_obj = new \Yege\Attr();
                $attr_obj->attr_id = intval($id);
                $attr_obj->attr_name = trim($post_info['attr_name']);
                $edit_result = array();
                $edit_result = $attr_obj->editAttr();
                if($edit_result['state'] == 1){
                    redirect("/Admin/Attr/attrList/parent_id/".$attr_info['result']['parent_id']);
                }else{
                    $this->error($edit_result['message']);
                }
            }

            $this->assign("id",$id);
            $this->assign("info",$attr_info['result']);
            $this->display();

        }else{
            $this->error($attr_info['message']);
        }

    }

}