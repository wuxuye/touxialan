<?php
namespace Yege;

/**
 * 用户清单类
 *
 * 方法提供
 * getGoodsInfoByUser       获取指定用户的清单信息
 * addGoodsToUserCart       添加商品到用户的清单中
 */

class Cart{

    //提供于外面赋值或读取的相关参数
    public $user_id = 0; //用户id
    public $goods_id = 0; //商品id

    private $attr_table = ""; //商品属性表
    private $cart_table = ""; //用户清单表（购物车、收藏）
    private $goods_table = ""; //商品信息表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->attr_table = C("TABLE_NAME_ATTR");
        $this->cart_table = C("TABLE_NAME_CART");
        $this->goods_table = C("TABLE_NAME_GOODS");
    }

    /**
     * 获取指定用户的清单信息
     * @return array $result 结果返回
     */
    public function getGoodsInfoByUser(){
        $result = [];

        $user_id = intval($this->user_id);
        $result = M($this->cart_table." as cart")
            ->field([
                "goods.id","goods.name","goods.ext_name","goods.price","goods.point","goods.can_price",
                "goods.can_point","goods.describe","goods.goods_image", "attr.attr_name",
            ])
            ->join("left join ".C("DB_PREFIX").$this->goods_table." as goods on goods.id = cart.goods_id")
            ->join("left join ".C("DB_PREFIX").$this->attr_table." as attr on attr.id = goods.attr_id")
            ->where([
                "cart.user_id" => $user_id
            ])
            ->order("cart.inputtime DESC,cart.last_time DESC")
            ->select();

        return $result;
    }

    /**
     * 添加商品到用户的清单中
     * @return array $result 结果返回
     */
    public function addGoodsToUserCart(){
        $result = ['state' => 0,'message' => '未知错误'];

        $user_id = intval($this->user_id);
        $goods_id = intval($this->goods_id);
        if(!empty($user_id)){
            //商品详情获取
            $Goods = new \Yege\Goods();
            $Goods->goods_id = $goods_id;
            $goods_info = $Goods->getGoodsInfo();
            if($goods_info['state'] == 1 && !empty($goods_info['result']['id'])){
                $goods_info = $goods_info['result'];
                if($goods_info['state'] == C("STATE_GOODS_NORMAL") && $goods_info['is_shop'] == C("STATE_GOODS_SHELVE")){
                    //存在性检验
                    $cart_info = [];
                    $cart_info = M($this->cart_table)->where(["user_id"=>$user_id,"goods_id"=>$goods_id])->find();
                    if(empty($cart_info)){
                        //验证通过将数据添加至清单表
                        $add = [];
                        $add['user_id'] = $user_id;
                        $add['goods_id'] = $goods_id;
                        $add['inputtime'] = time();
                        if(M($this->cart_table)->add($add)){
                            $result['state'] = 1;
                            $result['message'] = "操作成功";
                        }else{
                            $result['message'] = "添加操作失败，请稍后重试";
                        }
                    }else{
                        $result['message'] = "此商品已存在与您的清单中";
                    }
                }else{
                    $result['message'] = "该商品已被下架或删除";
                }
            }else{
                $result['message'] = "商品数据未能正确获取";
            }
        }else{
            $result['message'] = "未能获取用户信息";
        }

        return $result;
    }


}
