<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台AJAX控制器
 *
 * 相关方法
 *
 * ====== 商品相关 ======
 * ajaxUnshelveGoods  下架商品
 * ajaxShelveGoods    上架商品
 * ajaxDeleteGoods    删除商品
 *
 * ====== 商品标签相关 ======
 * ajaxTagAdd               新增标签
 * ajaxDeleteTag            删除标签
 * ajaxGetTagsListByGoodsId 根据商品id获取标签列表
 *
 * ====== 属性相关 ======
 * ajaxGetAttrNameById  根据属性id获取属性名称
 * ajaxGetAttrList      根据属性id获取属性列表
 * ajaxAddAttr          添加属性
 * ajaxDeleteAttr       删除属性
 *
 * ====== 订单相关 ======
 * ajaxConfirmOrder     确认订单
 * ajaxConfirmPay       确认付款
 * ajaxToDelivery       订单转配送中
 * ajaxSuccessOrder     完成订单
 * ajaxCloseOrder       关闭订单
 * ajaxRefundOrder      退款订单
 *
 * ====== 用户相关 ======
 * ajaxAddUser              添加用户
 * ajaxChangeUserState      改变用户状态
 * ajaxChangeUserIdentity   改变用户身份
 * ajaxResetUserResetCode   重置用户重置用安全码
 * ajaxDeleteUserMessage    删除用户消息记录
 *
 * ====== 活动相关 ======
 *  每日答题活动
 * ajaxUpdateQuestionState          修改题目状态
 * ajaxIsNextPublish                设为次日发布
 * ajaxDeleteQuestionImage          删除题目图片
 * ajaxGetStatisticsData            获取统计信息
 * ajaxUpdateAttrStatisticsData     更新属性统计
 *
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

        $goods_id = check_int($this->post_info['goods_id']);

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

        $goods_id = check_int($this->post_info['goods_id']);

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

        $goods_id = check_int($this->post_info['goods_id']);

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
     * 添加标签
     */
    public function ajaxTagAdd(){

        $tag_name = check_str($this->post_info['tag_name']);

        $tag_obj = new \Yege\Tag();
        $tag_obj->tag_name = $tag_name;
        $add_result = [];
        $add_result = $tag_obj->addTag();

        if($add_result['state'] == 1){
            $this->result['state'] = 1;
            $this->result['message'] = "添加成功";
        }else{
            $this->result['message'] = $add_result['message'];
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 删除标签
     */
    public function ajaxDeleteTag(){
        $tag_id = check_int($this->post_info['tag_id']);

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
        $this->post_info['goods_id'] = check_int($this->post_info['goods_id']);
        $tag_obj = new \Yege\Tag();
        $list = $tag_obj->getTagsListByGoodsId($this->post_info['goods_id']);
        $temp_list = array();
        foreach($list as $tag){
            if(!empty($tag['id'])){
                $temp_list[] = check_int($tag['id']);
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
     * 根据属性id获取属性名称
     */
    public function ajaxGetAttrNameById(){
        $this->post_info['attr_id'] = check_int($this->post_info['attr_id']);

        $info = [];
        $Attr = new \Yege\Attr();
        $info = $Attr->getInfo($this->post_info['attr_id']);
        if($info['state'] == 1){
            $this->result['state'] = 1;
            $this->result['name'] = $info['result']['attr_name'];
            $this->result['message'] = "获取成功";
        }else{
            $this->result['message'] = $info['message'];
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 根据属性id获取属性列表
     */
    public function ajaxGetAttrList(){

        $this->post_info['attr_id'] = check_int($this->post_info['attr_id']);
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

        $attr_name = check_str($this->post_info['attr_name']);
        $attr_parent_id = check_int($this->post_info['attr_parent_id']);
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

        $attr_id = check_int($this->post_info['attr_id']);

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
                    $parent_info = $attr_obj->getInfo($info['result']['parent_id']);
                    if($parent_info['state'] == 1){
                        $this->result['parent_parent_id'] = $parent_info['result']['parent_id'];
                    }
                }
            }
            $this->result['message'] = "删除成功";
        }else{
            $this->result['message'] = "删除失败";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 确认订单
     */
    public function ajaxConfirmOrder(){
        if(wait_action()){
            $order_id = check_int($this->post_info['order_id']);
            $order_obj = new \Yege\Order();
            $order_obj->order_id = $order_id;
            $result = [];
            $result = $order_obj->updateOrderStateUnifiedInlet("confirm_order");
            if($result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = "操作成功";
            }else{
                $this->result['message'] = "操作失败：".$result['message'];
            }
        }else{
            $this->result['message'] = "操作过于频繁";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 确认付款
     */
    public function ajaxConfirmPay(){
        if(wait_action()){
            $order_id = check_int($this->post_info['order_id']);
            $order_obj = new \Yege\Order();
            $order_obj->order_id = $order_id;
            $result = [];
            $result = $order_obj->updateOrderStateUnifiedInlet("confirm_pay");
            if($result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = "操作成功";
            }else{
                $this->result['message'] = "操作失败：".$result['message'];
            }
        }else{
            $this->result['message'] = "操作过于频繁";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 订单转配送中
     */
    public function ajaxToDelivery(){
        if(wait_action(10)){
            $order_id = check_str($this->post_info['order_id']);

            $order_id = str_replace("，",",",$order_id);
            $order_list = explode(",",$order_id);
            if(!empty($order_list)){
                $wrong_list = "";
                foreach($order_list as $info){
                    $order_obj = new \Yege\Order();
                    $order_obj->order_id = $info;
                    $result = [];
                    $result = $order_obj->updateOrderStateUnifiedInlet("to_delivery");
                    if($result['state'] != 1){
                        $wrong_list .= "订单id：".$order_obj->order_id."操作失败：".$result['message']."\r\n";
                    }
                }

                if(empty($wrong_list)){
                    $this->result['state'] = 1;
                    $this->result['message'] = "操作成功";
                }else{
                    $this->result['message'] = $wrong_list;
                }

            }else{
                $this->result['message'] = "没有可操作的订单";
            }
        }else{
            $this->result['message'] = "操作过于频繁";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 完成订单
     */
    public function ajaxSuccessOrder(){
        if(wait_action(10)){
            $order_id = check_str($this->post_info['order_id']);

            $order_id = str_replace("，",",",$order_id);
            $order_list = explode(",",$order_id);
            if(!empty($order_list)){
                $wrong_list = "";
                foreach($order_list as $info){
                    if(empty($info) || $info <= 0){
                        continue;
                    }
                    $order_obj = new \Yege\Order();
                    $order_obj->order_id = $info;
                    $result = [];
                    $result = $order_obj->updateOrderStateUnifiedInlet("success_order");
                    if($result['state'] != 1){
                        $wrong_list .= "订单id：".$order_obj->order_id."操作失败：".$result['message']."\r\n";
                    }
                }

                if(empty($wrong_list)){
                    $this->result['state'] = 1;
                    $this->result['message'] = "操作成功";
                }else{
                    $this->result['message'] = $wrong_list;
                }

            }else{
                $this->result['message'] = "没有可操作的订单";
            }
        }else{
            $this->result['message'] = "操作过于频繁";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 关闭订单
     */
    public function ajaxCloseOrder(){
        if(wait_action()){
            $order_id = check_int($this->post_info['order_id']);
            $operation_remark = check_str($this->post_info['operation_remark']);
            $order_obj = new \Yege\Order();
            $order_obj->order_id = $order_id;
            $order_obj->operation_remark = $operation_remark;
            $result = [];
            $result = $order_obj->updateOrderStateUnifiedInlet("close_order");
            if($result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = "操作成功";
            }else{
                $this->result['message'] = "操作失败：".$result['message'];
            }
        }else{
            $this->result['message'] = "操作过于频繁";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 退款订单
     */
    public function ajaxRefundOrder(){
        if(wait_action()){
            $order_id = check_int($this->post_info['order_id']);
            $operation_remark = check_str($this->post_info['operation_remark']);
            $order_obj = new \Yege\Order();
            $order_obj->order_id = $order_id;
            $order_obj->operation_remark = $operation_remark;
            $result = [];
            $result = $order_obj->updateOrderStateUnifiedInlet("refund_order");
            if($result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = "操作成功";
            }else{
                $this->result['message'] = "操作失败：".$result['message'];
            }
        }else{
            $this->result['message'] = "操作过于频繁";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 添加用户
     */
    public function ajaxAddUser(){
        P($this->post_info);
    }

    /**
     * 改变用户状态
     */
    public function ajaxChangeUserState(){
        if(wait_action()){
            $user_id = check_int($this->post_info['user_id']);
            $state = check_int($this->post_info['state']);
            $remark = check_str($this->post_info['remark']);

            if(empty(C("STATE_USER_STATE_LIST")[$state])){
                $this->result['message'] = "状态错误";
                $this->ajaxReturn($this->result);
            }

            if($state == C("STATE_USER_FREEZE") && empty($remark)){
                $this->result['message'] = "冻结用户必须填写原因";
                $this->ajaxReturn($this->result);
            }

            $user_model = D("User");
            if(!empty($user_id)){
                $result = [];
                $result = $user_model->changeUserState($user_id,$state);
                if($result['state'] == 1){

                    $log = "";
                    if($state == C("STATE_USER_FREEZE")){
                        $log = "因：".$remark." ，用户被暂时冻结，部分功能受限。";
                    }elseif($state == C("STATE_USER_NORMAL")){
                        $log = "用户被恢复正常状态。";
                        $log = empty($remark) ? $log : "因：".$remark." ，".$log;
                    }elseif($state == C("STATE_USER_DELETE")){
                        $log = "用户被删除。";
                        $log = empty($remark) ? $log : "因：".$remark." ，".$log;
                    }
                    //记一波操作记录
                    add_user_message($user_id,$log,1);

                    $this->result['state'] = 1;
                    $this->result['message'] = "修改成功";
                }else{
                    $this->result['message'] = $result['message'];
                }
            }else{
                $this->result['message'] = "参数缺失";
            }

        }else{
            $this->result['message'] = "操作过于频繁";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 改变用户身份
     */
    public function ajaxChangeUserIdentity(){

        if(wait_action()){
            $user_id = check_int($this->post_info['user_id']);
            $identity = check_int($this->post_info['identity']);

            $user_model = D("User");
            if(!empty($user_id)){
                $result = [];
                $result = $user_model->changeUserIdentity($user_id,$identity);
                if($result['state'] == 1){

                    //记一波操作记录
                    add_user_message($user_id,"后台修改了用户的身份，现在用户身份为:".$identity."(".C('IDENTITY_USER_STATE_LIST')[$identity].")");

                    $this->result['state'] = 1;
                    $this->result['message'] = "修改成功";
                }else{
                    $this->result['message'] = $result['message'];
                }
            }else{
                $this->result['message'] = "参数缺失";
            }

        }else{
            $this->result['message'] = "操作过于频繁";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 重置用户重置用安全码
     */
    public function ajaxResetUserResetCode(){
        if(wait_action()){
            $user_id = check_int($this->post_info['user_id']);

            $user_model = D("User");
            if(!empty($user_id)){
                $result = [];
                $result = $user_model->resetUserResetCode($user_id);
                if($result['state'] == 1){

                    //记一波操作记录
                    add_user_message($user_id,"后台重置了用户的重置用安全码，现在用户的重置用安全码为:".$result['reset_code']);


                    $this->result['state'] = 1;
                    $this->result['message'] = "重置成功";
                }else{
                    $this->result['message'] = $result['message'];
                }
            }else{
                $this->result['message'] = "参数缺失";
            }

        }else{
            $this->result['message'] = "操作过于频繁";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 删除用户消息记录
     */
    public function ajaxDeleteUserMessage(){
        $message_id = check_int($this->post_info['message_id']);

        $result = [];
        $user_model = D("User");
        $result = $user_model->deleteUserMessage($message_id);
        if($result['state'] == 1){
            $this->result['state'] = 1;
            $this->result['message'] = "操作成功";
        }else{
            $this->result['message'] = $result['message'];
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 修改题目状态
     */
    public function ajaxUpdateQuestionState(){

        $question_id = check_int($this->post_info['question_id']);
        $state = check_int($this->post_info['state']);
        if(!empty($question_id) && !empty($state)){
            if(!empty(C("STATE_ACTIVITY_QUESTION_BANK_STATE_LIST")[$state])){
                $obj_result = [];
                $obj = new \Yege\ActivityQuestion();
                $obj_result = $obj->updateQuestionState($question_id,$state);
                if($obj_result['state'] == 1){
                    $this->result['state'] = 1;
                    $this->result['message'] = "修改成功";
                }else{
                    $this->result['message'] = $obj_result['message'];
                }
            }else{
                $this->result['message'] = "错误的状态参数";
            }
        }else{
            $this->result['message'] = "参数缺失";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 设为次日发布
     */
    public function ajaxIsNextPublish(){
        $question_id = check_int($this->post_info['question_id']);

        if(!empty($question_id)){
            $obj_result = [];
            $obj = new \Yege\ActivityQuestion();
            $obj_result = $obj->setNextPublish($question_id);

            if($obj_result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = "修改成功";
            }else{
                $this->result['message'] = $obj_result['message'];
            }
        }else{
            $this->result['message'] = "参数缺失";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 删除题目图片
     */
    public function ajaxDeleteQuestionImage(){
        $question_id = check_int($this->post_info['question_id']);

        if(!empty($question_id)){
            $obj_result = [];
            $obj = new \Yege\ActivityQuestion();
            $obj_result = $obj->deleteQuestionImage($question_id);

            if($obj_result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = "修改成功";
            }else{
                $this->result['message'] = $obj_result['message'];
            }
        }else{
            $this->result['message'] = "参数缺失";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 获取统计信息（每日问答统计）
     */
    public function ajaxGetStatisticsData(){

        if(wait_action(1)){
            $level = check_int($this->post_info['level']);
            $time = check_str($this->post_info['time']);
            if(empty($level)){
                $level = 2; //默认等级
            }
            if(empty($time)){
                $time = ""; //默认时间
            }

            $data = [];
            $obj = new \Yege\ActivityQuestion();
            $data = $obj->getStatisticsData($level,$time);

            $this->result['state'] = 1;
            $this->result['message'] = "获取成功";
            $this->result['data'] = $data;
        }else{
            $this->result['message'] = "操作过于频繁，请稍后再试";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 更新属性统计
     */
    public function ajaxUpdateAttrStatisticsData(){
        if(wait_action()){
            $result = [];
            $result = D("Statistics")->statisticsAttr();

            if($result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = "更新成功";
            }else{
                $this->result['message'] = "更新失败：".$result['message'];
            }
        }else{
            $this->result['message'] = "操作过于频繁，请稍后再试";
        }

        $this->ajaxReturn($this->result);
    }

}