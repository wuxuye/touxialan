<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台统计控制器
 *
 * 相关方法：
 *
 */

class StatisticsController extends PublicController {

    public function _initialize(){
        parent::_initialize();
    }

    /**
     * 属性统计列表
     */
    public function attrStatisticsList(){
        //拿到树状图
        $attr_obj = new \Yege\Attr();
        $tree = $attr_obj->getAttrTree();

        //拿到统计
        $statistics = [];
        $statistics = D("Statistics")->getAttrStatistics();

        $this->assign("tree",$tree);
        $this->assign("statistics",$statistics);
        $this->display();

    }

}