<?php

namespace Home\Controller;
use Think\Controller;

class NoticeController extends PublicController {

    public function _initialize(){
        parent::_initialize();
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