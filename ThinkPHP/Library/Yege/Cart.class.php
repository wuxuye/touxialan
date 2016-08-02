<?php
namespace Yege;

/**
 * 用户清单类
 *
 * 方法提供
 *
 */

class Cart{

    //提供于外面赋值或读取的相关参数
    public $user_id = 0; //用户id
    public $goods_id = 0; //商品id

    private $cart_table = ""; //用户清单表（购物车、收藏）

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->cart_table = C("TABLE_NAME_CART");
    }



}
