<?php
/**
 * 全站参数配置表相关后台Model
 * by wuxuye 2016-07-20
 *
 * 方法提供
 *
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class ParamModel extends ViewModel{

	private $param_table = '';

	protected function _initialize(){
		$this->param_table = C("TABLE_NAME_PARAM");
	}



}
