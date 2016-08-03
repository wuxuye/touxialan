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
			$data[$key]['goods_image'] = "/".(empty($val['goods_image']) ? C("HOME_GOODS_DEFAULT_EMPTY_IMAGE_URL") : $val['goods_image']);
			$data[$key]['price'] = empty($val['can_price']) ? '-' : $val['price'];
			$data[$key]['can_select'] = (!empty($val['can_price']) && !empty($val['can_point'])) ? 1 : 0;
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

}
