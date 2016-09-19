<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台统计控制器
 *
 * 相关方法：
 * attrStatisticsList       属性统计列表
 * getAttrStatisticsHtml    属性树状图生成
 * orderStatisticsList      订单统计列表
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

        //用 $tree 与 $statistics 组装出html
        $html = $this->getAttrStatisticsHtml($tree,$statistics);

        $this->assign("tree",$tree);
        $this->assign("statistics",$statistics);
        $this->assign("html",$html);
        $this->display();

    }

    /**
     * 树状图生成
     * @param array $tree 树状图列表
     * @param array $statistics 统计数据列表
     * @return string $html 数据返回
     */
    private function getAttrStatisticsHtml($tree = [],$statistics = []){
        $html = "";

        if(!empty($tree) && !empty($statistics)){
            $html .= "<table>";
            foreach($tree as $key => $val){
                $html .= "<tr><td>".$val['attr_name']."<br>商品数量：".(empty($statistics[$key]['goods_num'])?0:$statistics[$key]['goods_num']).(empty($statistics[$key]['statistics_time'])?'':'<br>统计于 '.date("Y-m-d H:i:s",$statistics[$key]['statistics_time']))."</td>";
                $html .= "<td>".(empty($val['child'])?"":$this->getAttrStatisticsHtml($val['child'],$statistics))."</td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
        }

        return $html;
    }

    /**
     * 订单统计列表
     * @param int $level 搜索级别 1 年列表 、2 月列表 、3 日列表
     * @param string $time 时间搜索值 根据 $level 参数变化
     */
    public function orderStatisticsList($level = 1,$time = ""){
        $level = check_int($level);
        $time = check_str($time);

        $this->assign("statistics_level",$level);
        $this->assign("statistics_time",$time);
        $this->display();
    }

}