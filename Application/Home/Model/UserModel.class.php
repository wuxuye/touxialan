<?php
/**
 * 用户信息相关前台Model
 * by wuxuye 2016-05-30
 */
namespace Home\Model;
use Think\Model\ViewModel;

class UserModel extends ViewModel{

	private $user_table = '';

	protected function _initialize(){
		$this->user_table = C("TABLE_NAME_USER");
	}

	/**
	 * 增加收货地址
	 * @param int $user_id 用户id
	 * @param array $param 相关参数
	 * @return $result 结果返回
	 */
	public function addReceiptAddress($user_id = 0,$param = []){
		$result = ['state'=>0,'message'=>'未知错误'];

		$address = $where = [];


	}

}
