<?php

namespace Home\Controller;
use Think\Controller;

class IndexController extends PublicController {

    public function index(){

        //P($_SESSION);
        $this->display();
    }

}