<?php
/**
 * 用户清单相关前台Model
 * by wuxuye 2016-08-02
 */
namespace Home\Model;
use Think\Model\ViewModel;

class CartModel extends ViewModel{

	private $cart_table = '';

	protected function _initialize(){
		$this->cart_table = C("TABLE_NAME_CART");
	}

	/**
	 * 获取清单详情
	 * @param int $cart_id 清单id
	 * @return array $result 结果返回
	 */
	public function getCartInfo($cart_id = 0){
		$result = [];
		$info = M($this->cart_table)->where([
			"id" => $cart_id,
		])->find();
		if(!empty($info['id'])){
			$result = $info;
		}
		return $result;
	}

	/**
	 * 获取清单列表
	 * @param int $user_id 用户id
	 * @return array $result 结果返回
	 */
	public function getCartList($user_id = 0){
		$result = [];

		$Cart = new \Yege\Cart();
		$Cart->user_id = $user_id;
		$data = $Cart->getGoodsInfoByUser();

		//数据处理
		foreach($data as $key => $val){
			//图片
			$data[$key]['goods_image'] = "/".(empty($val['goods_image']) ? C("HOME_GOODS_DEFAULT_EMPTY_IMAGE_URL") : $val['goods_image']);
			//价格
			$data[$key]['price'] = empty($val['can_price']) ? '-' : $val['price'];
			$data[$key]['price_can_select'] = (!empty($val['can_price']) && !empty($val['can_point'])) ? 1 : 0;
			//库存
			$data[$key]['stock'] = $val['stock'] = check_int($val['stock']);
			$data[$key]['is_stock'] = empty($val['stock']) ? 0 : 1; //是否有库存
			$data[$key]['less_stock'] = ($val['stock'] >= $val['goods_num']) ? 1 : 0; //库存是否充足
			//是否可选择判断
			$data[$key]['goods_can_select'] = 1;
			if(empty($val['stock']) || $val['is_shop'] != 1 || $val['state'] != C('STATE_GOODS_NORMAL')){
				//库存为空 、 不是上架状态 或 状态不对
				$data[$key]['goods_can_select'] = 0;
			}
		}

		$result = $data;

		return $result;
	}

	/**
	 * 商品正确性检验
	 * @param int $goods_id 商品id
	 * @return array $result 结果返回
	 */
	public function checkCartGoods($goods_id = 0){
		$result = ['state'=>0,'message'=>'未知错误'];



		return $result;
	}

	/**
	 * 删除单个清单商品
	 * @param int $cart_id 清单商品id
	 */
	public function deleteCart($cart_id = 0){
		M($this->cart_table)->where([
			"id" => $cart_id,
		])->delete();
	}

	/**
	 * 删除用户清单中的所有商品
	 * @param int $user_id 用户id
	 */
	public function deleteAllCart($user_id = 0){
		M($this->cart_table)->where([
			"user_id" => $user_id,
		])->delete();
	}

}
