<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台资金控制器
 *
 * 相关方法
 * fundList        资金列表
 */

class FundController extends PublicController {

    public function _initialize(){
        parent::_initialize();
    }

    public function fundList(){
        $dispose = [];
        $dispose = $this->disposePostParam();

        //单页数量
        $page_num = C("ADMIN_FUND_LIST_PAGE_SHOW_NUM");

        $notice_obj = new \Yege\Notice();
        $list = [];
        $list = $notice_obj->getNoticeList($dispose['where'],$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list['list']) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $notice_obj->getNoticeList($dispose['where'],$dispose['page'],$page_num);
        }

        //分页
        $page_obj = new \Yege\Page($dispose['page'],$list['count'],$page_num);

        $this->assign("list",$list['list']);
        $this->assign("count",$list['count']);
        $this->assign("page",$page_obj->show());
        $this->assign("dispose",$dispose);
        $this->assign("search_time_type_list",C("ADMIN_NOTICE_LIST_SEARCH_TIME_TYPE_LIST"));
        $this->assign("search_info_type_list",C("ADMIN_NOTICE_LIST_SEARCH_INFO_TYPE_LIST"));
        $this->display();
    }

    /**
     * 公告列表参数判断
     * @return array $result
     */
    private function disposePostParam(){
        $result = ["where"=>[],"page"=>1];

        $post_info = [];
        $post_info = I("post.");

        $this->search_now_page = 1;
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
                case 1: //发布时间
                    if(!empty($start_time)){
                        $where['inputtime'][] = ["egt",$start_time];
                        $result['search_start_time'] = $post_info['search_start_time'];
                    }
                    if(!empty($end_time)){
                        $where['inputtime'][] = ["elt",$end_time];
                        $result['search_end_time'] = $post_info['search_end_time'];
                    }
                    break;
                case 2: //最后更新时间
                    if(!empty($start_time)){
                        $where['updatetime'][] = ["egt",$start_time];
                        $result['search_start_time'] = $post_info['search_start_time'];
                    }
                    if(!empty($end_time)){
                        $where['updatetime'][] = ["elt",$end_time];
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
                case 1: //标题搜索
                    $where['title'] = ['like',"%".$post_info['search_info']."%"];
                    break;
//                case 2: //内容搜索
//                    $where['message'] = ['like',"%".$post_info['search_info']."%"];
//                    break;
            }
            $result['search_info_type'] = $post_info['search_info_type'];
            $result['search_info'] = $post_info['search_info'];
        }

        $result['where'] = $where;

        return $result;
    }

    /**
     * 添加公告
     */
    public function addNotice(){

        if(IS_POST){
            $post_info = I("post.");
            $post_info['notice_remark'] = I("post.notice_remark","",false);
            if(!empty($post_info['notice_title']) && !empty($post_info['notice_remark'])){

                $admin_user_info = get_login_user_info();

                $Notice = new \Yege\Notice();
                $Notice->title = check_str($post_info['notice_title']);
                $Notice->message = $post_info['notice_remark'];
                $Notice->author = $admin_user_info['id'];
                $result = $Notice->addNotice();

                if($result['state'] == 1){
                    //添加成功回到列表
                    redirect('/Admin/Article/noticeList');
                }else{
                    $this->error("添加失败：".$result['message']);
                }
            }else{
                $this->error("请正确填写公告标题与内容");
            }
        }else{
            $this->display();
        }

    }

    /**
     * 编辑公告
     * @param int $id 公告id
     */
    public function editNotice($id = 0){
        //首先获取详情
        $id = check_int($id);
        $Notice = new \Yege\Notice();
        $Notice->notice_id = $id;
        $notice_info = $Notice->getInfo();
        if($notice_info['state'] == 1){

            if(IS_POST){
                $post_info = I("post.");
                $post_info['notice_remark'] = I("post.notice_remark","",false);
                if(!empty($post_info['notice_title']) && !empty($post_info['notice_remark'])){

                    $admin_user_info = get_login_user_info();
                    $Notice->title = check_str($post_info['notice_title']);
                    $Notice->message = $post_info['notice_remark'];
                    $Notice->author = $admin_user_info['id'];
                    $result = $Notice->editNotice();

                    if($result['state'] == 1){
                        //保存成功获取一边新数据
                        $notice_info = $Notice->getInfo();
                    }else{
                        $this->error("添加失败：".$result['message']);
                    }
                }else{
                    $this->error("请正确填写公告标题与内容");
                }
            }

            $notice_info = $notice_info['result'];
            $this->assign("notice_info",$notice_info);
            $this->display();

        }else{
            $this->error("基础信息获取失败：".$notice_info['message']);
        }
    }


}