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

	/**
	 * 添加规则
	 * @param array $rule_array 规则数组
	 * @return array $result 结果返回
	 */
	public function addRule($rule_array = []){
		$result = ['state'=>0,'message'=>'未知错误'];

		$add = [];
		$add['rule_name'] = check_str($rule_array['rule_name']);
		if(!empty($add['rule_name'])){
			$add['rule_key'] = check_str($rule_array['rule_key']);
			if(!empty($add['rule_key'])){
				$add['rule_value'] = check_str($rule_array['rule_value']);
				$add['is_json'] = check_int($rule_array['is_json']);
				if($add['is_json'] == 1){
					//做特殊解析
					$add['rule_value'] = $this->strToJson($add['rule_value']);
					$temp = json_decode($add['rule_value'],true);
				}else{
					$temp = $add['rule_value'];
				}
				if(!empty($temp)){
					$add['rule_remark'] = check_str($rule_array['rule_remark']);
					//添加数据
					$add['inputtime'] = $add['updatetime'] = time();
					if(M($this->web_data_rule_table)->add($add)){
						$result['state'] = 1;
						$result['message'] = "数据添加成功";
					}else{
						$result['message'] = "数据添加失败";
					}
				}else{
					$result['message'] = "规则键值 未能成功解析";
				}
			}else{
				$result['message'] = "规则键名必须填写";
			}
		}else{
			$result['message'] = "规则显示名必须填写";
		}

		return $result;
	}

	/**
	 * 删除指定规则
	 * @param int $rule_id 规则id
	 */
	public function deleteRule($rule_id = 0){
		$where = [
			'id' => $rule_id,
		];
		$save = [
			'is_delete' => 1,
			'updatetime' => time(),
		];
		M($this->web_data_rule_table)->where($where)->save($save);
	}

	/**
	 * 根据规则将字符串变成json串
	 * @param string $str 待解析字符串
	 * @return array $result 结果返回
	 */
	public function strToJson($str = ""){
		$result = "";

		if(!empty($str)){
			$json_array = [];
			$temp = explode(";",$str);
			foreach($temp as $info){
				$key_val_temp = explode(",",$info);
				if(!empty($key_val_temp[0]) && !empty($key_val_temp[1])){
					$key_val_temp[0] = check_str($key_val_temp[0]);
					$key_val_temp[1] = check_str($key_val_temp[1]);
					$json_array[$key_val_temp[0]] = $key_val_temp[1];
				}
			}
		}

		return $result;
	}

}
