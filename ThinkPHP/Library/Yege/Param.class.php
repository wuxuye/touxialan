<?php
namespace Yege;

/**
 * 基础参数类
 *
 * 方法提供
 * getDataByParam                      通过传递来的参数值获取需要的数据
 * saveDataByParam                     通过传递来的参数值处理需要的数据
 *
 * getDataGoodsListShowAttr（私有）     数据获取 商品列表 -- 展示属性
 * getDataFundStatisticsLastTime（私有）    数据获取 资金统计 -- 最后一次统计时间获取
 *
 * saveDataGoodsListShowAttr（私有）    数据处理 商品列表 -- 展示属性
 * saveDataFundStatisticsLastTime（私有）   数据处理 资金统计 -- 更新最后一次统计时间
 *
 * checkParam（私有）                   表中的参数值检测与数据返回
 * saveParam（私有）                    处理参数表中的数据
 *
 */

class Param{

    public $param_list = [ //拥有的参数列表
        "goodsListShowAttr" => ["str"=>"商品列表 -- 展示属性","param"=>"GoodsListShowAttr","is_show"=>1],
        "fundStatisticsLastTime" => ["str"=>"资金统计 -- 最后次统计时间","param"=>"FundStatisticsLastTime","is_show"=>0]
    ];

    private $param_table = ""; //全站基础参数配置表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->param_table = C("TABLE_NAME_PARAM");
    }

    /**
     * 通过传递来的参数值获取需要的数据
     * @param string $param 指定参数
     * @return array $result 结果返回
     */
    public function getDataByParam($param = ''){
        $result = ['state' => 0,'message' => '未知错误','display' => '','data' => []];

        //去相应获取数据逻辑
        $data_result = [];
        if(!empty($this->param_list[$param])){
            $data_result = call_user_func([$this,'getData'.ucfirst($param)]);
        }else{
            $data_result['message'] = '无对应方法';
        }

        if($data_result['state'] == 1){
            $result['state'] = 1;
            $result['display'] = $data_result['display'];
            $result['data'] = $data_result['data'];
            $result['message'] = '获取成功';
        }else{
            $result['message'] = '获取失败：'.$data_result['message'];
        }

        return $result;
    }

    /**
     * 通过传递来的参数值处理需要的数据
     * @param string $param 指定参数
     * @param array $data_info 待处理参数集合
     * @return array $result 结果返回
     */
    public function saveDataByParam($param = '',$data_info = []){
        $result = ['state' => 0,'message' => '未知错误'];

        //去相应处理数据逻辑
        $data_result = [];
        if(!empty($this->param_list[$param])){
            $data_result = call_user_func([$this,'saveData'.ucfirst($param)],$data_info);
        }else{
            $data_result['message'] = '无对应方法';
        }

        if($data_result['state'] == 1){
            $result['state'] = 1;
            $result['message'] = '处理成功';
        }else{
            $result['message'] = '处理失败：'.$data_result['message'];
        }

        return $result;
    }

    //===============数据获取方法===============

    /**
     * 数据获取 商品列表 -- 展示属性
     */
    private function getDataGoodsListShowAttr(){
        $result = ['state' => 0,'message' => '未知错误','display' => 'goodsListShowAttr','data' => []];

        $data_result = $this->checkParam('GoodsListShowAttr');
        if($data_result['state'] == 1){
            //对属性id进行解析
            foreach($data_result['data'] as $key => $val){
                $Attr = new \Yege\Attr();
                $attr_info = $Attr->getInfo($val['attr_id']);
                if($attr_info['state'] == 1){
                    $data_result['data'][$key]['str'] = $attr_info['result']['attr_name'];
                }else{
                    unset($data_result['data'][$key]);
                }
            }
            $result['state'] = 1;
            $result['message'] = '参数获取成功';
            $result['data'] = $data_result['data'];
        }else{
            $result['message'] = '参数获取失败：'.$data_result['message'];
        }

        return $result;
    }

    /**
     * 数据获取 资金统计 -- 最后一次统计时间
     */
    private function getDataFundStatisticsLastTime(){
        $result = ['state' => 0,'message' => '未知错误','display' => 'fundStatisticsLastTime','data' => 0];

        $data_result = $this->checkParam('FundStatisticsLastTime');

        if($data_result['state'] == 1){
            $result['state'] = 1;
            $result['message'] = "获取成功";
            $result['data'] = $data_result['data']['time'];
        }

        return $result;
    }


    //===============数据处理方法===============

    /**
     * 数据处理 商品列表 -- 展示属性
     * @param array $data_info 待处理数据
     */
    private function saveDataGoodsListShowAttr($data_info = []){
        $result = ['state' => 0,'message' => '未知错误'];

        if(!empty($data_info['attr_show_title'])){
            //循环栏目名
            $Attr = new \Yege\Attr();
            $result_array = [];
            foreach($data_info['attr_show_title'] as $key => $val){
                if(!empty($data_info['attr_show_id'][$key])){
                    //属性详情获取
                    $attr_info = $Attr->getInfo($data_info['attr_show_id'][$key]);
                    if(!empty($attr_info['state'] == 1)){
                        $result_array[] = ['title'=>$val,'attr_id'=>$attr_info['result']['id']];
                    }
                }
            }
            if(!empty($result_array)){
                //json转换
                $json_result = json_encode($result_array,JSON_UNESCAPED_UNICODE);
                if(!empty($json_result)){
                    $save_result = [];
                    $save_result = $this->saveParam($this->param_list['goodsListShowAttr']['param'],$json_result);
                    if($save_result['state'] == 1){
                        $result['state'] = 1;
                        $result['message'] = '操作成功';
                    }else{
                        $result['message'] = $save_result['message'];
                    }
                }else{
                    $result['message'] = '数据解析失败';
                }
            }else{
                $result['message'] = '没有正确的数据';
            }
        }else{
            $result['message'] = '没有相应的栏目数据';
        }

        return $result;
    }

    /**
     *  数据处理 资金统计 -- 最后一次统计时间 -- 更新最后一次统计时间
     */
    private function saveDataFundStatisticsLastTime($time = 0){
        $result = ['state' => 0,'message' => '未知错误'];

        if(!empty($time)){
            $result_array = [
                "time" => $time,
            ];
            //json转换
            $json_result = json_encode($result_array,JSON_UNESCAPED_UNICODE);
            if(!empty($json_result)){
                $save_result = [];
                $save_result = $this->saveParam($this->param_list['fundStatisticsLastTime']['param'],$json_result);
                if($save_result['state'] == 1){
                    $result['state'] = 1;
                    $result['message'] = '操作成功';
                }else{
                    $result['message'] = $save_result['message'];
                }
            }else{
                $result['message'] = '数据解析失败';
            }
        }else{
            $result['message'] = '统计时间错误';
        }

        return $result;
    }

    //===============辅助方法===============

    /**
     * 表中的参数值检测与数据返回
     * @param string $param 指定参数键名
     * @return array $result 参数结果返回
     */
    private function checkParam($param = ""){
        $result = ['state'=>0,'message'=>'未知错误','data'=>[]];
        $param = check_str($param);
        if(!empty($param)){
            //尝试在表中获取相应数据信息
            $data = M($this->param_table)->where(["param_key"=>$param])->find();
            if(empty($data['id'])){
                //添加数据
                $add = [];
                $add['param_key'] = $param;
                $add['inputtime'] = $add['updatetime'] = time();
                if(M($this->param_table)->add($add)){
                    $result['state'] = 1;
                    $result['data'] = [];
                    $result['message'] = '新的参数已被添加';
                }else{
                    $result['message'] = '添加参数失败';
                }
            }else{
                //数据返回
                $result['state'] = 1;
                $result['data'] = json_decode($data['param_value'],true);
                $result['message'] = '获取参数成功';
            }
        }else{
            $result['message'] = '参数为空';
        }
        return $result;
    }

    /**
     * 处理参数表中的数据
     * @param string $param 指定参数键名
     * @param string $data 指定数据
     */
    private function saveParam($param = '',$data = ''){
        $result = ['state'=>0,'message'=>'未知错误'];

        //先来一波参数检测
        $param_result = [];
        $param_result = $this->checkParam($param);
        if($param_result['state'] == 1){
            //开始处理数据
            $save = $where = [];
            $save['param_value'] = $data;
            $save['inputtime'] = time();
            $save['updatetime'] = time();
            $where['param_key'] = $param;
            if(M($this->param_table)->where($where)->save($save)){
                $result['state'] = 1;
                $result['message'] = "操作成功";
            }else{
                $result['message'] = "操作失败";
            }
        }else{
            $result['message'] = "参数检测失败：".$param_result['message'];
        }

        return $result;
    }

}
