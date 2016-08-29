<?php

namespace Home\Controller;
use Think\Controller;

class IndexController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->nav_param = 'index';
    }

    public function index(){

        //����ʱ���ȡ
        $work_list = get_work_time();

        $this->assign("work_list",$work_list);
        $this->display();
    }

}