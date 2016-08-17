<?php
namespace Yege;

/**
 * 订单类
 *
 * 方法提供
 * analysisGoodsCode        解析商品串码
 * createOrder              生成订单
 */

class Order{

    //提供于外面赋值或读取的相关参数
    public $goods_code = ""; //商品串码
    public $cart_list = []; //清单列表
    public $user_id = 0; //用户id

    private $cart_table = ""; //清单表
    private $order_table = ""; //订单表
    private $order_goods_table = ""; //订单商品关联表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->cart_table = C("TABLE_NAME_CART");
        $this->order_table = C("TABLE_NAME_ORDER");
        $this->order_goods_table = C("TABLE_NAME_ORDER_GOODS");
    }

    /**
     * 解析商品串码
     * 将$goods_code解析更新至$goods_list
     */
    public function analysisGoodsCode(){
        if(!empty($this->goods_code)){
            //用d分隔
            $temp = explode("d",$this->goods_code);
            $temp_list = [];
            foreach($temp as $info){
                if(!empty($info)){
                    $temp_list[] = $info;
                }
            }
            if(!empty($temp_list)){
                $cart_list = [];
                foreach($temp_list as $info){
                    $cart = [];
                    //用n分隔
                    $temp = explode("n",$info);
                    if(!empty($temp[0]) && !empty($temp[1])){
                        //第一个是清单表id
                        $cart['id'] = check_int($temp[0]);
                        //第二个用t分隔
                        $temp2 = explode("t",$temp[1]);
                        if(!empty($temp2[0]) && !empty($temp2[1])){
                            //第一个是数量 第二个是支付方式
                            $cart['num'] = check_int($temp2[0]);
                            $cart['type'] = check_int($temp2[1]);
                            if(!empty($cart['num']) && !empty(C('PAY_TYPE_CART_LIST')[$cart['type']])){
                                $cart_list[] = $cart;
                            }
                        }
                    }
                }
                $cart_id_list = [];
                //成功获取到清单数据时，为用户更新数据
                foreach($cart_list as $key => $val){
                    //将这些数据更新到清单表中
                    $save = [
                        "pay_type" => $val['type'],
                        "goods_num" => $val['num'],
                        "updatetime" => time(),
                    ];
                    $where = [
                        "id" => $val['id'],
                    ];
                    M($this->cart_table)->where($where)->save($save);
                    $cart_id_list[] = $val['id'];
                }

                $this->cart_list = $cart_list;
            }
        }
    }

    /**
     * 生成订单
     * @return array $result 结果集返回
     */
    public function createOrder(){
        $result = ["state"=>0,"message"=>"未知错误","user_wrong"=>0];

        //用户检测
        $user_check = $this->checkOrderUser();
        if($user_check['state'] == 1){

        }else{
            $result['message'] = '用户错误 - '.$user_check['message'];
            $result['user_wrong'] = $user_check['user_wrong'];
        }

        return $result;
    }

    /**
     * 生成订单时的用户检测
     * @return array $result 结果集返回
     */
    private function checkOrderUser(){
        $result = ["state"=>0,"message"=>"未知错误","user_wrong"=>0];

        if(!empty($this->user_id)){
            $User = new \Yege\User();
            $User->user_id = $this->user_id;
            $user_result = $User->getUserInfo();
            if($user_result['state'] == 1){
                $user_info = $user_result['result'];
                if(!empty($user_info['id'])){
                    //用户状态判断
                    if($user_info['state'] == C("STATE_USER_FREEZE")){
                        //冻结用户
                        $result['message'] = "此用户被冻结，暂无法使用此功能";
                        $result['user_wrong'] = 1;
                    }elseif($user_info['state'] == C("STATE_USER_DELETE")){
                        //删除用户
                        $result['message'] = "此用户被删除，暂无法使用此功能";
                        $result['user_wrong'] = 1;
                    }elseif($user_info['state'] == C("STATE_USER_NORMAL")){
                        //正常用户
                        $result['message'] = "验证成功";
                        $result['state'] = 1;
                    }else{
                        $result['message'] = "用户状态错误";
                    }
                }else{
                    $result['message'] = "用户数据错误";
                }
            }else{
                $result['message'] = $user_result['message'];
            }
        }else{
            $result['message'] = '用户参数缺失';
        }

        return $result;
    }

}
