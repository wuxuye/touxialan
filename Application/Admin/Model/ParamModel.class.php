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

	public function getAllOperation(){

		$this->order_table = C("TABLE_NAME_ORDER");

		$result = [
			//订单相关
			"wait_order" => 0, //待确认订单
			"success_wait_pay_order" => 0, //已完成待付款订单
			"middle_order" => 0, //中间状态订单（已确认，未完结）
			"wait_price" => 0, //代收订单金额
			"over_time" => 0, //隔夜订单
			"user_list" => [], //用户列表
		];

		//订单数据获取
		$order_list = M($this->order_table)
			->field(["state,pay_price,is_confirm,user_id,inputtime"])
			->where([
				"is_delete" => 0,
				"state" => ["in",[
					C("STATE_ORDER_WAIT_CONFIRM"),
					C("STATE_ORDER_WAIT_DELIVERY"),
					C("STATE_ORDER_DELIVERY_ING"),
					C("STATE_ORDER_WAIT_SETTLEMENT"),
				]],
			])
			->select();
		foreach($order_list as $info){
			if($info['is_confirm'] != 1){
				$result["wait_order"] ++;
			}
			if($info['state'] == C("STATE_ORDER_WAIT_SETTLEMENT")){
				$result["success_wait_pay_order"] ++;
			}else if($info['state'] != C("STATE_ORDER_WAIT_CONFIRM")){
				$result["middle_order"] ++;
			}
			$result["wait_price"] += $info['pay_price'];

			if(strtotime(date("Y-m-d 00:00:00",time()))>$info['inputtime']){
				$result['over_time'] ++;
			}
			$result["user_list"][$info['user_id']] = 1;
		}



		return $result;
	}


}
