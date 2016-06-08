<?php
namespace Yege;

/**
 * 购物车类类
 *
 * 方法提供
 *
 */

class Cart{

    //提供于外面赋值或读取的相关参数
    public $user_id = 0; //用户id
    public $goods_id = 0; //商品id

    private $cart_table = ""; //购物车信息表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->cart_table = C("TABLE_NAME_CART");
    }



}
