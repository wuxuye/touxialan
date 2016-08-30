<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台订单控制器
 *
 * 相关方法
 * orderList        商品列表
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
        $dispose = array();
        $dispose = $this->disposePostParam();

        //单页数量
        $page_num = C("ADMIN_ORDER_LIST_PAGE_SHOW_NUM");

        $list = array();
        $list = $this->order_model->getOrderList($dispose['where'],$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list['list']) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $this->order_model->getOrderList($dispose['where'],$dispose['page'],$page_num);
        }

        //分页
        $page_obj = new \Yege\Page($dispose['page'],$list['count'],$page_num);

        $this->assign("search_time_type_list",C("ADMIN_ORDER_LIST_SEARCH_TIME_TYPE_LIST"));
        $this->assign("search_info_type_list",C("ADMIN_ORDER_LIST_SEARCH_INFO_TYPE_LIST"));
        $this->assign("search_order_state_list",C("STATE_ORDER_LIST"));
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
        $post_info['search_start_time'] = check_str($post_info['search_start_time']);
        $post_info['search_end_time'] = check_str($post_info['search_end_time']);
        $post_info['search_time_type'] = check_int($post_info['search_time_type']);
        if(!empty($post_info['search_start_time']) || !empty($post_info['search_end_time'])){
            $start_time = is_date($post_info['search_start_time'])?strtotime($post_info['search_start_time']):0;
            $end_time = is_date($post_info['search_end_time'])?strtotime(date("Y-m-d 23:59:59",strtotime($post_info['search_end_time']))):0;
            switch($post_info['search_time_type']){
                case 1: //商品添加时间
                    if(!empty($start_time)){
                        $where['goods.inputtime'][] = array("egt",$start_time);
                        $result['search_start_time'] = $post_info['search_start_time'];
                    }
                    if(!empty($end_time)){
                        $where['goods.inputtime'][] = array("elt",$end_time);
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
                case 1: //商品id
                    $post_info['search_info'] = check_int($post_info['search_info']);
                    $where['goods.id'] = $post_info['search_info'];
                    break;
                case 2: //商品归属(手机)
                    $where['user.mobile'] = array('like',"%".$post_info['search_info']."%");
                    break;
            }
            $result['search_info_type'] = $post_info['search_info_type'];
            $result['search_info'] = $post_info['search_info'];
        }

        //商品名称搜索
        $post_info['search_goods_name'] = check_str($post_info['search_goods_name']);
        if(!empty($post_info['search_goods_name'])){
            $where['goods.name'] = array('like',"%".$post_info['search_goods_name']."%");
            $result['search_goods_name'] = $post_info['search_goods_name'];
        }

        //商品扩展名搜索
        $post_info['search_ext_name'] = check_str($post_info['search_ext_name']);
        if(!empty($post_info['search_ext_name'])){
            $where['goods.ext_name'] = array('like',"%".$post_info['search_ext_name']."%");
            $result['search_ext_name'] = $post_info['search_ext_name'];
        }

        //商品上架状态搜索
        $post_info['search_is_shop'] = check_int($post_info['search_is_shop']);
        if(!empty($post_info['search_is_shop'])){
            if($post_info['search_is_shop'] == 1){ //上架中
                $where['goods.is_shop'] = 1;
            }elseif($post_info['search_is_shop'] == 2){ //未上架
                $where['goods.is_shop'] = 0;
            }
            $result['search_is_shop'] = $post_info['search_is_shop'];
        }

        //商品推荐状态
        $result['search_is_recommend'] = -1;
        if(isset($post_info['search_is_recommend'])){
            $post_info['search_is_recommend'] = check_int($post_info['search_is_recommend']);
            if($post_info['search_is_recommend'] == 0){
                $where['goods.is_recommend'] = 0;
            }elseif($post_info['search_is_recommend'] == 1){
                $where['goods.is_recommend'] = 1;
            }
            $result['search_is_recommend'] = $post_info['search_is_recommend'];
        }

        //属性搜索
        $post_info['search_attr'] = check_int($post_info['search_attr']);
        if(!empty($post_info['search_attr'])){
            //先获取属性集合
            $Attr = new \Yege\Attr();
            $attr_id_list = [];
            $attr_list = $Attr->getChildList($post_info['search_attr']);
            foreach($attr_list as $key => $val){
                $attr_id_list[] = $val['attr_id'];
            }
            //带上自己
            if(!in_array($post_info['search_attr'],$attr_id_list)){
                $attr_id_list[] = $post_info['search_attr'];
            }

            $where['goods.attr_id'] = ['in',$attr_id_list];
            $result['search_attr'] = $post_info['search_attr'];
        }

        $result['where'] = $where;

        return $result;
    }


}