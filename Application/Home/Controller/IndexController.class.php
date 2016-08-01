<?php

namespace Home\Controller;
use Think\Controller;

class IndexController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->nav_param = 'index';
    }

    public function index(){

        //P($_SESSION);
        $this->display();
    }

}