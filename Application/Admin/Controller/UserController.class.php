<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台用户控制器
 *
 * 相关方法
 *
 */

class UserController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->user_model = D("User");
    }

    /**
     * 用户列表
     */
    public function userList(){
        $dispose = array();
        $dispose = $this->disposePostParam();

        //单页数量
        $page_num = C("ADMIN_USER_LIST_PAGE_SHOW_NUM");

        $list = array();
        $list = $this->user_model->getUserList($dispose['where'],$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list['list']) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $this->user_model->getUserList($dispose['where'],$dispose['page'],$page_num);
        }

        //分页
        $page_obj = new \Yege\Page($dispose['page'],$list['count'],$page_num);

        $this->assign("search_time_type_list",C("ADMIN_USER_LIST_SEARCH_TIME_TYPE_LIST"));
        $this->assign("search_info_type_list",C("ADMIN_USER_LIST_SEARCH_INFO_TYPE_LIST"));
        $this->assign("search_user_state_list",C("STATE_USER_STATE_LIST"));
        $this->assign("search_user_identity_list",C("IDENTITY_USER_STATE_LIST"));
        $this->assign("list",$list['list']);
        $this->assign("page",$page_obj->show());
        $this->assign("dispose",$dispose);
        $this->display();
    }

    /**
     * 用户列表参数判断
     * @return array $result
     */
    private function disposePostParam(){
        $result = array();
        $result['where'] = array();
        $result['page'] = 1;

        $post_info = array();
        $post_info = I("post.");

        //页码
        if(!empty($post_info['search_now_page'])){
            $result['page'] = $post_info['search_now_page'];
        }

        $where = array();

        //时间搜索类型
        $post_info['search_start_time'] = trim($post_info['search_start_time']);
        $post_info['search_end_time'] = trim($post_info['search_end_time']);
        $post_info['search_time_type'] = intval($post_info['search_time_type']);
        if(!empty($post_info['search_start_time']) || !empty($post_info['search_end_time'])){
            $start_time = is_date($post_info['search_start_time'])?strtotime($post_info['search_start_time']):0;
            $end_time = is_date($post_info['search_end_time'])?strtotime(date("Y-m-d 23:59:59",strtotime($post_info['search_end_time']))):0;
            switch($post_info['search_time_type']){
                case 1: //用户注册时间
                    if(!empty($start_time)){
                        $where['user.inputtime'][] = array("egt",$start_time);
                        $result['search_start_time'] = $post_info['search_start_time'];
                    }
                    if(!empty($end_time)){
                        $where['user.inputtime'][] = array("elt",$end_time);
                        $result['search_end_time'] = $post_info['search_end_time'];
                    }
                    break;
                case 2: //最后活跃时间
                    if(!empty($start_time)){
                        $where['user.active_time'][] = array("egt",$start_time);
                        $result['search_start_time'] = $post_info['search_start_time'];
                    }
                    if(!empty($end_time)){
                        $where['user.active_time'][] = array("elt",$end_time);
                        $result['search_end_time'] = $post_info['search_end_time'];
                    }
                    break;
            }
            $result['search_time_type'] = $post_info['search_time_type'];
        }

        //字段类型搜索
        $post_info['search_info'] = trim($post_info['search_info']);
        $post_info['search_info_type'] = intval($post_info['search_info_type']);
        if(!empty($post_info['search_info'])){
            switch($post_info['search_info_type']){
                case 1: //用户id
                    $post_info['search_info'] = intval($post_info['search_info']);
                    $where['user.id'] = $post_info['search_info'];
                    break;
                case 2: //用户名
                    $where['user.username'] = array('like',"%".$post_info['search_info']."%");
                    break;
                case 3: //用户昵称
                    $where['user.nick_name'] = array('like',"%".$post_info['search_info']."%");
                    break;
            }
            $result['search_info_type'] = $post_info['search_info_type'];
            $result['search_info'] = $post_info['search_info'];
        }

        //手机号
        $post_info['search_user_mobile'] = trim($post_info['search_user_mobile']);
        if(!empty($post_info['search_user_mobile'])){
            $where['user.mobile'] = array('like',"%".$post_info['search_user_mobile']."%");
            $result['search_user_mobile'] = $post_info['search_user_mobile'];
        }

        //用户状态搜索
        $result['search_user_state'] = -1;
        if($post_info['search_user_state'] > -1){
            $post_info['search_user_state'] = intval($post_info['search_user_state']);
            $where['user.state'] = $post_info['search_user_state'];
            $result['search_user_state'] = $post_info['search_user_state'];
        }

        //用户身份搜索
        $result['search_user_identity'] = -1;
        if($post_info['search_user_identity'] > -1){
            $post_info['search_user_identity'] = intval($post_info['search_user_identity']);
            $where['user.identity'] = $post_info['search_user_identity'];
            $result['search_user_identity'] = $post_info['search_user_identity'];
        }

        $result['where'] = $where;

        return $result;
    }

    /**
     * 添加用户 暂不开放
     */
    public function addUser(){
        $this->assign("user_identity_list",C("IDENTITY_USER_STATE_LIST"));
        $this->display();
    }

    /**
     * 编辑用户
     * @param int $id 用户id
     */
    public function editUser($id = 0){

        //获取商品信息
        $user_info = array();
        $user_obj = new \Yege\User();
        $user_obj->user_id = $id;
        $user_info = $user_obj->getUserInfo();

        if(!empty($user_info['result']['user_id'])){

            if(IS_POST){
                $post_info = I("post.");

                $edit_result = [];
                $user_obj->nick_name = $post_info['user_nick_name'];
                $user_obj->user_mobile = $post_info['user_mobile'];
                $edit_result = $user_obj->userEditData();

                if($edit_result['state'] == 1){

                    //记一波操作记录
                    add_user_message($id,"后台修改了用户信息，nick_name:".$user_obj->nick_name.",mobile:".$user_obj->user_mobile);

                    $this->success("编辑成功","/Admin/User/editUser/id/".$id);
                }else{
                    $this->error($edit_result['message']);
                }

            }else{
                $this->assign("user_state_list",C("STATE_USER_STATE_LIST"));
                $this->assign("user_identity_list",C("IDENTITY_USER_STATE_LIST"));
                $this->assign("user_identity_users",C("IDENTITY_USER_USERS"));
                $this->assign("user_identity_admin",C("IDENTITY_USER_ADMIN"));
                $this->assign("info",$user_info['result']);
                $this->display();
            }
        }else{
            $this->error("未能获取信息");
        }
    }

}