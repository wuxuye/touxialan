<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台订单控制器
 *
 * 相关方法
 * orderList                商品列表
 * disposePostParam（私有）  商品列表参数判断
 * orderInfo                订单详情
 * orderUnifiedOperation    订单统一操作
 */

class OrderController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->order_model = D("Order");
    }

    /**
     * 订单列表
     */
    public function orderList(){
        $dispose = [];
        $dispose = $this->disposePostParam();

        //单页数量
        $page_num = C("ADMIN_ORDER_LIST_PAGE_SHOW_NUM");

        $list = [];
        $list = $this->order_model->getOrderList($dispose['where'],$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list['list']) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $this->order_model->getOrderList($dispose['where'],$dispose['page'],$page_num);
        }

        //状态颜色
        $state_color_list = [
            C("STATE_ORDER_WAIT_CONFIRM") => "red_color", //待确认
            C("STATE_ORDER_WAIT_DELIVERY") => "red_color", //待发货
            C("STATE_ORDER_DELIVERY_ING") => "red_color", //配送中
            C("STATE_ORDER_WAIT_SETTLEMENT") => "red_color", //待结算
            C("STATE_ORDER_SUCCESS") => "green_color", //已完成
            C("STATE_ORDER_CLOSE") => "gray_color", //已关闭
            C("STATE_ORDER_DISSENT") => "red_color", //有异议
            C("STATE_ORDER_BACK") => "gray_color", //已退款
        ];

        //分页
        $page_obj = new \Yege\Page($dispose['page'],$list['count'],$page_num);
        $this->assign("search_time_type_list",C("ADMIN_ORDER_LIST_SEARCH_TIME_TYPE_LIST"));
        $this->assign("search_info_type_list",C("ADMIN_ORDER_LIST_SEARCH_INFO_TYPE_LIST"));
        $this->assign("search_order_state_list",C("STATE_ORDER_LIST"));
        $this->assign("search_send_week_list",C("SHOP_SEND_WEEK_LIST"));
        $this->assign("search_send_time_list",C("SHOP_SEND_TIME_LIST"));
        $this->assign("state_color_list",$state_color_list);
        $this->assign("list",$list['list']);
        $this->assign("count",$list['count']);
        $this->assign("page",$page_obj->show());
        $this->assign("dispose",$dispose);
        $this->display();
    }

    /**
     * 商品列表参数判断
     * @return array $result
     */
    private function disposePostParam(){
        $result = [];
        $result['where'] = [];
        $result['page'] = 1;

        $post_info = [];
        $post_info = I("post.");

        //页码
        if(!empty($post_info['search_now_page'])){
            $result['page'] = $post_info['search_now_page'];
        }

        $where = [];

        //时间搜索类型
        $post_info['search_start_time'] = check_str($post_info['search_start_time']);
        $post_info['search_end_time'] = check_str($post_info['search_end_time']);
        $post_info['search_time_type'] = check_int($post_info['search_time_type']);
        if(!empty($post_info['search_start_time']) || !empty($post_info['search_end_time'])){
            $start_time = is_date($post_info['search_start_time'])?strtotime($post_info['search_start_time']):0;
            $end_time = is_date($post_info['search_end_time'])?strtotime(date("Y-m-d 23:59:59",strtotime($post_info['search_end_time']))):0;
            switch($post_info['search_time_type']){
                case 1: //下单时间
                    if(!empty($start_time)){
                        $where['orders.inputtime'][] = ["egt",$start_time];
                        $result['search_start_time'] = $post_info['search_start_time'];
                    }
                    if(!empty($end_time)){
                        $where['orders.inputtime'][] = ["elt",$end_time];
                        $result['search_end_time'] = $post_info['search_end_time'];
                    }
                    break;
                case 2: //确认订单时间
                    if(!empty($start_time)){
                        $where['orders.confirm_time'][] = ["egt",$start_time];
                        $result['search_start_time'] = $post_info['search_start_time'];
                    }
                    if(!empty($end_time)){
                        $where['orders.confirm_time'][] = ["elt",$end_time];
                        $where['orders.confirm_time'][] = ["gt",0];
                        $result['search_end_time'] = $post_info['search_end_time'];
                    }
                    break;
                case 3: //确认付款时间
                    if(!empty($start_time)){
                        $where['orders.confirm_pay_time'][] = ["egt",$start_time];
                        $result['search_start_time'] = $post_info['search_start_time'];
                    }
                    if(!empty($end_time)){
                        $where['orders.confirm_pay_time'][] = ["elt",$end_time];
                        $where['orders.confirm_pay_time'][] = ["gt",0];
                        $result['search_end_time'] = $post_info['search_end_time'];
                    }
                    break;
            }
            $result['search_time_type'] = $post_info['search_time_type'];
        }

        //字段类型搜索
        $post_info['search_info'] = check_str($post_info['search_info']);
        $post_info['search_info_type'] = check_int($post_info['search_info_type']);
        if(!empty($post_info['search_info'])){
            switch($post_info['search_info_type']){
                case 1: //订单id
                    $post_info['search_info'] = check_int($post_info['search_info']);
                    $where['orders.id'] = $post_info['search_info'];
                    break;
                case 2: //订单序列号
                    $where['orders.order_code'] = ['like',"%".$post_info['search_info']."%"];
                    break;
                case 3: //用户手机号
                    $where['user.mobile'] = ['like',"%".$post_info['search_info']."%"];
                    break;
                case 4: //配送详细地址
                    $where['orders.send_address'] = ['like',"%".$post_info['search_info']."%"];
                    break;
                case 5: //用户确认手机号
                    $where['orders.confirm_mobile'] = ['like',"%".$post_info['search_info']."%"];
                    break;
                case 6: //备注信息
                    $where['orders.remark'] = ['like',"%".$post_info['search_info']."%"];
                    break;
            }
            $result['search_info_type'] = $post_info['search_info_type'];
            $result['search_info'] = $post_info['search_info'];
        }

        //订单状态搜索
        $post_info['search_order_state'] = check_int($post_info['search_order_state']);
        if(!empty($post_info['search_order_state'])){
            $where['orders.state'] = $post_info['search_order_state'];
            $result['search_order_state'] = $post_info['search_order_state'];
        }

        //是否已被确认
        $result['search_is_confirm'] = -1;
        if(isset($post_info['search_is_confirm'])){
            $post_info['search_is_confirm'] = check_int($post_info['search_is_confirm']);
            if($post_info['search_is_confirm'] == 0){
                $where['orders.is_confirm'] = 0;
            }elseif($post_info['search_is_confirm'] == 1){
                $where['orders.is_confirm'] = 1;
            }
            $result['search_is_confirm'] = $post_info['search_is_confirm'];
        }

        //是否已确认付款
        $result['search_is_pay'] = -1;
        if(isset($post_info['search_is_pay'])){
            $post_info['search_is_pay'] = check_int($post_info['search_is_pay']);
            if($post_info['search_is_pay'] == 0){
                $where['orders.is_pay'] = 0;
            }elseif($post_info['search_is_pay'] == 1){
                $where['orders.is_pay'] = 1;
            }
            $result['search_is_pay'] = $post_info['search_is_pay'];
        }

        //配送周
        $post_info['search_send_week'] = check_int($post_info['search_send_week']);
        if(!empty($post_info['search_send_week'])){
            $where['orders.send_week'] = $post_info['search_send_week'];
            $result['search_send_week'] = $post_info['search_send_week'];
        }

        //配送时间段
        $post_info['search_send_time'] = check_str($post_info['search_send_time']);
        if(!empty($post_info['search_send_time'])){
            $where['orders.send_time'] = $post_info['search_send_time'];
            $result['search_send_time'] = $post_info['search_send_time'];
        }

        //被删除的订单
        $post_info['search_is_delete'] = check_int($post_info['search_is_delete']);
        if(!empty($post_info['search_is_delete'])){
            $where['orders.is_delete'] = 1;
            $result['search_is_delete'] = 1;
        }

        $result['where'] = $where;

        return $result;
    }

    /**
     * 订单详情
     * @param int $order_id 订单id
     */
    public function orderInfo($order_id = 0){
        $order_info = [];
        $order_info = $this->order_model->getOrderInfo($order_id);
        if(!empty($order_info['order_info']['id'])){

            //确认列表
            $is_confirm_list = [
                "0" => [
                    "str" => "否",
                    "class" => "red_color",
                ],
                "1" => [
                    "str" => "是",
                    "class" => "green_color",
                ]
            ];
            //付款列表
            $is_pay_list = [
                "0" => [
                    "str" => "否",
                    "class" => "red_color",
                ],
                "1" => [
                    "str" => "是",
                    "class" => "green_color",
                ]
            ];
            //确认与付款的可操作状态
            $can_confirm = $can_pay = 1;
            if($order_info['order_info']['is_delete'] == 1){
                //订单已被删除两个都不可修改
                $can_confirm = $can_pay = 0;
            }else{
                //这些状态无法再操作
                $state_list = [
                    C("STATE_ORDER_CLOSE"),  //已关闭
                    C("STATE_ORDER_DISSENT"), //有异议
                    C("STATE_ORDER_BACK"), //已退款
                ];
                if(in_array($order_info['order_info']['state'],$state_list)){
                    $can_confirm = $can_pay = 0;
                }
            }
            //关闭与退课操作
            $close_list = [
                C("STATE_ORDER_WAIT_CONFIRM"), //待确认
                C("STATE_ORDER_WAIT_DELIVERY"), //待发货
                C("STATE_ORDER_DELIVERY_ING"), //配送中
                C("STATE_ORDER_WAIT_SETTLEMENT"), //待结算
                C("STATE_ORDER_DISSENT"), //有异议
            ];
            $can_close = $can_refund = 0;
            //状态在关闭列表中 且 未确认付款的订单
            if(in_array($order_info['order_info']['state'],$close_list) && $order_info['order_info']['is_pay'] != 1){
                $can_close = 1;
            }
            if($order_info['order_info']['is_pay'] == 1 && $order_info['order_info']['state'] != C("STATE_ORDER_BACK")){
                $can_refund = 1;
            }

            $this->assign("order_info",$order_info);
            $this->assign("is_confirm_list",$is_confirm_list);
            $this->assign("is_pay_list",$is_pay_list);
            $this->assign("can_confirm",$can_confirm);
            $this->assign("can_pay",$can_pay);
            $this->assign("can_close",$can_close);
            $this->assign("can_refund",$can_refund);
            $this->display();
        }else{
            $this->error("未能获取订单信息");
        }
    }

    /**
     * 订单统一操作orderUnifiedOperation
     */
    public function orderUnifiedOperation(){
        $list = [];
        $list = $this->order_model->getWeekAllOrderStatistics();

        $date = get_week_time();
        $start_time = $end_time = 0;
        if(!empty($date)){
            $start_time = $date['start_time'];
            $end_time = $date['end_time'];
        }

        $this->assign("list",$list);
        $this->assign("start_time",$start_time);
        $this->assign("end_time",$end_time);
        $this->display();
    }

}