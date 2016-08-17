<?php
namespace Yege;

/**
 * 订单类
 *
 * 方法提供
 * analysisGoodsCode        解析商品串码
 */

class Order{

    //提供于外面赋值或读取的相关参数
    public $goods_code = ""; //商品串码
    public $goods_list = []; //商品列表

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
                $cart_list = $goods_list = [];
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
                //用清单id获取全部的商品id
                if(!empty($cart_id_list)){
                    $cart_info_list = [];
                    $cart_info_list = M($this->cart_table)->field(["goods_id"])->where(["id"=>["in",$cart_id_list]])->select();
                    foreach($cart_info_list as $info){
                        $goods_list[] = $info['goods_id'];
                    }
                }
            }

            if(!empty($goods_list)){
                $this->goods_list = $goods_list;
            }

        }
    }

}
