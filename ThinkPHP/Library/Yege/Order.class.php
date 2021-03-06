<?php
namespace Yege;

/**
 * 订单类
 *
 * 方法提供
 * analysisGoodsCode                   解析商品串码
 * createOrder                         生成订单
 * checkOrderUser（私有）               生成订单时的用户检测
 * checkOrderTimeAndAddress（私有）     生成订单时的配送时间与地址检查
 * checkOrderGoods（私有）              生成订单时的商品检验，将返回最后用作订单生成的商品数据
 * checkPoint（私有）                   生成订单时的积分检查
 * checkOrder（私有）                   生成订单时的限制检验
 * createOrderByGoodsList（私有）       根据商品列表，为用户生成订单
 * getUserOrderInfo                    获取用户订单详情
 * getOrderLog                         获取订单日志列表
 * getUserOrderList                    获取用户订单列表详情
 * getTipMessageByOrderInfo（私有）     根据订单的详情，获取对应的提示信息
 * deleteInvalidOrder                  删除掉用户指定的无效订单
 * addOrderLog                         订单日志
 * updateOrderStateUnifiedInlet        订单状态更新统一入口
 * updateOrderStateConfirmOrder        订单状态更新 - 确认订单
 * updateOrderStateConfirmPay          订单状态更新 - 确认付款
 * updateOrderStateToDelivery          订单状态更新 - 待发货订单转至配送中
 * updateOrderStateSuccessOrder        订单状态更新 - 完成订单
 * onlyRecoverStock                    仅恢复订单库存
 */

class Order{

    //提供于外面赋值或读取的相关参数
    public $goods_code = ""; //商品串码
    public $cart_list = []; //清单列表
    public $user_id = 0; //用户id
    public $order_id = 0; //订单id
    public $send_week = 0; //送货周
    public $send_time = ""; //送货时间段
    public $send_address = ""; //送货地址
    public $remark = ""; //备注信息
    public $operation_remark = ""; //操作备注

    private $goods_list = []; //最终用户生成商品的商品数据列表

    private $order_info = []; //逻辑用订单详情

    private $cart_table = ""; //清单表
    private $goods_table = ""; //商品表
    private $goods_stock_table = ""; //商品库存信息表
    private $attr_table = ""; //商品属性表
    private $order_table = ""; //订单表
    private $order_goods_table = ""; //订单商品关联表
    private $order_log_table = ""; //订单日志表
    private $statistics_sale_table = ""; //商品统计相关表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->cart_table = C("TABLE_NAME_CART");
        $this->goods_table = C("TABLE_NAME_GOODS");
        $this->goods_stock_table = C("TABLE_NAME_GOODS_STOCK");
        $this->attr_table = C("TABLE_NAME_ATTR");
        $this->order_table = C("TABLE_NAME_ORDER");
        $this->order_goods_table = C("TABLE_NAME_ORDER_GOODS");
        $this->order_log_table = C("TABLE_NAME_ORDER_LOG");
        $this->statistics_sale_table = C("TABLE_NAME_STATISTICS_SALE");
        $this->user_id = intval($this->user_id);
        $this->order_id = intval($this->order_id);
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
            //配送时间与地址检查
            $time_and_address_check = $this->checkOrderTimeAndAddress();
            if($time_and_address_check['state'] == 1){
                //商品检查
                $goods_check = $this->checkOrderGoods();
                if($goods_check['state'] == 1){
                    //积分检验
                    $point_check = $this->checkPoint();
                    if($point_check['state'] == 1){
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
                        $result['tip_title'] = '积分不足';
                        $result['message'] = $point_check['message'];
                    }
                }else{
                    $result['tip_title'] = '商品错误';
                    $result['message'] = $goods_check['message'];
                }
            }else{
                $result['tip_title'] = '配送时间或地址错误';
                $result['message'] = $time_and_address_check['message'];
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
                        //正常用户 还要再进行一边信用分判断
                        $credit = check_int($user_info['credit']);
                        if($credit >= 0){
                            $result['message'] = "验证成功";
                            $result['state'] = 1;
                        }else{
                            $result['message'] = "抱歉，您的信用分不足，暂无法为您提供下单服务。您可以联系客服 QQ ".C("WEB_USE_QQ")." 来了解具体详情";
                        }
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
     * 生成订单时的配送时间与地址检查
     * @return array $result 结果集返回
     */
    private function checkOrderTimeAndAddress(){
        $result = ["state"=>0,"message"=>"未知错误"];

        //检查时间
        $shop_send_week_list = C("SHOP_SEND_WEEK_LIST");
        $send_week = check_int($this->send_week);
        if(!empty($shop_send_week_list[$send_week])){
            $shop_send_time_list = C("SHOP_SEND_TIME_LIST");
            $send_time = check_str($this->send_time);
            if(!empty($shop_send_time_list[$send_time])){
                $send_address = check_str($this->send_address);
                if(!empty($send_address)){
                    $result['state'] = 1;
                    $result['message'] = '时间与地址检测完成';
                }else{
                    $result["message"] = "请正确填写详细的配送地址";
                }
            }else{
                $result["message"] = "请正确选择配送时间段";
            }
        }else{
            $result["message"] = "请正确选择配送时间";
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
            $cart_id = check_int($info);
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
     * 生成订单时的积分检查
     * @return array $result 结果集返回
     */
    private function checkPoint(){
        $result = ["state"=>0,"message"=>"未知错误"];

        $point_obj = new \Yege\Point();
        $point_obj->user_id = $this->user_id;
        $point_info =  $point_obj->getUserPointInfo();
        //拿到用户积分
        $user_point = 0;
        if(!empty($point_info['result']['point_value'])){
            $user_point = intval($point_info['result']['point_value']);
        }
        //统计商品积分
        $goods_point = 0;
        foreach($this->goods_list as $info){
            if($info['pay_type'] == C('PAY_TYPE_CART_POINT') && $info['point'] > 0){
                $goods_point += $info['point'];
            }
        }
        if($goods_point <= $user_point){
            $result['state'] = 1;
            $result['message'] = '积分检测完成';
        }else{
            $result['message'] = '用户积分不足，请重新选择支付方式';
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
        $remove_state = [ //排除下面4个状态
            C("STATE_ORDER_SUCCESS"), //已完成
            C("STATE_ORDER_CLOSE"), //已关闭
            C("STATE_ORDER_DISSENT"), //有异议
            C("STATE_ORDER_BACK"), //已退单
        ];
        $where = [
            "user_id" => $this->user_id,
            "state" => ["not in",$remove_state],
            "is_confirm" => 0,
            "is_delete" => 0,
        ];
        $num = M($this->order_table)->where($where)->count();
        if($num < $wait_confirm_num){
            //用户最多能拥有的待完结订单数
            $wait_success_num = C("HOME_ORDER_MAX_USER_WAIT_SUCCESS_NUM");
            $where = [
                "user_id" => $this->user_id,
                "state" => ["not in",$remove_state],
                "is_delete" => 0,
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
            $order_code = "D".date("md",time()).time().rand(10000,99999);
            $add = [
                "order_code" => $order_code,
                "goods_code" => $this->goods_code,
                "user_id" => $this->user_id,
                "inputtime" => time(),
                "updatetime" => time(),
            ];
            $order_id = M($this->order_table)->add($add);
            if(!empty($order_id)){
                $price = $point = $goods_num = $goods_all_num = 0; //统计准备
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
                        $goods_all_num += $info['goods_num'];
                        if($info['pay_type'] == C("PAY_TYPE_CART_MONEY")){
                            $price += $info['price']*$info['goods_num'];
                        }elseif($info['pay_type'] == C("PAY_TYPE_CART_POINT")){
                            $point += $info['point']*$info['goods_num'];
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
                    "goods_all_num" => $goods_all_num,
                    "send_week" => $this->send_week,
                    "send_time" => $this->send_time,
                    "send_address" => $this->send_address,
                    "remark" => $this->remark,
                ];
                if(M($this->order_table)->where($where)->save($save)){
                    //更新库存
                    foreach($this->goods_list as $info){
                        $goods_obj = new \Yege\Goods();
                        $goods_obj->goods_id = $info['id'];
                        $stock_result = $goods_obj->updateGoodsStock(1,$info['goods_num']);
                        if($stock_result['state'] != 1){
                            //日志中记录出错信息
                            add_wrong_log("库存更新失败（将导致此次操作的库存数据回滚）：".$stock_result['message']);

                            M()->rollback();
                            $result['message'] = '系统繁忙，当前操作人数过多，请稍后再试';
                            return $result;
                        }
                    }

                    if($point > 0){
                        //减掉用户的积分
                        $point_result = [];
                        $point_obj = new \Yege\Point();
                        $point_obj->user_id = $this->user_id;
                        $point_obj->log = "由订单 ".$order_code." 消费";
                        $point_obj->remark = date("Y-m-d H:i:s",time())."生成订单 ".$order_code." ，用户使用积分：".$point;
                        $point_obj->points = -$point;
                        $point_obj->operation_tab = 'goods_consume';
                        $point_result = $point_obj->updateUserPoints();
                        if($point_result['state'] != 1){
                            //日志中记录出错信息
                            add_wrong_log("积分更新失败（将导致此次操作的积分数据回滚）：".$point_result['message']);

                            M()->rollback();
                            $result['message'] = '积分操作失败，请稍后再试';
                            return $result;
                        }
                    }

                    //添加订单日志
                    $this->order_id = $order_id;
                    $this->addOrderLog("订单生成成功。订单id：".$order_id."，订单序列号：".$order_code,"用户id：".$this->user_id);

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

    /**
     * 获取用户订单详情
     * @return array $result 结果集返回
     */
    public function getUserOrderInfo(){
        $result = [];

        $order_id = check_int($this->order_id);
        $user_id = check_int($this->user_id);
        if(!empty($order_id) && !empty($user_id)){
            //首先拿到订单数据
            $order_info = M($this->order_table)
                ->where([
                    "id"=>$order_id,
                    "user_id"=>$user_id,
                ])
                ->find();
            if(!empty($order_info['id'])){
                $order_info['state_str'] = C("STATE_ORDER_LIST")[$order_info['state']];
                $order_info['is_confirm_str'] = empty($order_info['is_confirm']) ? '未确认' : '已确认';
                $order_info['is_pay_str'] = empty($order_info['is_pay']) ? '未付款' : '已付款';
                $order_info['send_week_and_time'] = C("SHOP_SEND_WEEK_LIST")[$order_info['send_week']]['week_str']."&nbsp;&nbsp;".
                    C("SHOP_SEND_TIME_LIST")[$order_info['send_time']]['time'];

                //这个时候获取订单商品信息
                $order_goods_list = M($this->order_goods_table." as order_goods")
                    ->field([
                        "goods.id","goods.name","goods.ext_name","order_goods.price","order_goods.point","goods.describe",
                        "goods.goods_image","goods.is_shop","goods.state","attr.attr_name","order_goods.pay_type","order_goods.goods_num",
                    ])
                    ->join("left join ".C("DB_PREFIX").$this->goods_table." as goods on goods.id = order_goods.goods_id")
                    ->join("left join ".C("DB_PREFIX").$this->attr_table." as attr on attr.id = goods.attr_id")
                    ->where([
                        "order_goods.order_id" => $order_info['id'],
                    ])
                    ->order("order_goods.id ASC")
                    ->select();

                if(!empty($order_goods_list)){

                    //是否存在问题商品
                    $is_wrong = 0;

                    //数据处理
                    foreach($order_goods_list as $key => $val){
                        //图片
                        $order_goods_list[$key]['goods_image'] = "/".(empty($val['goods_image']) ? C("HOME_GOODS_DEFAULT_EMPTY_IMAGE_URL") : $val['goods_image']);
                        if($val['is_shop'] != 1 || $val['state'] != C('STATE_GOODS_NORMAL')){
                            //库存不足 、 不是上架状态 或 状态不对
                            $is_wrong = 1;
                        }
                    }

                    //判断订单存在无效商品的前置状态
                    $has_wrong_list = [
                        C("STATE_ORDER_WAIT_CONFIRM"), //待确认
                    ];

                    //判断订单是否已有提示信息的状态
                    $has_tip_list = [
                        C("STATE_ORDER_WAIT_CONFIRM"),
                        C("STATE_ORDER_WAIT_DELIVERY"),
                        C("STATE_ORDER_DELIVERY_ING"),
                        C("STATE_ORDER_WAIT_SETTLEMENT"),
                        C("STATE_ORDER_CLOSE"),
                        C("STATE_ORDER_DISSENT"),
                        C("STATE_ORDER_BACK"),
                    ];

                    $result = [
                        "order_info" => $order_info,
                        "order_goods_list" => $order_goods_list,
                        "is_wrong" => in_array($order_info['state'],$has_wrong_list) ? $is_wrong : 0,
                        "has_tip" => in_array($order_info['state'],$has_tip_list) ? 1 : 0,
                    ];

                    //有信息提示，就尝试获取提示信息
                    if($result['has_tip'] == 1){
                        $tip_message = $this->getTipMessageByOrderInfo($order_info,$is_wrong);
                        $result['tip_message'] = $tip_message['tip_message'];
                        $result['right_tip'] = $tip_message['right_tip'];
                    }

                }
            }
        }

        return $result;
    }

    /**
     * 获取订单日志列表
     */
    public function getOrderLog(){
        $log = [];

        $order_id = check_int($this->order_id);
        if(!empty($order_id)){
            $log = M($this->order_log_table)->where(["order_id"=>$order_id])->order("inputtime ASC")->select();
        }

        return $log;
    }

    /**
     * 获取用户订单列表详情
     */
    public function getUserOrderList($where = [],$page = 1,$num = 20){
        $result = [
            "list" => [],
            "count" => 0,
        ];

        $user_id = check_int($this->user_id);
        //基础参数
        $where["user_id"] = $user_id;
        $where["is_delete"] = 0;

        $limit = ($page-1)*$num.",".$num;

        $order_list = M($this->order_table)
            ->where($where)
            ->limit($limit)
            ->order("inputtime DESC")
            ->select();
        //数据处理
        foreach($order_list as $key => $val){
            $order_list[$key]['state_str'] = C("STATE_ORDER_LIST")[$val['state']];
            $order_list[$key]['is_confirm_str'] = empty($val['is_confirm']) ? '未确认' : '已确认';
            $order_list[$key]['is_pay_str'] = empty($val['is_pay']) ? '未付款' : '已付款';
            $order_list[$key]['can_delete'] = 0;
            if($val['state'] == C('STATE_ORDER_WAIT_CONFIRM') && empty($val['is_confirm']) && empty($val['is_pay'])){
                $order_list[$key]['can_delete'] = 1;
            }
        }
        $result['list'] = $order_list;

        //数量获取
        $count = M($this->order_table)
            ->where($where)
            ->count();
        $result['count'] = empty($count) ? 0 : $count;

        return $result;
    }

    /**
     * 根据订单的详情，获取对应的提示信息
     * @param array $order_info 订单详情
     * @param int $is_wrong 商品是否存在问题
     * @return array $result 提示信息返回
     */
    private function getTipMessageByOrderInfo($order_info = [],$is_wrong = 0){
        $result["tip_message"] = "订单状态数据判断失败...请刷新页面后重试。";
        $result["right_tip"] = 0;

        //提示信息
        switch($order_info['state']){
            case C("STATE_ORDER_WAIT_CONFIRM"):
                //待确认
                $result["tip_message"] = $is_wrong == 0 ?
                    "确认订单信息无误后 <span class='public_tip_color'>请用此账号所绑定的手机号码</span> 编辑短信 ‘<span class='public_tip_color'>QR".$order_info['order_code']."</span>’ 发送至手机号 <span class='public_tip_color'>".C('WEB_USE_MOBILE')."</span> 以完成订单确认。" :
                    "订单中的部分商品 <span class='public_tip_color'>已被下架或是删除</span> , 请返回 <a href='/Home/Cart/cartList' class='public_tip_color' >我的清单</a> 重新选择商品";
                $result["right_tip"] = $is_wrong == 0 ? 1 : 0;
                break;
            case C("STATE_ORDER_WAIT_DELIVERY"):
                //待发货
                $result["tip_message"] = "您的订单已被确认，请务必在 <span class='public_tip_color'>配送时间段内</span> 保持联系号码畅通，以方便配送员联系到您。";
                $result["right_tip"] = 1;
                break;
            case C("STATE_ORDER_DELIVERY_ING"):
                //配送中
                $result["tip_message"] = "配送员已在路上，请耐心等待，并 <span class='public_tip_color'>保持联系号码畅通</span>，以方便配送员联系到您。";
                $result["right_tip"] = 1;
                break;
            case C("STATE_ORDER_WAIT_SETTLEMENT"):
                //待结算
                $result["tip_message"] = "您的订单已完成，<span class='public_tip_color'>但您还未付款</span>，以至于它停留在 <span class='public_tip_color'>待结算</span> 状态，您可将订单金额直接打款至 <a href='#order_info_footer_tip' onclick='show_zfb()' class='public_tip_color public_big_font'>支付宝账号 ".C('WEB_USE_ALIPAY')."（点击显示二维码）</a> 并在备注中填写&nbsp;订单号 ‘<span class='public_tip_color'>".$order_info['order_code']."</span>’ 以等待订单确认结算。若有疑问 可以联系客服 QQ ".C("WEB_USE_QQ")."。";
                $result["right_tip"] = 0;
                break;
            case C("STATE_ORDER_CLOSE"):
                //已关闭
                $result["tip_message"] = "您的订单 因：<span class='public_tip_color'>".$order_info['operation_remark']."</span>，已被关闭。";
                $result["right_tip"] = 0;
                break;
            case C("STATE_ORDER_DISSENT"):
                //有异议
                $result["tip_message"] = "您已对这张订单提出异议，我们会尽快为您解决，请耐心等待，若有疑问 可以联系客服 QQ ".C("WEB_USE_QQ")."。";
                $result["right_tip"] = 0;
                break;
            case C("STATE_ORDER_BACK"):
                //已退款
                $result["tip_message"] = "您的订单 因：<span class='public_tip_color'>".$order_info['operation_remark']."</span>，已退款。若有疑问 可以联系客服 QQ ".C("WEB_USE_QQ")."。";
                $result["right_tip"] = 0;
                break;
        }

        return $result;
    }

    /**
     * 删除掉用户指定的无效订单
     * @param string $order_code 订单序列号
     */
    public function deleteInvalidOrder($order_code = ""){
        //尝试用这个订单序列号搜索出订单
        $order_code = check_str($order_code);
        $order_info = M($this->order_table)
            ->where([
                "order_code" => $order_code,
                "user_id" => $this->user_id,
                "is_delete" => 0,
            ])->find();

        //订单未确认 用户未确认 用户未付款
        if(!empty($order_info) && $order_info['state'] == C('STATE_ORDER_WAIT_CONFIRM') && empty($order_info['is_confirm']) && empty($order_info['is_pay'])){
            //拿到关联商品
            $goods_list = M($this->order_goods_table)
                ->where([
                    "order_id" => $order_info['id'],
                ])->select();
            //将订单标记为删除
            M($this->order_table)->where([
                "id" => $order_info['id'],
                "order_code" => $order_code,
                "user_id" => $this->user_id,
            ])->save(["is_delete"=>1,"delete_time"=>time()]);
            //恢复库存
            foreach($goods_list as $info){
                $goods_result = [];
                $goods_obj = new Goods();
                $goods_obj->goods_id = $info['goods_id'];
                $goods_result = $goods_obj->updateGoodsStock(2,$info['goods_num']);
                if($goods_result['state'] != 1){
                    //日志中记录出错信息
                    add_wrong_log("订单id：".$order_info['id']."，订单被取消时恢复库存失败：".$goods_result['message']);
                }
            }

            //订单涉及到积分就恢复积分
            if($order_info['point'] > 0){
                //恢复用户的积分
                $point_result = [];
                $point_obj = new \Yege\Point();
                $point_obj->user_id = $order_info['user_id'];
                $point_obj->log = "取消订单 ".$order_code." ，恢复积分";
                $point_obj->remark = date("Y-m-d H:i:s",time())."取消订单 ".$order_code." ，用户恢复积分：".$order_info['point'];
                $point_obj->points = $order_info['point'];
                $point_obj->operation_tab = 'order_cancel';
                $point_result = $point_obj->updateUserPoints();
                if($point_result['state'] != 1){
                    //日志中记录出错信息
                    add_wrong_log("订单id：".$order_info['id']."，订单被取消时积分更新失败：".$point_result['message']);
                }
            }

            //添加订单日志
            $this->order_id = $order_info['id'];
            $this->addOrderLog("订单id：".$order_info['id']."，被删除","用户id：".$order_info['user_id']);

        }
    }

    /**
     * 订单日志
     * @param string $log 日志内容
     * @param string $relevant 相关内容
     */
    public function addOrderLog($log = "",$relevant = ""){
        $order_id = check_int($this->order_id);
        $log = check_str($log);
        $relevant = check_str($relevant);
        if(!empty($order_id)){
            $add = [
                "order_id" => $order_id,
                "log" => $log,
                "relevant" => $relevant,
                "inputtime" => time(),
            ];
            if(!M($this->order_log_table)->add($add)){
                add_wrong_log("订单id：".$order_id."，添加订单日志失败，涉及参数 log：".$log."，relevant：".$relevant);
            }
        }
    }

    /**
     * 订单状态更新统一入口
     * @param string $action 动作
     * @return array $result 结果返回
     */
    public function updateOrderStateUnifiedInlet($action = ""){
        $result = ['state'=>0,'message'=>'未知错误'];

        //首先用订单id获取订单详情
        $order_id = check_int($this->order_id);
        $order_info = M($this->order_table)->where(["id"=>$order_id])->find();
        if(!empty($order_info['id'])){
            if(empty($order_info['is_delete'])){
                $this->order_info = $order_info;
                //根据动作去不同的方法
                $temp_result = [];
                switch($action){
                    case 'confirm_order': //确认订单
                        $temp_result = $this->updateOrderStateConfirmOrder();
                        if($temp_result['state'] == 1){
                            $result['state'] = 1;
                            $result['message'] = "操作成功";
                        }else{
                            $result['message'] = $temp_result['message'];
                        }
                        break;
                    case 'confirm_pay': //确认付款
                        $temp_result = $this->updateOrderStateConfirmPay();
                        if($temp_result['state'] == 1){
                            $result['state'] = 1;
                            $result['message'] = "操作成功";
                        }else{
                            $result['message'] = $temp_result['message'];
                        }
                        break;
                    case 'to_delivery': //待发货转至配送中
                        $temp_result = $this->updateOrderStateToDelivery();
                        if($temp_result['state'] == 1){
                            $result['state'] = 1;
                            $result['message'] = "操作成功";
                        }else{
                            $result['message'] = $temp_result['message'];
                        }
                        break;
                    case 'success_order': //配送中的订单完成
                        $temp_result = $this->updateOrderStateSuccessOrder();
                        if($temp_result['state'] == 1){
                            $result['state'] = 1;
                            $result['message'] = "操作成功";
                        }else{
                            $result['message'] = $temp_result['message'];
                        }
                        break;
                    case 'close_order': //关闭订单
                        $temp_result = $this->updateOrderStateCloseOrder();
                        if($temp_result['state'] == 1){
                            $result['state'] = 1;
                            $result['message'] = "操作成功";
                        }else{
                            $result['message'] = $temp_result['message'];
                        }
                        break;
                    case 'refund_order': //退款订单
                        $temp_result = $this->updateOrderStateRefundOrder();
                        if($temp_result['state'] == 1){
                            $result['state'] = 1;
                            $result['message'] = "操作成功";
                        }else{
                            $result['message'] = $temp_result['message'];
                        }
                        break;
                    default:
                        $result['message'] = '没能找到指定动作';
                }
            }else{
                $result['message'] = '不能操作已被删除的订单';
            }
        }else{
            $result['message'] = '未能正确获取订单信息';
        }

        return $result;
    }

    /**
     * 订单状态更新 - 确认订单
     * @return array $result 结果返回
     */
    private function updateOrderStateConfirmOrder(){
        $result = ['state'=>0,'message'=>'未知错误'];
        //前提条件 订单状态为待确认、用户确认状态为未确认
        $order_info = $this->order_info;
        if(!empty($order_info['id']) && $order_info['state'] == C("STATE_ORDER_WAIT_CONFIRM") && $order_info['is_confirm'] == 0){
            //拿到用户信息
            $user_obj = new \Yege\User();
            $user_obj->user_id = $order_info['user_id'];
            $user_info = $user_obj->getUserInfo();
            if($user_info['state'] == 1 && !empty($user_info['result']['id'])){
                $user_info = $user_info['result'];
                //数据更新
                $save = [
                    "state" => C("STATE_ORDER_WAIT_DELIVERY"), //状态变为待发货
                    "is_confirm" => 1,
                    "confirm_mobile" => $user_info['mobile'],
                    "confirm_time" => time(),
                    "updatetime" => time(),
                ];
                $where = [
                    "id" => $order_info['id'],
                    "user_id" => $user_info['id'],
                ];
                if(M($this->order_table)->where($where)->save($save)){
                    $this->addOrderLog("订单被确认通过");
                    add_user_message($user_info['id'],"您的订单 ".$order_info['order_code']." 被确认通过，订单正处于待发货状态。",1);
                    $result['state'] = 1;
                    $result['message'] = '操作成功';
                }else{
                    $result['message'] = '确认订单失败';
                }
            }else{
                $result['message'] = '用户信息获取失败';
            }
        }else{
            $result['message'] = '订单状态错误';
        }
        return $result;
    }

    /**
     * 订单状态更新 - 确认付款
     * 注：当订单是待确认状态，一但确认付款就会跑updateOrderStateConfirmOrder逻辑
     * @return array $result 结果返回
     */
    private function updateOrderStateConfirmPay(){
        $result = ['state'=>0,'message'=>'未知错误'];
        //前提条件 确认付款状态为未确认
        $order_info = $this->order_info;
        if(!empty($order_info['id']) && $order_info['is_pay'] == 0){
            //数据更新
            $save = [
                "is_pay" => 1,
                "confirm_pay_time" => time(),
                "updatetime" => time(),
            ];
            $where = [
                "id" => $order_info['id'],
            ];
            if(M($this->order_table)->where($where)->save($save)){
                $this->addOrderLog("订单被确认已付款");
                add_user_message($order_info['user_id'],"您的订单 ".$order_info['order_code']." 被确认已付款。",1);
                if($order_info['state'] == C("STATE_ORDER_WAIT_CONFIRM")){
                    //待确认状态的订单会跑一边updateOrderStateConfirmOrder逻辑，但无论结果
                    $this->updateOrderStateConfirmOrder();
                }
                $result['state'] = 1;
                $result['message'] = '操作成功';
            }else{
                $result['message'] = '确认付款失败';
            }
        }else{
            $result['message'] = '订单状态错误';
        }
        return $result;
    }

    /**
     * 订单状态更新 - 待发货订单转至配送中
     * @return array $result 结果返回
     */
    private function updateOrderStateToDelivery(){
        $result = ['state'=>0,'message'=>'未知错误'];
        //前提条件 订单状态为待发货
        $order_info = $this->order_info;
        if(!empty($order_info['id']) && $order_info['state'] == C("STATE_ORDER_WAIT_DELIVERY")){
            //数据更新
            $save = [
                "state" => C("STATE_ORDER_DELIVERY_ING"),
                "updatetime" => time(),
            ];
            $where = [
                "id" => $order_info['id'],
            ];
            if(M($this->order_table)->where($where)->save($save)){
                $this->addOrderLog("订单已开始配送");
                add_user_message($order_info['user_id'],"您的订单 ".$order_info['order_code']." 已开始配送。",1);
                $result['state'] = 1;
                $result['message'] = '操作成功';
            }else{
                $result['message'] = '确认付款失败';
            }
        }else{
            $result['message'] = '订单状态错误';
        }
        return $result;
    }

    /**
     * 订单状态更新 - 完成订单
     * @return array $result 结果返回
     */
    private function updateOrderStateSuccessOrder(){
        $result = ['state'=>0,'message'=>'未知错误'];
        //前提条件 订单在配送中状态 或者是 待结算状态
        $order_info = $this->order_info;
        if(!empty($order_info['id']) && ($order_info['state'] == C("STATE_ORDER_DELIVERY_ING") || $order_info['state'] == C("STATE_ORDER_WAIT_SETTLEMENT"))){
            //默认是已付款状态下的订单
            $log = "订单已完成";
            $user_log = "您的订单 ".$order_info['order_code']." 已完成。";
            $save = [
                //已付款的情况下是完成状态
                "state" => C("STATE_ORDER_SUCCESS"),
                "updatetime" => time(),
            ];
            if($order_info['state'] == C("STATE_ORDER_DELIVERY_ING")){
                //配送中的订单逻辑

                //判断付款状态
                if($order_info['is_pay'] != 1){
                    //未付款的订单是待结算状态
                    $save['state'] = C("STATE_ORDER_WAIT_SETTLEMENT");
                    $log = "订单进入待结算状态";
                    $user_log = "订单 ".$order_info['order_code']." 进入待结算状态，请及时为订单付款。付款方式可在 <a href='/Home/Order/orderInfo/order_id/".$order_info['id']."'>订单详情</a> 中查看。";
                }else{
                    //这种情况下 可以为用户增加信用分
                    $user_obj = new User();
                    $user_obj->user_id = $order_info['user_id'];
                    $user_obj->changeUserCredit(1);
                }
            }elseif($order_info['state'] == C("STATE_ORDER_WAIT_SETTLEMENT")){
                //待结算中的订单

                //判断付款状态
                if($order_info['is_pay'] != 1){
                    $result['message'] = '订单未被确认结算，无法完成订单';
                    return $result;
                }
            }else{
                $result['message'] = '错误的订单状态';
                return $result;
            }

            $where = [
                "id" => $order_info['id'],
            ];

            //订单完成时间
            if($save["state"] == C("STATE_ORDER_SUCCESS")){
                $save['success_time'] = time();
            }

            if(M($this->order_table)->where($where)->save($save)){
                $this->addOrderLog($log);
                add_user_message($order_info['user_id'],$user_log,1);

                //若行动状态被变更至已完成，就自动记录一笔资金流水
                if($save["state"] == C("STATE_ORDER_SUCCESS")){
                    $Fund = new \Yege\Fund();
                    $Fund->fund = $order_info['pay_price'];
                    $Fund->remark = "订单 ".$order_info['order_code']." 完成，获取收益：".$order_info['pay_price'];
                    $Fund->addFundLog();
                }

                $result['state'] = 1;
                $result['message'] = '操作成功';
            }else{
                $result['message'] = '确认付款失败';
            }
        }else{
            $result['message'] = '订单状态错误';
        }
        return $result;
    }

    /**
     * 订单状态更新 - 关闭订单
     * @return array $result 结果返回
     */
    private function updateOrderStateCloseOrder(){
        $result = ['state'=>0,'message'=>'未知错误'];
        //前提条件 列举可以进入已关闭状态的订单状态
        $state_list = [
            C("STATE_ORDER_WAIT_CONFIRM"), //待确认
            C("STATE_ORDER_WAIT_DELIVERY"), //待发货
            C("STATE_ORDER_DELIVERY_ING"), //配送中
            C("STATE_ORDER_WAIT_SETTLEMENT"), //待结算
            C("STATE_ORDER_DISSENT"), //有异议
        ];
        $order_info = $this->order_info;
        if(!empty($order_info['id']) && in_array($order_info['state'],$state_list) && $order_info['is_pay'] != 1){
            //操作备注不能为空
            $operation_remark = check_str($this->operation_remark);
            if(!empty($operation_remark)){
                //拿到用户信息
                $user_obj = new \Yege\User();
                $user_obj->user_id = $order_info['user_id'];
                $user_info = $user_obj->getUserInfo();
                if($user_info['state'] == 1 && !empty($user_info['result']['id'])){
                    $user_info = $user_info['result'];
                    //数据更新
                    $save = [
                        "state" => C("STATE_ORDER_CLOSE"), //状态变为已关闭
                        "operation_remark" => $operation_remark,
                        "updatetime" => time(),
                    ];
                    $where = [
                        "id" => $order_info['id'],
                        "user_id" => $user_info['id'],
                    ];
                    if(M($this->order_table)->where($where)->save($save)){
                        $this->addOrderLog("订单因为：".$operation_remark." 被关闭");
                        add_user_message($user_info['id'],"您的订单 ".$order_info['order_code']." 因：“".$operation_remark."”，被关闭。",1);

                        //仅恢复订单库存
                        $this->onlyRecoverStock();

                        $result['state'] = 1;
                        $result['message'] = '操作成功';
                    }else{
                        $result['message'] = '确认订单失败';
                    }
                }else{
                    $result['message'] = '用户信息获取失败';
                }
            }else{
                $result['message'] = '操作备注不能为空';
            }
        }else{
            $result['message'] = '订单状态错误';
        }
        return $result;
    }

    /**
     * 订单状态更新 - 退款订单
     * @return array $result 结果返回
     */
    private function updateOrderStateRefundOrder(){
        $result = ['state'=>0,'message'=>'未知错误'];
        //前提条件 已确认付款
        $order_info = $this->order_info;
        if(!empty($order_info['id']) && $order_info['is_pay'] == 1 && $order_info['state'] != C("STATE_ORDER_BACK")){
            //操作备注不能为空
            $operation_remark = check_str($this->operation_remark);
            if(!empty($operation_remark)){
                //拿到用户信息
                $user_obj = new \Yege\User();
                $user_obj->user_id = $order_info['user_id'];
                $user_info = $user_obj->getUserInfo();
                if($user_info['state'] == 1 && !empty($user_info['result']['id'])){
                    $user_info = $user_info['result'];
                    //数据更新
                    $save = [
                        "state" => C("STATE_ORDER_BACK"), //状态变为已退款
                        "operation_remark" => $operation_remark,
                        "updatetime" => time(),
                    ];
                    $where = [
                        "id" => $order_info['id'],
                        "user_id" => $user_info['id'],
                    ];
                    if(M($this->order_table)->where($where)->save($save)){
                        $this->addOrderLog("订单因为：".$operation_remark." 已退款");
                        add_user_message($user_info['id'],"您的订单 ".$order_info['order_code']." 因：“".$operation_remark."”，已退款。",1);

                        //仅恢复订单库存
                        $this->onlyRecoverStock();

                        $result['state'] = 1;
                        $result['message'] = '操作成功';
                    }else{
                        $result['message'] = '确认订单失败';
                    }
                }else{
                    $result['message'] = '用户信息获取失败';
                }
            }else{
                $result['message'] = '操作备注不能为空';
            }
        }else{
            $result['message'] = '订单状态错误';
        }
        return $result;
    }

    /**
     * 仅恢复订单库存
     */
    private function onlyRecoverStock(){
        //尝试用这个订单序列号搜索出订单
        $order_id = check_int($this->order_id);
        $order_info = M($this->order_table)
            ->where([
                "id" => $order_id,
            ])->find();

        //订单已关闭 或 已退款
        if(!empty($order_info) && ($order_info['state'] == C('STATE_ORDER_CLOSE') || $order_info['state'] == C('STATE_ORDER_BACK'))){
            //拿到关联商品
            $goods_list = M($this->order_goods_table)
                ->where([
                    "order_id" => $order_id,
                ])->select();
            //恢复库存
            foreach($goods_list as $info){
                $goods_result = [];
                $goods_obj = new Goods();
                $goods_obj->goods_id = $info['goods_id'];
                $goods_result = $goods_obj->updateGoodsStock(2,$info['goods_num']);
                if($goods_result['state'] != 1){
                    //日志中记录出错信息
                    add_wrong_log("订单id：".$order_id."，订单仅恢复库存时失败：".$goods_result['message']);
                }
            }

            //添加订单日志
            $this->order_id = $order_info['id'];
            $this->addOrderLog("订单id：".$order_id."，仅恢复库存","用户id：".$order_info['user_id']);

        }
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
        $result['all'] = [ //总体统计
            "all" => 0,
            "success" => 0,
            "close" => 0,
            "refund" => 0,
            "pay_price" => 0,
            "success_price" => 0,
            "success_point" => 0,
        ];

        //获取订单表中的全部数据
        $all = [];
        $all = M($this->order_table)
            ->field(["id","pay_price","point","state","inputtime"])
            ->where(["is_delete"=>0])
            ->order("inputtime ASC")
            ->select();

        //总体统计
        foreach($all as $val){
            //总订单
            $result['all']["all"] ++;
            //总订单金额
            $result['all']["pay_price"] += $val['pay_price'];
            if($val['state'] == C("STATE_ORDER_SUCCESS")){
                //成功订单
                $result['all']["success"] ++;
                //总成功金额
                $result['all']["success_price"] += $val['pay_price'];
                //总成功积分
                $result['all']["success_point"] += $val['point'];
            }elseif($val['state'] == C("STATE_ORDER_CLOSE")){
                //关闭订单
                $result['all']["close"] ++;
            }elseif($val['state'] == C("STATE_ORDER_BACK")){
                //退款订单
                $result['all']["refund"] ++;
            }
        }

        switch($level){
            case 1: //年列表统计($time参数无效)
                //以2016年为起点的3年
                $result['statistics'] = [
                    "2016" => [
                        "all" => 0,
                        "success" => 0,
                        "close" => 0,
                        "refund" => 0,
                        "pay_price" => 0,
                        "success_price" => 0,
                        "success_point" => 0,
                    ],"2017" => [
                        "all" => 0,
                        "success" => 0,
                        "close" => 0,
                        "refund" => 0,
                        "pay_price" => 0,
                        "success_price" => 0,
                        "success_point" => 0,
                    ], "2018" => [
                        "all" => 0,
                        "success" => 0,
                        "close" => 0,
                        "refund" => 0,
                        "pay_price" => 0,
                        "success_price" => 0,
                        "success_point" => 0,
                    ],
                ];

                //开始统计
                foreach($all as $val){

                    $temp_time = date("Y",$val['inputtime']);
                    if(empty($result['statistics'][$temp_time])){
                        $result['statistics'][$temp_time] = [
                            "all" => 0,
                            "success" => 0,
                            "close" => 0,
                            "refund" => 0,
                            "pay_price" => 0,
                            "success_price" => 0,
                            "success_point" => 0,
                        ];
                    }
                    //总订单
                    $result['statistics'][$temp_time]["all"] ++;
                    //总订单金额
                    $result['statistics'][$temp_time]["pay_price"] += $val['pay_price'];
                    if($val['state'] == C("STATE_ORDER_SUCCESS")){
                        //成功订单
                        $result['statistics'][$temp_time]["success"] ++;
                        //总成功金额
                        $result['statistics'][$temp_time]["success_price"] += $val['pay_price'];
                        //总成功积分
                        $result['statistics'][$temp_time]["success_point"] += $val['point'];
                    }elseif($val['state'] == C("STATE_ORDER_CLOSE")){
                        //关闭订单
                        $result['statistics'][$temp_time]["close"] ++;
                    }elseif($val['state'] == C("STATE_ORDER_BACK")){
                        //退款订单
                        $result['statistics'][$temp_time]["refund"] ++;
                    }
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
                        "all" => 0,
                        "success" => 0,
                        "close" => 0,
                        "refund" => 0,
                        "pay_price" => 0,
                        "success_price" => 0,
                        "success_point" => 0,
                        'month' => $month_str,
                    ];
                }

                $temp = $where = [];
                $where['inputtime'][] = ['egt',strtotime($time."-01-01 00:00:00")];
                $where['inputtime'][] = ['lt',strtotime(($time+1)."-01-01 00:00:00")];
                $where['is_delete'] = 0;

                $temp = M($this->order_table)
                    ->field(["id","pay_price","point","state","inputtime"])
                    ->where($where)
                    ->order("inputtime ASC")
                    ->select();
                //开始统计
                foreach($temp as $val){
                    $temp_time = "month_".date("m",$val['inputtime']);
                    if(empty($result['statistics'][$temp_time])){
                        $result['statistics'][$temp_time] = [
                            "all" => 0,
                            "success" => 0,
                            "close" => 0,
                            "refund" => 0,
                            "pay_price" => 0,
                            "success_price" => 0,
                            "success_point" => 0,
                            'month' => date("m",$val['inputtime'])
                        ];
                    }

                    //总订单
                    $result['statistics'][$temp_time]["all"] ++;
                    //总订单金额
                    $result['statistics'][$temp_time]["pay_price"] += $val['pay_price'];
                    if($val['state'] == C("STATE_ORDER_SUCCESS")){
                        //成功订单
                        $result['statistics'][$temp_time]["success"] ++;
                        //总成功金额
                        $result['statistics'][$temp_time]["success_price"] += $val['pay_price'];
                        //总成功积分
                        $result['statistics'][$temp_time]["success_point"] += $val['point'];
                    }elseif($val['state'] == C("STATE_ORDER_CLOSE")){
                        //关闭订单
                        $result['statistics'][$temp_time]["close"] ++;
                    }elseif($val['state'] == C("STATE_ORDER_BACK")){
                        //退款订单
                        $result['statistics'][$temp_time]["refund"] ++;
                    }
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
                        "all" => 0,
                        "success" => 0,
                        "close" => 0,
                        "refund" => 0,
                        "pay_price" => 0,
                        "success_price" => 0,
                        "success_point" => 0,
                        "day" => $day_str,
                    ];
                }

                $temp = $where = [];
                $where['inputtime'][] = ['egt',strtotime($time."-01 00:00:00")];
                $where['inputtime'][] = ['lt',(strtotime($time."-01 00:00:00")+($day*24*60*60))];
                $where['is_delete'] = 0;

                $temp = M($this->order_table)
                    ->field(["id","pay_price","point","state","inputtime"])
                    ->where($where)
                    ->order("inputtime ASC")
                    ->select();

                //开始统计
                foreach($temp as $val){
                    $temp_time = "day_".date("d",$val['inputtime']);
                    if(empty($result['statistics'][$temp_time])){
                        $result['statistics'][$temp_time] = [
                            "all" => 0,
                            "success" => 0,
                            "close" => 0,
                            "refund" => 0,
                            "pay_price" => 0,
                            "success_price" => 0,
                            "success_point" => 0,
                            "day" => date("d",$val['inputtime']),
                        ];
                    }

                    //总订单
                    $result['statistics'][$temp_time]["all"] ++;
                    //总订单金额
                    $result['statistics'][$temp_time]["pay_price"] += $val['pay_price'];
                    if($val['state'] == C("STATE_ORDER_SUCCESS")){
                        //成功订单
                        $result['statistics'][$temp_time]["success"] ++;
                        //总成功金额
                        $result['statistics'][$temp_time]["success_price"] += $val['pay_price'];
                        //总成功积分
                        $result['statistics'][$temp_time]["success_point"] += $val['point'];
                    }elseif($val['state'] == C("STATE_ORDER_CLOSE")){
                        //关闭订单
                        $result['statistics'][$temp_time]["close"] ++;
                    }elseif($val['state'] == C("STATE_ORDER_BACK")){
                        //退款订单
                        $result['statistics'][$temp_time]["refund"] ++;
                    }

                }
                break;
        }

        ksort($result['statistics']);

        return $result;
    }


}
