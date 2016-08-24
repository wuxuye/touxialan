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
        $is_shop = check_shop_time();
        $work_list = get_work_time();

        $this->assign("is_shop",$is_shop);
        $this->assign("work_list",$work_list);
        $this->display();
    }

}