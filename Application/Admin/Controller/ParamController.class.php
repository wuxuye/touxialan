<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台全站基础参数配置控制器
 *
 * 相关方法
 */

class ParamController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->param_model = D("Param");
    }

    /**
     * 操作项列表
     */
    public function operationList(){
        //操作列表
        $operation_list = [
            "indexWebConfig" => "基础参数 -- 基础配置",
            "goodsListShowAttr" => "商品列表 -- 展示属性",
        ];

        $this->assign("operation_list",$operation_list);
        $this->display();
    }

}