<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 前台商品控制器
 *
 * 相关方法
 * goodsList                    商品列表展示
 * allGoodsList                 全商品列表页
 * allGoodsListDisposeData      全商品列表参数处理
 * goodsInfo                    商品详情页
 */

class GoodsController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->goods_model = D("Goods");
        $this->nav_param = 'list';
    }

    /**
     * 商品列表展示
     */
    public function goodsList(){

        $list = [];
        $list = $this->goods_model->getColumnList();
        if($list['state'] == 1){
            //逐个栏目获取商品
            $goods_list = [];
            if(!empty($list['data'])){
                $Attr = new \Yege\Attr();
                foreach($list['data'] as $key => $val){
                    //获取属性列表
                    $temp_list = $where = $attr_id_list = $attr_id_list_temp = [];
                    $attr_id_list_temp = $Attr->getChildList($val['attr_id']);
                    if(!empty($attr_id_list_temp)){
                        foreach($attr_id_list_temp as $attr_info){
                            $attr_id_list[] = $attr_info['attr_id'];
                        }
                        $attr_id_list[] = $val['attr_id'];
                        $where['attr.id'] = ['in',$attr_id_list];
                        $temp_list = $this->goods_model->getGoodsList($where,1,C("HOME_GOODS_LIST_ONE_COLUMN_MAX_GOODS_NUM"));
                        if($temp_list['state'] == 1){
                            $goods_list[$val['attr_id']] = $temp_list['list'];
                        }
                    }
                }
            }
            $this->assign("list",$list['data']);
            $this->assign("goods_list",$goods_list);
            $this->display();
        }else{
            $this->error($list['message']);
        }

    }

    /**
     * 全商品列表页
     */
    public function allGoodsList(){
        $data = $this->allGoodsListDisposeData();

        $goods_list = $this->goods_model->getGoodsList($data['where'],$data['page'],C("HOME_ALL_GOODS_LIST_MAX_GOODS_NUM"));

        //分页
        $page_obj = new \Yege\IndexPage($goods_list['count'],C("HOME_ALL_GOODS_LIST_MAX_GOODS_NUM"));

        $this->assign("goods_list",$goods_list['list']);
        $this->assign("get_data",$data['data']);
        $this->assign("page",$page_obj->show());
        $this->display();
    }

    /**
     * 全商品列表参数处理
     * $return array $data 数据返回
     */
    private function allGoodsListDisposeData(){
        $data = ['where'=>[],'data'=>[],'page'=>1];

        $get_info = I("get.");

        //页码
        if(!empty($get_info['page'])){
            $data['page'] = check_int($get_info['page']);
        }
        $data['page'] = $data['page'] > 0 ? $data['page'] : 1;

        $where = [];
        //搜索关键词
        $data['data']['search_key'] = "";
        if(!empty($get_info['search_key'])){
            $get_info['search_key'] = mb_substr($get_info['search_key'],0,C("HOME_GOODS_MAX_SEARCH_NUM"),"utf-8");
            $data['data']['search_key'] = check_str($get_info['search_key']);

            //商品名
            $where[1][]['goods.name'] = ['like','%'.$get_info['search_key'].'%'];
            //商品扩展名
            $where[1][]['goods.ext_name'] = ['like','%'.$get_info['search_key'].'%'];

            //分词
            $participle = D("Keyword")->saveKeyword($get_info['search_key']);
            if(!empty($participle['keyword'])){
                $data['data']['search_key'] = $participle['keyword'];
                foreach($participle['keyword_list'] as $val){
                    $where[1][]['goods.name'] = ['like','%'.$val.'%'];
                    $where[1][]['goods.ext_name'] = ['like','%'.$val.'%'];
                }
            }

            $where[1]['_logic'] = 'or';
            //标签
            $tag = [];
            $tag_temp = M(C('TABLE_NAME_GOODS_TAG_RELATE')." as tag_relate")
                    ->field("tag_relate.goods_id,tags.tag_name")
                    ->join("left join ".C("DB_PREFIX").C('TABLE_NAME_TAGS')." as tags on tag_relate.tag_id = tags.id")
                    ->where([
                        "tags.tag_name" => ['like','%'.$get_info['search_key'].'%'],
                        "tags.state" => C("STATE_TAGS_NORMAL"),
                    ])->select();
            foreach($tag_temp as $val){
                if(!empty($val['goods_id']) && !in_array($val['goods_id'],$tag)){
                    $tag[] = $val['goods_id'];
                }
            }
            if(!empty($tag)){
                $where[1][]['goods.id'] = ['in',$tag];
            }

        }
        $data['where'] = $where;

        return $data;
    }

    /**
     * 商品详情页
     * @param int $goods_id 商品id
     */
//    public function goodsInfo($goods_id = 0){
//
//    }

}