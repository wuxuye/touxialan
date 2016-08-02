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
	 */
	public function getCartList(){
		$result = ["state"=>0,"message"=>"未知错误","data"=>[]];



		return $result;
	}

}
