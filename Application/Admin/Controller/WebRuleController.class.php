<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台前台规则控制器
 *
 * 相关方法
 * ruleList             前台规则列表
 * disposePostParam     前台规则列表参数判断
 *
 */

class WebRuleController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->web_rule_model = D("WebRule");
    }

    /**
     * 商品列表
     */
    public function ruleList(){
        $dispose = array();
        $dispose = $this->disposePostParam();

        $list = array();
        $list = $this->web_rule_model->getRuleList($dispose['where']);

        $this->assign("list",$list['list']);
        $this->assign("dispose",$dispose);
        $this->display();
    }

    /**
     * 前台规则列表参数判断
     * @return array $result
     */
    private function disposePostParam(){
        $result = ['where'=>[]];

        $post_info = [];
        $post_info = I("post.");

        $where = [];

        //规则名称搜索
        $post_info['search_rule_name'] = check_str($post_info['search_rule_name']);
        if(!empty($post_info['search_rule_name'])){
            $where['rule_name'] = array('like',"%".$post_info['search_rule_name']."%");
            $result['search_rule_name'] = $post_info['search_rule_name'];
        }

        //规则备注搜索
        $post_info['search_remark_name'] = check_str($post_info['search_remark_name']);
        if(!empty($post_info['search_remark_name'])){
            $where['rule_remark'] = array('like',"%".$post_info['search_remark_name']."%");
            $result['search_remark_name'] = $post_info['search_remark_name'];
        }

        $result['where'] = $where;

        return $result;
    }

    /**
     * 规则添加
     */
    public function addRule(){
        if(IS_POST) {
            $post_info = I("post.");
            $add_result = $this->web_rule_model->addRule($post_info);
            if($add_result['state'] == 1){
                $this->success("添加成功","/Admin/WebRule/ruleList");
            }else{
                $this->error($add_result['message']);
            }
        }else{
            $this->display();
        }
    }

    /**
     * 规则编辑
     */
    public function editRule($id = 0){
        $id = intval($id);
        $info = $this->web_rule_model->getRuleInfo($id);
        if(!empty($info)){
            if(IS_POST) {
                $post_info = I("post.");
                $edit_result = $this->web_rule_model->editRule($post_info);
                if($edit_result['state'] == 1){
                    $this->success("编辑成功","/Admin/WebRule/ruleList");
                }else{
                    $this->error($edit_result['message']);
                }
            }else{
                $this->assign("info",$info);
                $this->display();
            }
        }else{
            $this->error("规则详情未能获取");
        }
    }

}