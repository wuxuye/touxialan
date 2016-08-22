<?php
namespace Yege;

/**
 * 订单类
 *
 * 方法提供
 * analysisGoodsCode                   解析商品串码
 * createOrder                         生成订单
 * checkOrderUser（私有）               生成订单时的用户检测
 * checkOrderGoods（私有）              生成订单时的商品检验，将返回最后用作订单生成的商品数据
 * createOrderByGoodsList（私有）       根据商品列表，为用户生成订单
 *
 */

class Order{

    //提供于外面赋值或读取的相关参数
    public $goods_code = ""; //商品串码
    public $cart_list = []; //清单列表
    public $user_id = 0; //用户id

    private $goods_list = []; //最终用户生成商品的商品数据列表
    private $cart_table = ""; //清单表
    private $goods_table = ""; //商品表
    private $goods_stock_table = ""; //商品库存信息表
    private $order_table = ""; //订单表
    private $order_goods_table = ""; //订单商品关联表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->cart_table = C("TABLE_NAME_CART");
        $this->goods_table = C("TABLE_NAME_GOODS");
        $this->goods_stock_table = C("TABLE_NAME_GOODS_STOCK");
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

                $this->cart_list = $cart_id_list;
            }
        }
    }

    /**
     * 生成订单
     * @return array $result 结果集返回
     */
    public function createOrder(){
        $result = ["state"=>0,"message"=>"未知错误",'order_id'=>0,"tip_title"=>""];

        //用户检测
        $user_check = $this->checkOrderUser();
        if($user_check['state'] == 1){
            //商品检查
            $goods_check = $this->checkOrderGoods();
            if($goods_check['state'] == 1){
                //订单检查
                $order_check = $this->checkOrder();
                if($order_check['state'] == 1){
                    //用最终的商品数据生成订单
                    $order_result = $this->createOrderByGoodsList();
                    if($order_result['state'] == 1){
                        $result['state'] = 1;
                        $result['order_id'] = $order_result['order_id'];
                        $result['message'] = '订单生成成功';
                    }else{
                        $result['tip_title'] = '生成订单失败';
                        $result['message'] = $order_result['message'];
                    }
                }else{
                    $result['tip_title'] = '订单限制';
                    $result['message'] = $order_check['message'];
                }
            }else{
                $result['tip_title'] = '商品错误';
                $result['message'] = $goods_check['message'];
            }
        }else{
            $result['tip_title'] = '用户错误';
            $result['message'] = $user_check['message'];
        }

        return $result;
    }

    /**
     * 生成订单时的用户检测
     * @return array $result 结果集返回
     */
    private function checkOrderUser(){
        $result = ["state"=>0,"message"=>"未知错误"];

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
                        $result['message'] = "此用户被冻结，暂无法使用此功能，您可以到 <a href='JavaScript:;' class='tip_a'>用户记录</a> 页面查看原因。";
                    }elseif($user_info['state'] == C("STATE_USER_DELETE")){
                        //删除用户
                        $result['message'] = "此用户被删除，暂无法使用此功能，您可以到 <a href='JavaScript:;' class='tip_a'>用户记录</a> 页面查看原因。";
                    }elseif($user_info['state'] == C("STATE_USER_NORMAL")){
                        //正常用户
                        $result['message'] = "验证成功";
                        $result['state'] = 1;
                    }else{
                        $result['message'] = "用户状态错误，请刷新页面后重试。";
                    }
                }else{
                    $result['message'] = "用户数据错误，请刷新页面后重试。";
                }
            }else{
                $result['message'] = $user_result['message'];
            }
        }else{
            $result['message'] = '用户参数缺失，请刷新页面后重试。';
        }

        return $result;
    }

    /**
     * 生成订单时的商品检验，将返回最后用作订单生成的商品数据
     * @return array $result 结果集返回
     */
    private function checkOrderGoods(){
        $result = ["state"=>0,"message"=>"未知错误"];

        $goods_list = [];
        //循环获取商品数据
        foreach($this->cart_list as $info){
            $cart_id = intval($info);
            //获取商品的相关数据
            $goods_info = M($this->cart_table." as cart")
                ->field([
                    "goods.id","goods.price","goods.point","goods.can_price",
                    "goods.can_point","stock.stock","cart.pay_type","cart.goods_num",
                ])
                ->join("left join ".C("DB_PREFIX").$this->goods_table." as goods on goods.id = cart.goods_id")
                ->join("left join ".C("DB_PREFIX").$this->goods_stock_table." as stock on stock.goods_id = cart.goods_id")
                ->where([
                    "cart.id" => $cart_id,
                    "goods.state" => C("STATE_GOODS_NORMAL"),
                    "goods.is_shop" => 1,
                ])->find();
            //商品参数检验
            if(!empty($goods_info['id']) && $goods_info['stock'] > 0 && $goods_info['goods_num'] > 0){
                if($goods_info['stock'] >= $goods_info['goods_num'] && !empty(C("PAY_TYPE_CART_LIST")[$goods_info['pay_type']])){
                    //支付类型判断
                    if($goods_info['pay_type'] == C("PAY_TYPE_CART_MONEY")){
                        if($goods_info["price"] <= 0){
                            //错误的支付金额
                            continue;
                        }
                    }elseif($goods_info['pay_type'] == C("PAY_TYPE_CART_POINT")){
                        if($goods_info["point"] <= 0){
                            //错误的支付积分
                            continue;
                        }
                    }else{
                        //错误的支付方式
                        continue;
                    }

                    //跑到这就把商品信息存到最终数组中
                    $goods_list[] = $goods_info;
                }
            }
        }

        if(!empty($goods_list)){
            $this->goods_list = $goods_list;
            $result['state'] = 1;
            $result['message'] = '商品检测完成';
        }else{
            $result['message'] = '商品数据发生改变，没有可购买的商品，请刷新清单列表页后重试。';
        }

        return $result;
    }

    /**
     * 生成订单时的限制检验
     * @return array $result 结果集返回
     */
    private function checkOrder(){
        $result = ["state"=>0,"message"=>"未知错误"];

        //用户最多能拥有的未确认订单数
        $wait_confirm_num = C("HOME_ORDER_MAX_USER_WAIT_CONFIRM_NUM");
        $remove_state = [ //次判断下排除3个状态
            C("STATE_ORDER_SUCCESS"), //已完成
            C("STATE_ORDER_CLOSE"), //已关闭
            C("STATE_ORDER_BACK"), //已退单
        ];
        $where = [
            "user_id" => $this->user_id,
            "state" => ["not in",$remove_state],
            "is_confirm" => 0,
        ];
        $num = M($this->order_table)->where($where)->count();
        if($num < $wait_confirm_num){
            //用户最多能拥有的待完结订单数
            $wait_success_num = C("HOME_ORDER_MAX_USER_WAIT_SUCCESS_NUM");
            $where = [
                "user_id" => $this->user_id,
                "state" => ["not in",$remove_state],
            ];
            $num = M($this->order_table)->where($where)->count();
            if($num < $wait_success_num){
                $result['state'] = 1;
                $result["message"] = "订单检测完成";
            }else{
                $result["message"] = "您最多只能有 ".$wait_success_num." 张未完结的订单，请先等待订单完结。";
            }
        }else{
            $result["message"] = "您最多只能有 ".$wait_confirm_num." 张未确认的订单，请先去 <a href='JavaScript:;' class='tip_a'>订单中心</a> 确认订单。";
        }

        return $result;
    }

    /**
     * 根据商品列表，为用户生成订单
     * @return array $result 结果集返回
     */
    private function createOrderByGoodsList(){
        $result = ["state"=>0,"message"=>"未知错误","order_id"=>0];

        if(!empty($this->goods_list)){
            //开始下单逻辑
            M()->startTrans();
            //首先为用户生成一张订单
            $add = [
                "order_code" => "D".date("md",time()).time().rand(10000,99999),
                "goods_code" => $this->goods_code,
                "user_id" => $this->user_id,
                "inputtime" => time(),
                "updatetime" => time(),
            ];
            $order_id = M($this->order_table)->add($add);
            if(!empty($order_id)){
                $price = $point = $goods_num = 0; //统计准备
                foreach($this->goods_list as $info){
                    //逐个将商品添加至订单商品关联表
                    $add = [
                        "goods_id" => $info['id'],
                        "order_id" => $order_id,
                        "goods_num" => $info['goods_num'],
                        "pay_type" => $info['pay_type'],
                        "price" => $info['price'],
                        "point" => $info['point'],
                    ];
                    if(M($this->order_goods_table)->add($add)){
                        $goods_num ++;
                        if($info['pay_type'] == C("PAY_TYPE_CART_MONEY")){
                            $price += $info['price'];
                        }elseif($info['pay_type'] == C("PAY_TYPE_CART_POINT")){
                            $point += $info['point'];
                        }else{
                            M()->rollback();
                            $result['message'] = '商品支付方式错误';
                            return $result;
                        }
                    }else{
                        M()->rollback();
                        $result['message'] = '商品添加失败';
                        return $result;
                    }
                }
                //最后将统计数据更新至订单表
                $where = [
                    "id" => $order_id,
                ];
                $save = [
                    "order_price" => $price,
                    "pay_price" => $price,
                    "point" => $point,
                    "goods_num" => $goods_num,
                ];
                if(M($this->order_table)->where($where)->save($save)){
                    //到这里算订单生成成功
                    M()->commit();
                    $result['state'] = 1;
                    $result['order_id'] = $order_id;
                    $result['message'] = '订单生成成功';
                }else{
                    M()->rollback();
                    $result['message'] = '订单修改失败';
                }
            }else{
                M()->rollback();
                $result['message'] = '订单添加失败';
            }
        }else{
            $result['message'] = '没有商品数据';
        }

        return $result;
    }

}
