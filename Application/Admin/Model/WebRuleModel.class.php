<?php
/**
 * 前台规则相关后台Model
 * by wuxuye 2016-07-05
 *
 * 提供方法
 * getRuleList			前台规则数据列表获取
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class WebRuleModel extends ViewModel{

	private $web_data_rule_table = '';

	protected function _initialize(){
		$this->web_data_rule_table = C("TABLE_NAME_WEB_DATA_RULE");
	}

	/**
	 * 前台规则数据列表获取
	 * @param array $where where条件数组
	 * @return array $result 结果返回
	 */
	public function getRuleList($where = []){
		$result = ['state'=>0,'message'=>'未知错误','list'=>[]];

		//基本条件
		$where['is_delete'] = 0;

		//列表信息获取
		$list = [];
		$list = M($this->web_data_rule_table)->where($where)->order("id DESC")->select();

		//列表数据处理
		foreach($list as $key => $val){

		}

		$result['list'] = $list;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

}
