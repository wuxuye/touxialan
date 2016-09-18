<?php

namespace Home\Controller;
use Think\Controller;

class NoticeController extends PublicController {

    public function _initialize(){
        parent::_initialize();
    }

    /**
     * 公告列表
     */
    public function noticeList(){
        $data = $this->noticeListDisposeData();

        $Notice = new \Yege\Notice();
        $notice_list = $Notice->getNoticeList($data['where'],$data['page'],C("HOME_NOTICE_LIST_MAX_SHOW_NUM"));

        //分页
        $page_obj = new \Yege\IndexPage($notice_list['count'],C("HOME_NOTICE_LIST_MAX_SHOW_NUM"));

        $this->assign("notice_list",$notice_list['list']);
        $this->assign("get_data",$data['data']);
        $this->assign("page",$page_obj->show());
        $this->display();
    }

    /**
     * 公告列表参数处理
     * $return array $data 数据返回
     */
    private function noticeListDisposeData(){
        $data = ['where'=>[],'data'=>[],'page'=>1];

        $get_info = I("get.");

        //页码
        if(!empty($get_info['page'])){
            $data['page'] = check_int($get_info['page']);
        }
        $data['page'] = $data['page'] > 0 ? $data['page'] : 1;

        return $data;
    }

    /**
     * 显示公告详情
     */
    public function show($id = 0){
        $id = check_int($id);
        $Notice = new \Yege\Notice();
        $Notice->notice_id = $id;
        $info = $Notice->getInfo();

        $this->home_head_left_title = "公告详情";
        $this->hidden_nav = 1;

        if($info['state'] == 1){
            $this->assign("info",$info['result']);
            $this->display();
        }else{
            $this->error($info['message']);
        }
    }

}