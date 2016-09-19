<?php
namespace Yege;

/**
 * 资金类
 *
 * 方法提供
 * getFundList          获取资金列表
 * getFundInfo          获取资金信息
 * addFundLog           添加资金流水
 * updateFund           变更资金记录
 * withdrawFund         提取资金
 *
 */

class Fund{

    //提供于外面赋值或读取的相关参数
    public $fund = 0; //流动资金
    public $remark = ""; //流动备注

    private $fund_id = 1; //资金记录id
    private $fund_table = ""; //资金记录表
    private $fund_log_table = ""; //资金流水日志表
    private $fund_statistics_table = ""; //资金流水日统计表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->fund_table = C("TABLE_NAME_FUND");
        $this->fund_log_table = C("TABLE_NAME_FUND_LOG");
        $this->fund_statistics_table = C("TABLE_NAME_FUND_STATISTICS");
    }

    /**
     * 获取资金列表
     * @param array $where 搜索条件
     * @param int $page 页码
     * @param int $num 单页数量
     * @return array $list 结果返回
     */
    public function getFundList($where = [],$page = 1,$num = 20){
        $result = [
            "list" => [],
            "count" => 0,
        ];

        //基础参数

        $limit = ($page-1)*$num.",".$num;

        $fund_list = M($this->fund_log_table)
            ->where($where)
            ->limit($limit)
            ->order("inputtime DESC,id DESC")
            ->select();

        $result['list'] = $fund_list;

        //数量获取
        $count = M($this->fund_log_table)
            ->where($where)
            ->count();
        $result['count'] = empty($count) ? 0 : $count;

        return $result;
    }

    /**
     * 获取资金信息
     * @result array $result 结果返回
     */
    public function getFundInfo(){
        $result = [];

        $result = M($this->fund_table)->where(["id"=>$this->fund_id])->find();
        if(empty($result['id'])){
            //创建出这条数据
            $add['id'] = 1;
            $add['inputtime'] = $add['updattime'] = time();
            if(M($this->fund_table)->add($add)){
                //再次获取
                $result = M($this->fund_table)->where(["id"=>$this->fund_id])->find();
            }
        }

        return $result;
    }

    /**
     * 添加资金流水
     * @result array $result 结果返回
     */
    public function addFundLog(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //四舍五入 2 位小数
        $fund = round($this->fund,2);
        $remark = check_str($this->remark);
        if(!empty($fund)){
            if(!empty($remark)){
                //当前资金记录获取
                $info = $this->getFundInfo();
                if(!empty($info['id'])){
                    M()->startTrans();
                    $add = [];
                    $add['fund'] = $fund;
                    $add['result_fund'] = $info['fund']+$fund;
                    $add['remark'] = $remark;
                    $add['inputtime'] = time();
                    if(M($this->fund_log_table)->add($add)){
                        //改变资金记录
                        $temp = $this->updateFund();
                        if($temp['state'] == 1){
                            $result['state'] = 1;
                            $result['message'] = "操作成功";
                            M()->commit();
                        }else{
                            M()->rollback();
                            $result['message'] = "资金操作失败：".$temp['message'];
                        }
                    }else{
                        M()->rollback();
                        $result['message'] = "添加流水失败";
                    }
                }else{
                    $result['message'] = "资金记录未能获取";
                }
            }else{
                $result['message'] = "备注信息必须填写";
            }
        }else{
            $result['message'] = "错误的流动资金";
        }

        return $result;
    }

    /**
     * 变更资金记录
     * @result array $result 结果返回
     */
    private function updateFund(){
        //当前资金记录获取
        $info = $this->getFundInfo();
        if(!empty($info['id'])){
            //四舍五入 2 位小数
            $fund = round($this->fund,2);
            if(!empty($fund)){
                $save = [];
                $save['fund'] = ["exp","fund + ".$fund];
                $save['profit'] = ["exp","profit + ".$fund];
                if($fund > 0){
                    $save['income'] = ["exp","income + ".abs($fund)];
                }else{
                    $save['expenses'] = ["exp","income + ".abs($fund)];
                }
                $save['updatetime'] = time();
                if(M($this->fund)->where(["id"=>$this->fund_id])->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "资金记录操作成功";
                }else{
                    $result['message'] = "资金记录操作失败";
                }
            }else{
                $result['message'] = "变更资金错误";
            }
        }else{
            $result['message'] = "资金记录未能获取";
        }
        return $result;
    }

    /**
     * 提取资金
     * @result array $result 结果返回
     */
    public function withdrawFund(){
        //当前资金记录获取
        $info = $this->getFundInfo();
        if(!empty($info['id'])){
            //四舍五入 2 位小数
            $fund = round($this->fund,2);
            if($fund > 0){
                M()->startTrans();
                $save = [];
                $save['fund'] = ["exp","fund - ".$fund];
                $save['withdraw'] = ["exp","withdraw + ".$fund];
                $save['updatetime'] = time();
                if(M($this->fund)->where(["id"=>$this->fund_id])->save($save)){
                    //记一笔流水
                    $remark = check_str($this->remark);
                    $add = [];
                    $add['fund'] = -$fund;
                    $add['result_fund'] = $info['fund'] - $fund;
                    $add['remark'] = "资金提现".(empty($remark) ? "" : (":".$remark));
                    $add['inputtime'] = time();
                    if(M($this->fund_log_table)->add($add)){
                        M()->commit();
                        $result['state'] = 1;
                        $result['message'] = "操作成功";
                    }else{
                        M()->rollback();
                        $result['message'] = "资金流水操作失败";
                    }
                }else{
                    M()->rollback();
                    $result['message'] = "资金记录操作失败";
                }
            }else{
                $result['message'] = "变更资金错误";
            }
        }else{
            $result['message'] = "资金记录未能获取";
        }
        return $result;
    }

    /**
     * 统计资金
     * @result array $result 结果返回
     */
    public function statisticsFund(){
        $result = ["state"=>0,"message"=>"未知错误"];

        //首先获取未统计的信息
        $where = [
            "is_statistics" => 0,
        ];
        $list = M($this->fund_log_table)->where($where)->order("inputtime DESC,id DESC")->select();

        $result_fund = "";
        $wrong = 0;
        foreach($list as $key => $val){
            //循环检验一波
            if($result_fund == ""){
                $result_fund = $val['result_fund'];
            }else{
                $result_fund += $val['fund'];
                if($result_fund != $val['result_fund']){
                    $wrong = 1;
                    break;
                }
            }
        }

        //记录无误 开始逐条做数据统计
        if($wrong == 0){
            foreach($list as $key => $val){

            }
        }else{
            $result['message'] = "记录有误，操作失败";
        }

        return $result;
    }

}
