<?php

namespace Home\Controller;
use Think\Controller;

class IndexController extends PublicController {

    public function index(){

        add_wrong_log("ssss\r\n");

        $this->display();
    }

}