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
			if(!empty($val['is_json'])){
				$list[$key]['rule_value_str'] = print_r(json_decode($val['rule_value'],true),true);
			}else{
				$list[$key]['rule_value_str'] = $val['rule_value'];
			}
		}

		$result['list'] = $list;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

	/**
	 * 规则详情获取
	 * @param int $id 规则id
	 * @return array $result 结果返回
	 */
	public function getRuleInfo($id = 0){
		$info = [];

		if(!empty($id)){
			$info = M($this->web_data_rule_table)->where(['id'=>$id])->find();
			//反解析json串
			if(!empty($info['is_json'])){
				$info['rule_value'] = $this->jsonToStr($info['rule_value']);
			}
		}

		return $info;
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
	 * 编辑规则
	 * @param array $rule_array 规则数组
	 * @return array $result 结果返回
	 */
	public function editRule($rule_array = []){

		$result = ['state'=>0,'message'=>'未知错误'];

		$rule_array['rule_id'] = check_int($rule_array['rule_id']);
		$info = $this->getRuleInfo($rule_array['rule_id']);

		if(!empty($info)){
			$edit = [];
			$edit['rule_name'] = check_str($rule_array['rule_name']);
			if(!empty($edit['rule_name'])){
				$edit['rule_key'] = check_str($rule_array['rule_key']);
				if(!empty($edit['rule_key'])){
					$edit['rule_value'] = check_str($rule_array['rule_value']);
					$edit['is_json'] = check_int($rule_array['is_json']);
					if($edit['is_json'] == 1){
						//做特殊解析
						$edit['rule_value'] = $this->strToJson($edit['rule_value']);
						$temp = json_decode($edit['rule_value'],true);
					}else{
						$temp = $edit['rule_value'];
					}
					if(!empty($temp)){
						$edit['rule_remark'] = check_str($rule_array['rule_remark']);
						//编辑数据
						$edit['updatetime'] = time();
						if(M($this->web_data_rule_table)->where(['id'=>$rule_array['rule_id']])->save($edit)){
							$result['state'] = 1;
							$result['message'] = "数据编辑成功";
						}else{
							$result['message'] = "数据编辑失败";
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
		}else{
			$result['message'] = "未能获取规则详情";
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
			//先 分号 分段
			$temp = explode(";",$str);
			foreach($temp as $info){
				//再 逗号 分数组
				$val_temp = explode(",",$info);
				$array_temp = [];
				foreach($val_temp as $val_info){
					//再 冒号 分键值对
					$key_val_temp = explode(":",$val_info);
					if(!empty($key_val_temp[0]) && !empty($key_val_temp[1])){
						$key_val_temp[0] = check_str($key_val_temp[0]);
						$key_val_temp[1] = check_str($key_val_temp[1]);
						$array_temp[] = [$key_val_temp[0] => $key_val_temp[1]];
					}
				}
				$json_array[] = $array_temp;
			}
			$result = json_encode($json_array,JSON_UNESCAPED_UNICODE);
		}

		return $result;
	}

	/**
	 * 根据规则将json串变成字符串
	 * @param string $str 待解析字符串
	 * @return array $result 结果返回
	 */
	public function jsonToStr($json = ""){
		$result = "";

		if(!empty($json)){
			$json_array = [];
			$json_array = json_decode($json,true);
			foreach($json_array as $info){
				foreach($info as $key_val_info){
					$key = array_keys($key_val_info);
					$val = array_values($key_val_info);
					if(!empty($key[0]) && !empty($val[0])){
						$result .= $key[0].":".$val[0].",";
					}
				}
				$result = mb_substr($result,0,-1,'utf-8');
				$result .= ";";
			}
		}

		return $result;
	}

}
