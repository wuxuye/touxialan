<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台用户控制器
 *
 * 相关方法
 * userList                             用户列表
 * disposePostParam(私有)                用户列表参数判断
 * addUser                              添加用户 暂不开放
 * editUser                             编辑用户
 * userMessageList                      用户消息记录列表
 * disposeUserMessagePostParam(私有)     处理用户消息记录列表的post请求
 * userPointList                        用户积分记录列表
 * disposeUserPointPostParam(私有)       处理用户积分记录列表的post请求
 * userPointOperation                   用户积分操作
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
            $start_time = is_date($post_info['search_start_time'])?strtotime(date("Y-m-d 00:00:00",strtotime($post_info['search_start_time']))):0;
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

        //获取用户信息
        $user_info = array();
        $user_obj = new \Yege\User();
        $user_obj->user_id = $id;
        $user_info = $user_obj->getUserInfo();

        if(!empty($user_info['result']['id'])){

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

                //获取用户积分
                $point_obj = new \Yege\Point();
                $point_obj->user_id = $id;
                $point_info = [];
                $point_info = $point_obj->getUserPointInfo();
                if($point_info['state'] != 1){
                    $this->error($point_info['message']);
                }
                //获取用户收货地址列表
                $address_list = [];
                $address_list = $user_obj->getUserReceiptAddress();
                if($address_list['state'] != 1){
                    $this->error($address_list['message']);
                }

                //页面操作显示
                $operation = [
                    'delete'=>['is_show'=>0,'value'=>C("STATE_USER_DELETE")],
                    'freeze'=>['is_show'=>0,'value'=>C("STATE_USER_FREEZE")],
                    'recovery'=>['is_show'=>0,'value'=>C("STATE_USER_NORMAL")],
                    'to_users'=>['is_show'=>0,'value'=>C("IDENTITY_USER_USERS")],
                    'to_admin'=>['is_show'=>0,'value'=>C("IDENTITY_USER_ADMIN")],
                ];
                //用户状态
                if($user_info['result']['state'] == C("STATE_USER_FREEZE")){ //冻结状态
                    $operation['delete']['is_show'] = 1;
                    $operation['recovery']['is_show'] = 1;
                }elseif($user_info['result']['state'] == C("STATE_USER_NORMAL")){ //正常状态
                    $operation['delete']['is_show'] = 1;
                    $operation['freeze']['is_show'] = 1;
                }elseif($user_info['result']['state'] == C("STATE_USER_DELETE")){ //删除状态
                    $operation['recovery']['is_show'] = 1;
                }
                //用户身份
                if($user_info['result']['identity'] == C("IDENTITY_USER_USERS")){ //用户身份
                    $operation['to_admin']['is_show'] = 1;
                }elseif($user_info['result']['identity'] == C("IDENTITY_USER_ADMIN")){ //管理员身份
                    $operation['to_users']['is_show'] = 1;
                }

                $this->assign("user_state_list",C("STATE_USER_STATE_LIST"));
                $this->assign("user_identity_list",C("IDENTITY_USER_STATE_LIST"));
                $this->assign("operation",$operation);
                $this->assign("info",$user_info['result']);
                $this->assign("point_info",$point_info['result']);
                $this->assign("address_list",$address_list['list']);
                $this->display();
            }
        }else{
            $this->error("未能获取信息");
        }
    }

    /**
     * 用户消息记录列表
     */
    public function userMessageList(){

        $message_obj = new \Yege\Message();

        $dispose = array();
        $dispose = $this->disposeUserMessagePostParam();

        $page_num = C("ADMIN_USER_MESSAGE_LIST_PAGE_SHOW_NUM");

        $list = [];
        $list = $message_obj->getUserMessageList($dispose['where'],0,$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $message_obj->getUserMessageList($dispose['where'],0,$dispose['page'],$page_num);
        }

        $all = $message_obj->getUserMessageList($dispose['where']);

        //分页
        $page_obj = new \Yege\Page($dispose['page'],count($all),$page_num);

        $this->assign("list",$list);
        $this->assign("page",$page_obj->show());
        $this->assign("dispose",$dispose);
        $this->display();

    }

    /**
     * 处理用户消息记录列表的post请求
     */
    private function disposeUserMessagePostParam(){
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
        if(!empty($post_info['search_start_time']) || !empty($post_info['search_end_time'])){
            $start_time = is_date($post_info['search_start_time'])?strtotime(date("Y-m-d 00:00:00",strtotime($post_info['search_start_time']))):0;
            $end_time = is_date($post_info['search_end_time'])?strtotime(date("Y-m-d 23:59:59",strtotime($post_info['search_end_time']))):0;

            //添加时间
            if(!empty($start_time)){
                $where['user_message.inputtime'][] = array("egt",$start_time);
                $result['search_start_time'] = $post_info['search_start_time'];
            }
            if(!empty($end_time)){
                $where['user_message.inputtime'][] = array("elt",$end_time);
                $result['search_end_time'] = $post_info['search_end_time'];
            }
        }

        //字段类型搜索
        $post_info['search_info'] = trim($post_info['search_info']);
        $post_info['search_info_type'] = intval($post_info['search_info_type']);
        if(!empty($post_info['search_info'])){
            switch($post_info['search_info_type']){
                case 1: //用户手机
                    $where['user.mobile'] = array('like',"%".$post_info['search_info']."%");
                    break;
                case 2: //消息内容
                    $where['user_message.remark'] = array('like',"%".$post_info['search_info']."%");
                    break;
            }
            $result['search_info_type'] = $post_info['search_info_type'];
            $result['search_info'] = $post_info['search_info'];
        }

        //是否显示给用户
        $result['search_is_show'] = -1; //默认值
        if(isset($post_info['search_is_show'])){
            $post_info['search_is_show'] = intval($post_info['search_is_show']);
            if($post_info['search_is_show'] >= 0){
                $where['user_message.is_show'] = $post_info['search_is_show'];
                $result['search_is_show'] = $post_info['search_is_show'];
            }
        }

        $result['where'] = $where;

        return $result;
    }

    /**
     * 用户积分记录列表
     * @param int $user_id 筛选用户id
     */
    public function userPointList($user_id = 0){

        $point_obj = new \Yege\Point();

        $dispose = array();
        $dispose = $this->disposeUserPointPostParam();

        $page_num = C("ADMIN_USER_POINT_LIST_PAGE_SHOW_NUM");

        $list = [];
        $list = $point_obj->getUserPointList($dispose['where'],0,$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $point_obj->getUserPointList($dispose['where'],0,$dispose['page'],$page_num);
        }

        //数据处理
        foreach($list as $key => $val){
            if($val['points'] > 0){
                $list[$key]['color_str'] = 'green_color';
            }elseif($val['points'] < 0){
                $list[$key]['color_str'] = 'red_color';
            }else{
                $list[$key]['color_str'] = 'yellow_color';
            }
        }

        $all = $point_obj->getUserPointList($dispose['where']);

        //分页
        $page_obj = new \Yege\Page($dispose['page'],count($all),$page_num);

        $this->assign("list",$list);
        $this->assign("page",$page_obj->show());
        $this->assign("dispose",$dispose);
        $this->assign("activity_point_list",C("ACTIVITY_POINT_LIST"));
        $this->display();

    }

    /**
     * 处理用户积分记录列表的post请求
     */
    private function disposeUserPointPostParam(){
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
        if(!empty($post_info['search_start_time']) || !empty($post_info['search_end_time'])){
            $start_time = is_date($post_info['search_start_time'])?strtotime(date("Y-m-d 00:00:00",strtotime($post_info['search_start_time']))):0;
            $end_time = is_date($post_info['search_end_time'])?strtotime(date("Y-m-d 23:59:59",strtotime($post_info['search_end_time']))):0;

            //添加时间
            if(!empty($start_time)){
                $where['user_points.inputtime'][] = array("egt",$start_time);
                $result['search_start_time'] = $post_info['search_start_time'];
            }
            if(!empty($end_time)){
                $where['user_points.inputtime'][] = array("elt",$end_time);
                $result['search_end_time'] = $post_info['search_end_time'];
            }
        }

        //字段类型搜索
        $post_info['search_info'] = trim($post_info['search_info']);
        $post_info['search_info_type'] = intval($post_info['search_info_type']);

        //get传值判断
        $user_id = I("get.user_id");
        if(!empty($user_id)){
            $post_info['search_info'] = $user_id;
            $post_info['search_info_type'] = 3;
        }

        if(!empty($post_info['search_info'])){
            switch($post_info['search_info_type']){
                case 1: //用户手机
                    $where['user.mobile'] = array('like',"%".$post_info['search_info']."%");
                    break;
                case 2: //备注信息
                    $where['user_points.remark'] = array('like',"%".$post_info['search_info']."%");
                    break;
                case 3: //用户id
                    $where['user_points.user_id'] = $post_info['search_info'];
                    break;
            }
            $result['search_info_type'] = $post_info['search_info_type'];
            $result['search_info'] = $post_info['search_info'];
        }

        //是否显示给用户
        $post_info['search_point_tab'] = trim($post_info['search_point_tab']);
        if(!empty($post_info['search_point_tab'])){
            $where['user_points.operation_tab'] = $post_info['search_point_tab'];
            $result['search_point_tab'] = $post_info['search_point_tab'];
        }

        $result['where'] = $where;

        return $result;
    }

    /**
     * 用户积分操作
     * @param int $user_id 用户id
     */
    public function userPointOperation($user_id = 0){
        //获取用户信息
        $user_info = array();
        $user_obj = new \Yege\User();
        $user_obj->user_id = $user_id;
        $user_info = $user_obj->getUserInfo();

        if(!empty($user_info['result']['user_id'])){

            if(IS_POST){
                $post_info = I("post.");

                $operation_point = intval($post_info['operation_point']);
                $operation_remark = trim($post_info['operation_remark']);
                if(empty($operation_point) || empty($operation_remark)){
                    $this->error("请正确填写 操作积分 与 操作原因");
                }

                $obj = new \Yege\Point();
                $obj->user_id = $user_id;
                $obj->operation_tab = 'admin_update_point';
                $obj->points = $operation_point;
                $obj->remark = $operation_remark;

                $result = [];
                $result = $obj->updateUserPoints();

                if($result['state'] == 1){
                    $this->success("操作成功");
                }else{
                    $this->error($result['message']);
                }

            }else{

                //获取用户积分
                $point_obj = new \Yege\Point();
                $point_obj->user_id = $user_id;
                $point_info = [];
                $point_info = $point_obj->getUserPointInfo();
                if($point_info['state'] != 1){
                    $this->error($point_info['message']);
                }

                $this->assign("info",$user_info['result']);
                $this->assign("point_info",$point_info['result']);
                $this->display();
            }
        }else{
            $this->error("未能获取用户信息");
        }
    }

}