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


}
