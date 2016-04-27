<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台导航控制器
 *
 * 相关方法
 * index     导航列表
 */

class IndexController extends PublicController {

    public function _initialize(){
        parent::_initialize();
    }

    /**
     * 导航列表
     */
    public function index(){
        $this->display();
    }

}