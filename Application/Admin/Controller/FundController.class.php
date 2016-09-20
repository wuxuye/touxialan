<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台资金控制器
 *
 * 相关方法
 * fundList             资金列表
 * disposePostParam     资金列表参数判断
 * addFund              添加流水
 * withdrawFund         资金提现
 *
 */

class FundController extends PublicController {

    public function _initialize(){
        parent::_initialize();
    }

    public function fundList(){
        $dispose = [];
        $dispose = $this->disposePostParam();

        //单页数量
        $page_num = C("ADMIN_FUND_LIST_PAGE_SHOW_NUM");

        $Fund = new \Yege\Fund();
        $list = [];
        $list = $Fund->getFundList($dispose['where'],$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list['list']) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $Fund->getFundList($dispose['where'],$dispose['page'],$page_num);
        }

        //分页
        $page_obj = new \Yege\Page($dispose['page'],$list['count'],$page_num);

        $this->assign("list",$list['list']);
        $this->assign("count",$list['count']);
        $this->assign("fund",$list['fund']);
        $this->assign("page",$page_obj->show());
        $this->assign("dispose",$dispose);
        $this->assign("fund_type_list",C("TYPE_FUND_LOG_LIST"));
        $this->display();
    }

    /**
     * disposePostParam公告列表参数判断
     * @return array $result
     */
    private function disposePostParam(){
        $result = ["where"=>[],"page"=>1];

        $post_info = [];
        $post_info = I("post.");

        $this->search_now_page = 1;
        if(!empty($post_info['search_now_page'])){
            $result['page'] = $post_info['search_now_page'];
        }

        $where = [];

        //时间搜索
        $post_info['search_start_time'] = check_str($post_info['search_start_time']);
        $post_info['search_end_time'] = check_str($post_info['search_end_time']);
        if(!empty($post_info['search_start_time']) || !empty($post_info['search_end_time'])){
            $start_time = is_date($post_info['search_start_time'])?strtotime($post_info['search_start_time']):0;
            $end_time = is_date($post_info['search_end_time'])?strtotime(date("Y-m-d 23:59:59",strtotime($post_info['search_end_time']))):0;
            if(!empty($start_time)){
                $where['inputtime'][] = ["egt",$start_time];
                $result['search_start_time'] = $post_info['search_start_time'];
            }
            if(!empty($end_time)){
                $where['inputtime'][] = ["elt",$end_time];
                $result['search_end_time'] = $post_info['search_end_time'];
            }
        }

        //资金流动备注搜索
        $post_info['search_remark'] = check_str($post_info['search_remark']);
        if(!empty($post_info['search_remark'])){
            $where['remark'] = ["like","%".$post_info['search_remark']."%"];
            $result['search_remark'] = $post_info['search_remark'];
        }

        //资金流动类型搜索
        $post_info['search_type'] = check_int($post_info['search_type']);
        if(!empty(C('TYPE_FUND_LOG_LIST')[$post_info['search_type']])){
            $where['type'] = $post_info['search_type'];
            $result['search_type'] = $post_info['search_type'];
        }

        //资金是否已被统计
        $post_info['search_is_statistics'] = check_int($post_info['search_is_statistics']);
        if(!empty($post_info['search_is_statistics'])){
            if($post_info['search_is_statistics'] == 1){
                $where['is_statistics'] = 1;
            }else{
                $where['is_statistics'] = 0;
            }
            $result['search_is_statistics'] = $post_info['search_is_statistics'];
        }

        $result['where'] = $where;

        return $result;
    }

    /**
     * 添加流水
     */
    public function addFund(){

        if(IS_POST){
            $post_info = I("post.");
            if(!empty($post_info['fund']) && !empty($post_info['remark'])){

                $Fund = new \Yege\Fund();
                $Fund->fund = $post_info['fund'];
                $Fund->remark = $post_info['remark'];
                $result = $Fund->addFundLog();

                if($result['state'] == 1){
                    //添加成功回到列表
                    redirect('/Admin/Fund/fundList');
                }else{
                    $this->error("添加失败：".$result['message']);
                }
            }else{
                $this->error("请正确填写流动金额与备注信息");
            }
        }else{
            $this->display();
        }

    }

    /**
     * 资金提现
     */
    public function withdrawFund(){
        if(IS_POST){
            $post_info = I("post.");
            if(!empty($post_info['fund']) && !empty($post_info['remark'])){

                $Fund = new \Yege\Fund();
                $Fund->fund = $post_info['fund'];
                $Fund->remark = $post_info['remark'];
                $result = $Fund->addFundLog();

                if($result['state'] == 1){
                    //添加成功回到列表
                    redirect('/Admin/Fund/fundList');
                }else{
                    $this->error("添加失败：".$result['message']);
                }
            }else{
                $this->error("请正确填写流动金额与备注信息");
            }
        }else{
            $this->display();
        }
    }

}