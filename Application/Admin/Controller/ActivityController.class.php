<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台活动公共控制器
 */

class ActivityController extends PublicController {

    public function _initialize(){
        parent::_initialize();
    }

    public function activityList(){
        $dispose = [];
        $dispose = $this->disposePostParam();

        //单页数量
        $page_num = C("ADMIN_ACTIVITY_LIST_PAGE_SHOW_NUM");

        $activity_obj = new \Yege\Activity();
        $list = [];
        $list = $activity_obj->getActivityList($dispose['where'],$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list['list']) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $activity_obj->getActivityList($dispose['where'],$dispose['page'],$page_num);
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

        //开始时间搜索
        $post_info['search_start_time'] = check_str($post_info['search_start_time']);
        $post_info['search_end_time'] = check_str($post_info['search_end_time']);
        if(!empty($post_info['search_start_time']) || !empty($post_info['search_end_time'])){
            $start_time = is_date($post_info['search_start_time'])?strtotime($post_info['search_start_time']):0;
            $end_time = is_date($post_info['search_end_time'])?strtotime(date("Y-m-d 23:59:59",strtotime($post_info['search_end_time']))):0;
            if(!empty($start_time)){
                $where['start_time'][] = ["egt",$start_time];
                $result['search_start_time'] = $post_info['search_start_time'];
            }
            if(!empty($end_time)){
                $where['start_time'][] = ["elt",$end_time];
                $result['search_end_time'] = $post_info['search_end_time'];
            }
        }

        //标题搜索
        $post_info['search_title'] = check_str($post_info['search_title']);
        if(!empty($post_info['search_title'])){
            $where['title'] = ['like',"%".$post_info['search_title']."%"];
            $result['search_title'] = $post_info['search_title'];
        }

        $result['where'] = $where;

        return $result;
    }

    /**
     * 添加活动
     */
    public function addActivity(){

        if(IS_POST){
            $post_info = I("post.");
            if(!empty($post_info['activity_title']) && !empty($post_info['activity_url'])){
                $Activity = new \Yege\Activity();
                $Activity->activity_title = $post_info['activity_title'];
                $Activity->activity_url = $post_info['activity_url'];
                $Activity->activity_start_time = $post_info['activity_start_time'];
                $Activity->activity_end_time = $post_info['activity_end_time'];
                $result = $Activity->addActivity();

                if($result['state'] == 1){
                    //添加成功回到列表
                    redirect('/Admin/Activity/activityList');
                }else{
                    $this->error("添加失败：".$result['message']);
                }
            }else{
                $this->error("请正确填写活动标题与URL");
            }
        }else{
            $this->display();
        }

    }

    /**
     * 编辑活动
     * @param int $id 活动id
     */
    public function editActivity($id = 0){
        //首先获取详情
        $id = check_int($id);
        $Activity = new \Yege\Activity();
        $Activity->activity_id = $id;
        $activity_info = $Activity->getInfo();
        if($activity_info['state'] == 1){

            if(IS_POST){
                $post_info = I("post.");
                if(!empty($post_info['activity_title']) && !empty($post_info['activity_url'])){
                    $Activity->activity_title = $post_info['activity_title'];
                    $Activity->activity_url = $post_info['activity_url'];
                    $Activity->activity_start_time = $post_info['activity_start_time'];
                    $Activity->activity_end_time = $post_info['activity_end_time'];
                    $result = $Activity->editActivity();

                    if($result['state'] == 1){
                        //保存成功获取一边新数据
                        $activity_info = $Activity->getInfo();
                    }else{
                        $this->error("添加失败：".$result['message']);
                    }
                }else{
                    $this->error("请正确填写活动标题与URL");
                }
            }

            $activity_info = $activity_info['result'];
            $this->assign("activity_info",$activity_info);
            $this->display();

        }else{
            $this->error("基础信息获取失败：".$activity_info['message']);
        }
    }

}