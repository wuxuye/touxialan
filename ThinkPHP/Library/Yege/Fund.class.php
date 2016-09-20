<?php
namespace Yege;

/**
 * 资金类
 *
 * 方法提供
 * getFundList          获取资金列表
 * getFundInfo          获取资金信息
 * addFundLog           添加资金流水（常规资金流动）
 * updateFund           变更资金记录
 * withdrawFund         提取资金
 * statisticsFund       统计资金
 *
 */

class Fund{

    //提供于外面赋值或读取的相关参数
    public $fund = 0; //流动资金
    public $remark = ""; //流动备注
    public $type = 0; //资金流动类型

    private $fund_id = 1; //资金记录id
    private $fund_table = ""; //资金记录表
    private $fund_log_table = ""; //资金流水日志表
    private $fund_statistics_table = ""; //资金流水日统计表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->fund_table = C("TABLE_NAME_FUND");
        $this->fund_log_table = C("TABLE_NAME_FUND_LOG");
        $this->fund_statistics_table = C("TABLE_NAME_STATISTICS_FUND");
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
            "fund" => 0, //合计资金
        ];

        //基础参数

        $limit = ($page-1)*$num.",".$num;

        $fund_list = M($this->fund_log_table)
            ->where($where)
            ->limit($limit)
            ->order("inputtime DESC,id DESC")
            ->select();

        $result['list'] = $fund_list;

        //合计资金
        foreach($fund_list as $info){
            $result['fund'] += $info['fund'];
        }

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
     * 添加资金流水（常规资金流动）
     * 需要 fund 与 remark
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
                    $add['type'] = C("TYPE_FUND_LOG_PUBLIC");
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
                if(M($this->fund_table)->where(["id"=>$this->fund_id])->save($save)){
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
     * 需要 fund 与 remark（remark 不必要）
     * @result array $result 结果返回
     */
    public function withdrawFund(){
        //当前资金记录获取
        $info = $this->getFundInfo();
        if(!empty($info['id'])){
            //四舍五入 2 位小数
            $fund = round($this->fund,2);
            if($fund > 0){
                if($fund <= $info['fund']){
                    M()->startTrans();
                    $save = [];
                    $save['fund'] = ["exp","fund - ".$fund];
                    $save['withdraw'] = ["exp","withdraw + ".$fund];
                    $save['updatetime'] = time();
                    if(M($this->fund_table)->where(["id"=>$this->fund_id])->save($save)){
                        //记一笔流水
                        $remark = check_str($this->remark);
                        $add = [];
                        $add['fund'] = -$fund;
                        $add['result_fund'] = $info['fund'] - $fund;
                        $add['remark'] = "资金提现".(empty($remark) ? "" : (":".$remark));
                        $add['type'] = C("TYPE_FUND_LOG_WITHDRAW");
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
                    $result['message'] = "没有足够的资金可供提现";
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
        $result = ["state"=>0,"message"=>"未知错误","wrong_list"=>[]];

        //首先获取未统计的信息
        $where = [
            "is_statistics" => 0,
        ];
        $list = M($this->fund_log_table)->where($where)->order("inputtime ASC,id ASC")->select();

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
            $data = []; //待更新集合
            $wrong_list = []; //错误集合
            foreach($list as $key => $val){
                //时间检验
                $time = strtotime(date("Y-m-d 00:00:00",$val['inputtime']));
                if(!empty($time)){
                    //组装待更新集合
                    if(empty($data[$time])){
                        $data[$time] = [
                            "income" => 0,
                            "expenses" => 0,
                            "withdraw" => 0,
                            "id_list" => [],
                        ];
                    }

                    if(empty($val['fund'])){
                        $wrong_list[] = "记录id：".$val['id']." 错误的流动金额";
                        continue;
                    }

                    //首先看类型
                    switch($val['type']){
                        case C("TYPE_FUND_LOG_PUBLIC") : //常规
                            if($val['fund'] > 0){ //收入
                                $data[$time]['income'] += $val['fund'];
                                $data[$time]['id_list'][] = $val['id'];
                            }else{ //支出
                                $data[$time]['expenses'] += abs($val['fund']);
                                $data[$time]['id_list'][] = $val['id'];
                            }
                            break;
                        case C("TYPE_FUND_LOG_WITHDRAW") : //提现
                            $data[$time]['withdraw'] += abs($val['fund']);
                            $data[$time]['id_list'][] = $val['id'];
                            break;
                        default : //未知的类型
                            $wrong_list[] = "记录id：".$val['id']." 类型未知";
                            continue;
                    }
                }else{
                    $wrong_list[] = "记录id：".$val['id']." 时间错误";
                }
            }

            //跑完后计算利润 并做统计记录更新
            foreach($data as $key => $val){
                $temp = $val;
                //利润 = 收入 - 支出
                $temp["profit"] = $temp['income'] - $temp['expenses'];
                //统计数据获取
                $statistics_id = 0;
                $info = M($this->fund_statistics_table)->where(["record_time"=>$key])->find();
                if(!empty($info['id'])){
                    $statistics_id = $info['id'];
                }else{
                    //生成新的一条统计数据
                    $add = [
                        "record_time" => $key,
                    ];
                    $statistics_id = M($this->fund_statistics_table)->add($add);
                }
                //最后的统计id判断
                if(!empty($statistics_id)){
                    $save = [
                        "profit" => ['exp','profit + '.$temp["profit"]],
                        "income" => ['exp','income + '.$temp["income"]],
                        "expenses" => ['exp','expenses + '.$temp["expenses"]],
                        "withdraw" => ['exp','withdraw + '.$temp["withdraw"]],
                    ];
                    if(M($this->fund_statistics_table)->where(["id"=>$statistics_id])->save($save)){
                        //标记已统计
                        M($this->fund_log_table)->where(["id"=>["in",$temp['id_list']]])->save(["is_statistics"=>1]);
                    }else{
                        $wrong_list[] = date("Y-m-d",$key)." 的数据统计更新失败。";
                    }
                }else{
                    $wrong_list[] = date("Y-m-d",$key)." 的数据统计失败。";
                }
            }

            //到这直接算成功
            $result['state'] = 1;
            $result['message'] = "统计结束";
            $result['wrong_list'] = $wrong_list;

        }else{
            $result['message'] = "记录有误，操作失败";
        }

        return $result;
    }


    /**
     * 获取统计数据
     * @param int $level 搜索级别 1 年列表 、2 月列表 、3 日列表
     * @param string $time 时间搜索值 根据 $level 参数变化
     * @return array $result 统计结果返回
     */
    public function getStatisticsData($level = 1,$time = ""){
        $result = [];
        $result['level'] = $level;
        $result['statistics'] = [];
        switch($level){
            case 1: //年列表统计($time参数无效)
                //以2016年为起点的3年
                $result['statistics'] = [
                    "2016" => [
                        "profit" => 0,
                        "income" => 0,
                        "expenses" => 0,
                        "withdraw" => 0,
                    ],"2017" => [
                        "profit" => 0,
                        "income" => 0,
                        "expenses" => 0,
                        "withdraw" => 0,
                    ], "2018" => [
                        "profit" => 0,
                        "income" => 0,
                        "expenses" => 0,
                        "withdraw" => 0,
                    ],
                ];
                //获取统计表中的全部数据
                $temp = [];
                $temp = M($this->fund_statistics_table)
                    ->order("record_time ASC")
                    ->select();
                //开始统计
                foreach($temp as $val){
                    $temp_time = date("Y",$val['record_time']);
                    if(empty($result['statistics'][$temp_time])){
                        $result['statistics'][$temp_time] = [
                            "profit" => 0,
                            "income" => 0,
                            "expenses" => 0,
                            "withdraw" => 0,
                        ];
                    }
                    $result['statistics'][$temp_time]["profit"] += $val['profit'];
                    $result['statistics'][$temp_time]["income"] += $val['income'];
                    $result['statistics'][$temp_time]["expenses"] += $val['expenses'];
                    $result['statistics'][$temp_time]["withdraw"] += $val['withdraw'];
                }
                break;
            case 2: //月列表统计($time参数表示年份)
                //默认年份是今年
                if(empty($time) || !is_date($time."-01-01")){
                    $time = date("Y",time());
                }

                $result['year'] = $time;

                //填充月份
                for($i=1;$i<=12;$i++){
                    $month_str = $i < 10 ? "0".$i : $i;
                    $result['statistics']["month_".$month_str] = [
                        "profit" => 0,
                        "income" => 0,
                        "expenses" => 0,
                        "withdraw" => 0,
                        'month' => $month_str,
                    ];
                }

                $temp = $where = [];
                $where['record_time'][] = ['egt',strtotime($time."-01-01 00:00:00")];
                $where['record_time'][] = ['lt',strtotime(($time+1)."-01-01 00:00:00")];
                $temp = M($this->fund_statistics_table)
                    ->where($where)
                    ->order("record_time ASC")
                    ->select();
                //开始统计
                foreach($temp as $val){
                    $temp_time = "month_".date("m",$val['record_time']);
                    if(empty($result['statistics'][$temp_time])){
                        $result['statistics'][$temp_time] = [
                            "profit" => 0,
                            "income" => 0,
                            "expenses" => 0,
                            "withdraw" => 0,
                            'month' => date("m",$val['record_time'])
                        ];
                    }
                    $result['statistics'][$temp_time]["profit"] += $val['profit'];
                    $result['statistics'][$temp_time]["income"] += $val['income'];
                    $result['statistics'][$temp_time]["expenses"] += $val['expenses'];
                    $result['statistics'][$temp_time]["withdraw"] += $val['withdraw'];
                }

                break;
            case 3: //日列表统计($time参数表示月份)
                //默认月份是今年这个月
                if(empty($time) || !is_date($time."-01")){
                    $time = date("Y-m",time());
                }

                $result['year'] = date("Y",strtotime($time));
                $result['month'] = date("m",strtotime($time));

                //填充天数
                $day = date("t",strtotime($time."-01"));
                for($i=1;$i<=$day;$i++){
                    $day_str = $i < 10 ? "0".$i : $i;
                    $result['statistics']["day_".$day_str] = [
                        "profit" => 0,
                        "income" => 0,
                        "expenses" => 0,
                        "withdraw" => 0,
                        "day" => $day_str,
                    ];
                }

                $temp = $where = [];
                $where['record_time'][] = ['egt',strtotime($time."-01 00:00:00")];
                $where['record_time'][] = ['lt',(strtotime($time."-01 00:00:00")+($day*24*60*60))];
                $temp = M($this->fund_statistics_table)
                    ->where($where)
                    ->order("record_time ASC")
                    ->select();

                //开始统计
                foreach($temp as $val){
                    $temp_time = "day_".date("d",$val['record_time']);
                    if(empty($result['statistics'][$temp_time])){
                        $result['statistics'][$temp_time] = [
                            "profit" => 0,
                            "income" => 0,
                            "expenses" => 0,
                            "withdraw" => 0,
                            "day" => date("d",$val['record_time']),
                        ];
                    }

                    $result['statistics'][$temp_time]["profit"] += $val['profit'];
                    $result['statistics'][$temp_time]["income"] += $val['income'];
                    $result['statistics'][$temp_time]["expenses"] += $val['expenses'];
                    $result['statistics'][$temp_time]["withdraw"] += $val['withdraw'];

                }
                break;
        }

        ksort($result['statistics']);

        return $result;
    }

}
