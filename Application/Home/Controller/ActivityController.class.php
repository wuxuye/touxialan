<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 前台活动公共控制器
 */

class ActivityController extends PublicController {

    public function _initialize(){
        parent::_initialize();
    }

    /**
     * 公告列表
     */
    public function activityList(){
        $data = $this->activityListDisposeData();

        $this->home_head_left_title = "活动列表";
        $this->hidden_nav = 1;

        $Activity = new \Yege\Activity();
        $activity_list = $Activity->getActivityList($data['where'],$data['page'],C("HOME_ACTIVITY_LIST_MAX_SHOW_NUM"));

        //分页
        $page_obj = new \Yege\IndexPage($activity_list['count'],C("HOME_ACTIVITY_LIST_MAX_SHOW_NUM"));

        $this->assign("activity_list",$activity_list['list']);
        $this->assign("get_data",$data['data']);
        $this->assign("page",$page_obj->show());
        $this->display();
    }

    /**
     * 公告列表参数处理
     * $return array $data 数据返回
     */
    private function activityListDisposeData(){
        $data = ['where'=>[],'data'=>[],'page'=>1];

        $get_info = I("get.");

        //页码
        if(!empty($get_info['page'])){
            $data['page'] = check_int($get_info['page']);
        }
        $data['page'] = $data['page'] > 0 ? $data['page'] : 1;

        return $data;
    }

}