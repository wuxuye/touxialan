<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 用户中心控制器
 *
 * 相关方法
 * index                        用户中心首页
 * userOrderList                我的订单
 * userOrderListDisposeData     用户订单列表参数处理
 * userEditPassword             用户密码修改
 * userReceiptAddressList       用户收货地址列表
 * userAddReceiptAddress        增加收货地址
 * userEditReceiptAddress       编辑收货地址
 * userMessage                  用户消息管理
 * userMessageListDisposeData   用户信息提醒列表参数处理
 * showFeedback                 反馈详情查看
 * userInfo                     用户信息查看
 * userPoint                    用户积分列表
 * userPointListDisposeData     用户积分列表参数处理
 */

class UserCenterController extends UserController {

    public function _initialize(){
        parent::_initialize();

        //首先获取用户登录信息
        if(empty($this->now_user_info['id'])){
            //记录回跳url
            set_login_back_url();
            //跳转至用户登录
            redirect("/Home/User/userLogin");
        }

        $this->user_center_tag_list = C("HOME_USER_CENTER_TAG_LIST");
    }

    /**
     * 用户中心首页
     */
    public function index(){
        //跳去我的订单页
        redirect("/Home/UserCenter/userOrderList");
    }

    /**
     * 我的订单
     */
    public function userOrderList(){
        $this->active_tag = "order";

        $data = $this->userOrderListDisposeData();

        $order_obj = new \Yege\Order();
        $order_obj->user_id = $this->now_user_info['id'];
        $order_list = $order_obj->getUserOrderList($data['where'],$data['page'],C("HOME_USER_ORDER_LIST_MAX_ORDER_NUM"));

        //分页
        $page_obj = new \Yege\IndexPage($order_list['count'],C("HOME_USER_ORDER_LIST_MAX_ORDER_NUM"));

        //各种状态的颜色显示
        $state_list = [
            C("STATE_ORDER_WAIT_CONFIRM") => "public_red_color", //待确认
            C("STATE_ORDER_WAIT_DELIVERY") => "public_red_color", //待发货
            C("STATE_ORDER_DELIVERY_ING") => "public_green_color", //配送中
            C("STATE_ORDER_WAIT_SETTLEMENT") => "public_red_color", //待结算
            C("STATE_ORDER_SUCCESS") => "public_green_color", //已完成
            C("STATE_ORDER_CLOSE") => "public_gray_color", //已关闭
            C("STATE_ORDER_DISSENT") => "public_red_color", //有异议
            C("STATE_ORDER_BACK") => "public_gray_color", //已退款
        ];
        $confirm_list = [
            "0" => "public_red_color",
            "1" => "public_green_color",
        ];
        $pay_list = [
            "0" => "public_red_color",
            "1" => "public_green_color",
        ];

        $this->assign("state_list",$state_list);
        $this->assign("confirm_list",$confirm_list);
        $this->assign("pay_list",$pay_list);
        $this->assign("order_list",$order_list);
        $this->assign("page",$page_obj->show());
        $this->display();
    }

    /**
     * 用户订单列表参数处理
     * $return array $data 数据返回
     */
    private function userOrderListDisposeData(){
        $data = ['where'=>[],'data'=>[],'page'=>1];
        $get_info = I("get.");

        //页码
        if(!empty($get_info['page'])){
            $data['page'] = check_int($get_info['page']);
        }
        $data['page'] = $data['page'] > 0 ? $data['page'] : 1;

        $where = [];

        $data['where'] = $where;

        return $data;
    }

    /**
     * 用户密码修改
     */
    public function userEditPassword(){
        $this->active_tag = "password";
        $this->display();
    }

    /**
     * 用户收货地址列表
     */
    public function userReceiptAddressList(){
        $this->active_tag = "address";
        $user_obj = new \Yege\User();
        $user_obj->user_id = $this->now_user_info['id'];
        $address_list = [];
        $address_list = $user_obj->getUserReceiptAddress();

        $this->assign("address_list",$address_list['list']);
        $this->assign("can_add",count($address_list['list']) < C("HOME_USER_MAX_RECEIPT_ADDRESS_NUM") ? 1 : 0);
        $this->display();
    }

    /**
     * 增加收货地址
     */
    public function userAddReceiptAddress(){
        $this->active_tag = "address";
        $this->display();
    }

    /**
     * 编辑收货地址
     * @param int $id 收货地址id
     */
    public function userEditReceiptAddress($id = 0){
        $this->active_tag = "address";
        $user_obj = new \Yege\User();
        $user_obj->user_id = $this->now_user_info['id'];
        //获取详情
        $info = [];
        $info = $user_obj->getUserReceiptAddressInfo($id);
        if($info['state'] == 1){
            $this->assign("info",$info['info']);
            $this->display();
        }else{
            $this->error($info['message']);
        }
    }

    /**
     * 用户信息提醒列表
     */
    public function userMessage(){
        $this->active_tag = "message";

        $data = $this->userMessageListDisposeData();

        $page_num = C("HOME_USER_MESSAGE_LIST_MAX_ORDER_NUM");

        $message_obj = new \Yege\Message();
        $message_list = [];
        $message_list = $message_obj->getUserMessageList($data['where'],0,$data['page'],$page_num);
        $all = $message_obj->getUserMessageList($data['where']);

        //分页
        $page_obj = new \Yege\IndexPage(count($all),$page_num);

        //清除用户消息提醒
        $message_obj->user_id = $this->now_user_info['id'];
        $message_obj->cleanUserMessageTip();

        $this->assign("message_list",$message_list);
        $this->assign("get_info",$data['param']);
        $this->assign("page",$page_obj->show());
        $this->display();
    }

    /**
     * 用户信息提醒列表参数处理
     * $return array $data 数据返回
     */
    private function userMessageListDisposeData(){
        $data = ['where'=>[],'data'=>[],'page'=>1,'param'=>[]];
        $get_info = I("get.");

        //页码
        if(!empty($get_info['page'])){
            $data['page'] = check_int($get_info['page']);
        }
        $data['page'] = $data['page'] > 0 ? $data['page'] : 1;

        //基础条件
        $where = [
            "user_message.is_show" => 1,
            "user_message.is_delete" => 0,
            "user_message.user_id" => $this->now_user_info['id'],
        ];

        //时间搜索
        $start_time = is_date($get_info['search_start_time'])?strtotime($get_info['search_start_time']):0;
        $end_time = is_date($get_info['search_end_time'])?strtotime(date("Y-m-d 23:59:59",strtotime($get_info['search_end_time']))):0;
        $data['param']['search_start_time'] = "";
        $data['param']['search_end_time'] = "";
        if(!empty($start_time)){
            $where['user_message.inputtime'][] = array("egt",$start_time);
            $data['param']['search_start_time'] = date("Y-m-d",$start_time);
        }
        if(!empty($end_time)){
            $where['user_message.inputtime'][] = array("elt",$end_time);
            $data['param']['search_end_time'] = date("Y-m-d",$end_time);
        }

        //内容搜索
        $search_content = empty($get_info['search_content']) ? "" : check_str($get_info['search_content']);
        $data['param']['search_content'] = "";
        if(!empty($search_content)){
            $where['user_message.remark'] = ['like','%'.$search_content.'%'];
            $data['param']['search_content'] = $search_content;
        }

        $data['where'] = $where;

        return $data;
    }

    /**
     * 反馈详情查看
     */
    public function showFeedback($id = 0){
        $this->active_tag = "message";

        $id = check_int($id);
        $feedback_obj = new \Yege\Feedback();
        $feedback_obj->feedback_id = $id;
        $feedback_info = $feedback_obj->getFeedbackInfo();
        if(!empty($feedback_info) && $feedback_info['user_id'] == $this->now_user_info['id'] && $feedback_info['is_solve'] == 1){
            $this->assign("feedback_info",$feedback_info);
            $this->display();
        }else{
            $this->error("信息错误");
        }

    }

    /**
     * 用户信息查看
     */
    public function userInfo(){
        $this->active_tag = "user_info";

        //用户详情获取
        $user_id = check_int($this->now_user_info['id']);
        $user_obj = new \Yege\User();
        $user_obj->user_id = $user_id;
        $user_info = $user_obj->getUserInfo();

        if($user_info['state'] == 1 && !empty($user_info['result']['id'])){
            $user_info = $user_info['result'];

            //积分数据获取
            $point_info = [];
            $point_obj = new \Yege\Point();
            $point_obj->user_id = $user_id;
            $point_temp = $point_obj->getUserPointInfo();
            if($point_temp['state'] == 1){
                $point_info = $point_temp['result'];
            }

            if(empty($point_info)){
                $this->error("积分信息获取失败");
            }

            $this->assign("user_info",$user_info);
            $this->assign("point_info",$point_info);
            $this->display();
        }else{
            $this->error("信息错误");
        }

    }

    /**
     * 用户积分列表
     */
    public function userPoint(){
        $this->active_tag = "user_info";

        $data = $this->userPointListDisposeData();

        $page_num = C("HOME_USER_POINT_LIST_MAX_ORDER_NUM");

        $message_obj = new \Yege\Message();
        $point_list = [];
        $point_list = $message_obj->getUserPointLogList($data['where'],0,$data['page'],$page_num);
        $all = $message_obj->getUserPointLogList($data['where']);

        //分页
        $page_obj = new \Yege\IndexPage(count($all['list']),$page_num);

        $this->assign("point_list",$point_list['list']);
        $this->assign("get_info",$data['param']);
        $this->assign("page",$page_obj->show());
        $this->display();
    }

    /**
     * 用户积分列表参数处理
     */
    public function userPointListDisposeData(){
        $data = ['where'=>[],'data'=>[],'page'=>1,'param'=>[]];
        $get_info = I("get.");

        //页码
        if(!empty($get_info['page'])){
            $data['page'] = check_int($get_info['page']);
        }
        $data['page'] = $data['page'] > 0 ? $data['page'] : 1;

        //基础条件
        $where = [
            "user_id" => $this->now_user_info['id'],
        ];

        $data['where'] = $where;

        return $data;
    }

}