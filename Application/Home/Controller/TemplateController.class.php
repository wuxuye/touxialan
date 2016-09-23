<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 前台订单控制器
 *
 * 相关方法
 * orderInfo        订单详情页
 */

class TemplateController extends PublicController {

    public function _initialize(){
        parent::_initialize();
    }

    /**
     * 错误页面
     */
    public function errorShow(){
        $this->error();
    }

    /**
     * 正确页面
     */
    public function successShow(){
        $this->success();
    }

}