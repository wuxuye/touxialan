<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台商品控制器
 *
 * 相关方法
 * goodsList                    商品列表
 * disposePostParam（私有）      商品列表参数判断
 * addGoods                     添加商品方法
 * editGoods                    编辑商品方法
 * editGoodsStock               商品库存修改
 */

class GoodsController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->goods_model = D("Goods");
        $this->tags_model = D("Tags");
    }

    /**
     * 商品列表
     */
    public function goodsList(){
        $dispose = array();
        $dispose = $this->disposePostParam();

        //单页数量
        $page_num = C("ADMIN_GOODS_LIST_PAGE_SHOW_NUM");

        $list = array();
        $list = $this->goods_model->getGoodsList($dispose['where'],$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list['list']) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $this->goods_model->getGoodsList($dispose['where'],$dispose['page'],$page_num);
        }

        //分页
        $page_obj = new \Yege\Page($dispose['page'],$list['count'],$page_num);

        $Param = new \Yege\Param();
        $statistics_last_time = $Param->getDataByParam("goodsSaleStatisticsLastTime");
        $last_time = empty($statistics_last_time['data']) ? 0 : $statistics_last_time['data'];

        $this->assign("search_time_type_list",C("ADMIN_GOODS_LIST_SEARCH_TIME_TYPE_LIST"));
        $this->assign("search_info_type_list",C("ADMIN_GOODS_LIST_SEARCH_INFO_TYPE_LIST"));
        $this->assign("list",$list['list']);
        $this->assign("count",$list['count']);
        $this->assign("page",$page_obj->show());
        $this->assign("dispose",$dispose);
        $this->assign("last_time",$last_time);
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

    /**
     * 添加商品
     */
    public function addGoods(){

        $tags_list = array();
        $tags_obj = new \Yege\Tag();
        $tags_list = $tags_obj->getTagsList();

        if(IS_POST){
            $post_info = I("post.");

            //图片判断
            $image_temp = array();
            if(!empty($_FILES['goods_image']['name'])){
                $image_obj = new \Yege\Image();
                $image_temp = $image_obj->upload_image($_FILES['goods_image']);
            }

            $goods_obj = new \Yege\Goods();
            $goods_obj->goods_belong_id = $post_info['goods_belong'];
            $goods_obj->goods_name = $post_info['goods_name'];
            $goods_obj->goods_ext_name = $post_info['goods_ext'];
            $goods_obj->goods_attr_id = $post_info['goods_attr_id'];
            $goods_obj->goods_price = $post_info['goods_price'];
            $goods_obj->goods_point = $post_info['goods_point'];
            $goods_obj->goods_can_price = $post_info['goods_can_price'];
            $goods_obj->goods_can_point = $post_info['goods_can_point'];
            $goods_obj->goods_describe = $post_info['goods_describe'];
            $goods_obj->goods_is_recommend = $post_info['goods_is_recommend'];
            $goods_obj->goods_weight = $post_info['goods_weight'];
            if(!empty($image_temp['url'])){
                $goods_obj->goods_image = $image_temp['url'];
            }

            $goods_result = array();
            $goods_result = $goods_obj->addGoods();

            if($goods_result['state'] == 1){

                //位商品处理标签
                if(!empty($post_info['now_tags_list'])){
                    $post_tags = json_decode($post_info['now_tags_list'],true);
                    if(!empty($post_tags)){
                        $this->tags_model->relateGoods($goods_obj->goods_id,$post_tags);
                    }
                }

                $this->success("添加成功");
            }else{
                $this->error("添加失败：".$goods_result['message']);
            }

        }else{
            $this->assign('tags_list',$tags_list);
            $this->display();
        }

    }

    /**
     * 编辑商品
     * @param int $id 商品id
     */
    public function editGoods($id = 0){

        //获取商品信息
        $info = array();
        $goods_obj = new \Yege\Goods();
        $goods_obj->goods_id = $id;
        $info = $goods_obj->getGoodsInfo();

        if(!empty($info['result']['id'])){

            $tags_list = array();
            $tags_obj = new \Yege\Tag();
            $tags_list = $tags_obj->getTagsList();

            if(IS_POST){
                $post_info = I("post.");

                //图片判断
                $image_temp = array();
                if(!empty($_FILES['goods_image']['name'])){
                    $image_obj = new \Yege\Image();
                    $image_temp = $image_obj->upload_image($_FILES['goods_image']);
                }

                $goods_obj = new \Yege\Goods();
                $goods_obj->goods_id = $id;
                $goods_obj->goods_belong_id = $post_info['goods_belong'];
                $goods_obj->goods_name = $post_info['goods_name'];
                $goods_obj->goods_ext_name = $post_info['goods_ext'];
                $goods_obj->goods_attr_id = $post_info['goods_attr_id'];
                $goods_obj->goods_price = $post_info['goods_price'];
                $goods_obj->goods_point = $post_info['goods_point'];
                $goods_obj->goods_can_price = $post_info['goods_can_price'];
                $goods_obj->goods_can_point = $post_info['goods_can_point'];
                $goods_obj->goods_describe = $post_info['goods_describe'];
                $goods_obj->goods_is_recommend = $post_info['goods_is_recommend'];
                $goods_obj->goods_weight = $post_info['goods_weight'];
                if(!empty($image_temp['url'])){
                    $goods_obj->goods_image = $image_temp['url'];
                }

                $goods_result = array();
                $goods_result = $goods_obj->editGoods();
                if($goods_result['state'] == 1){

                    //位商品处理标签
                    if(!empty($post_info['now_tags_list'])){
                        $post_tags = json_decode($post_info['now_tags_list'],true);
                        if(!empty($post_tags)){
                            $this->tags_model->relateGoods($goods_obj->goods_id,$post_tags);
                        }
                    }

                    $this->success("编辑成功");exit;
                }else{
                    $this->error("编辑失败：".$goods_result['message']);
                }
            }else{
                $this->assign('tags_list',$tags_list);
                $this->assign("info",$info['result']);
                $this->display();
            }
        }else{
            $this->error("未能获取信息");
        }
    }

    /**
     * 商品库存修改
     * $param int $goods_id 商品库存
     */
    public function editGoodsStock($goods_id = 0){

        $goods_obj = new \Yege\Goods();
        $goods_obj->goods_id = $goods_id;

        //首先获取商品详情
        $goods_info = [];
        $goods_info = $goods_obj->getGoodsInfo();
        if($goods_info['state'] != 1){
            $this->error($goods_info['message']);
        }

        //再获取库存信息
        $stock_info = [];
        $stock_info = $goods_obj->getGoodsStock();

        if(IS_POST){
            $post_info = I("post.");

            $type = check_int($post_info['op_type']);
            $num = check_int($post_info['op_num']);
            $unit = check_str($post_info['op_unit']);

            if($type == 1){
                if(($stock_info['stock_num'] - $num) < 0){
                    $this->error("库存不足");
                }
            }

            $result = [];
            $result = $goods_obj->updateGoodsStock($type,$num,$unit);
            if($result['state'] == 1){
                $this->success("修改成功");
            }else{
                $this->error($result['message']);
            }

        }else{
            $this->assign("goods_info",$goods_info['result']);
            $this->assign("stock_info",$stock_info);
            $this->display();
        }

    }

    /**
     * 商品库存记录
     */
    public function goodsStockLog(){
        $dispose = [];
        $dispose = $this->disposeGoodsStockLogPostParam();

        //单页数量
        $page_num = C("ADMIN_GOODS_STOCK_LOG_LIST_PAGE_SHOW_NUM");

        $Goods = new \Yege\Goods();
        $list = [];
        $list = $Goods->getGoodsStockLogList($dispose['where'],$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list['list']) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $Goods->getGoodsStockLogList($dispose['where'],$dispose['page'],$page_num);
        }

        //分页
        $page_obj = new \Yege\Page($dispose['page'],$list['count'],$page_num);

        $this->assign("list",$list['list']);
        $this->assign("count",$list['count']);
        $this->assign("page",$page_obj->show());
        $this->assign("dispose",$dispose);
        $this->display();
    }

    /**
     * 商品库存记录列表参数判断
     * @return array $result
     */
    private function disposeGoodsStockLogPostParam(){
        $result = ["where"=>[],"page"=>1];

        $post_info = [];
        $post_info = I("post.");

        $this->search_now_page = 1;
        if(!empty($post_info['search_now_page'])){
            $result['page'] = $post_info['search_now_page'];
        }

        $where = [];

        //时间搜索
        $post_info['search_start_time'] = check_str($post_info['search_start_time']);
        $post_info['search_end_time'] = check_str($post_info['search_end_time']);
        if(!empty($post_info['search_start_time']) || !empty($post_info['search_end_time'])){
            $start_time = is_date($post_info['search_start_time'])?strtotime($post_info['search_start_time']):0;
            $end_time = is_date($post_info['search_end_time'])?strtotime(date("Y-m-d 23:59:59",strtotime($post_info['search_end_time']))):0;
            if(!empty($start_time)){
                $where['stock_log.inputtime'][] = ["egt",$start_time];
                $result['search_start_time'] = $post_info['search_start_time'];
            }
            if(!empty($end_time)){
                $where['stock_log.inputtime'][] = ["elt",$end_time];
                $result['search_end_time'] = $post_info['search_end_time'];
            }
        }

        //商品id
        $post_info['search_goods_id'] = check_int($post_info['search_goods_id']);
        if(!empty($post_info['search_goods_id'])){
            $where['goods.id'] = $post_info['search_goods_id'];
            $result['search_goods_id'] = $post_info['search_goods_id'];
        }

        $result['where'] = $where;

        return $result;
    }

}