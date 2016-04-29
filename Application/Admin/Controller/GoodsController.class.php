<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台商品控制器
 *
 * 相关方法
 * goodsList     商品列表
 * addGoods      添加商品方法
 * editGoods     编辑商品方法
 * deleteGoods   删除商品
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

        $this->assign("list",$list['list']);
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

        $this->search_now_page = 1;
        if(!empty($post_info['search_now_page'])){
            $this->search_now_page = $post_info['search_now_page'];
            $result['page'] = $this->search_now_page;
        }

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
            $goods_obj->goods_describe = $post_info['goods_describe'];
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

        if(!empty($info['result']['goods_id'])){

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
                $goods_obj->goods_describe = $post_info['goods_describe'];
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

                    $this->success("编辑成功");
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

}