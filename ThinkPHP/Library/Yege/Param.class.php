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
 *
 * saveDataGoodsListShowAttr（私有）    数据处理 商品列表 -- 展示属性
 *
 * checkParam（私有）                   表中的参数值检测与数据返回
 *
 */

class Param{

    public $param_list = [ //拥有的参数列表
        "goodsListShowAttr" => "商品列表 -- 展示属性",
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
            $data_result = call_user_func('saveData'.$param,$data_info);
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
            $result['state'] = 1;
            $result['message'] = '参数获取成功';
            $result['data'] = $data_result['data'];
        }else{
            $result['message'] = '参数获取失败：'.$data_result['message'];
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

        return $result;
    }

    //===============辅助方法===============

    /**
     * 表中的参数值检测与数据返回
     * @param array $param 指定参数键名
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

}
