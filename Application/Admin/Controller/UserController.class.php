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
     * 添加用户
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