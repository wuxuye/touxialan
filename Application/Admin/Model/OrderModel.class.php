<?php
/**
 * 订单信息相关后台Model
 * by wuxuye 2016-08-30
 *
 * 提供方法
 * getOrderList						订单列表数据获取
 * getOrderInfo						订单详情获取
 * getTodayAllOrderStatistics		获取今天下单的所有订单统计信息
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class OrderModel extends ViewModel{

	private $order_table = '';

	protected function _initialize(){
		$this->order_table = C("TABLE_NAME_ORDER");
		$this->user_table = C("TABLE_NAME_USER");
	}

	/**
	 * 订单列表数据获取
	 * @param array $where where条件数组
	 * @param int $page 分页页码
	 * @param int $num 一页显示数量
	 * @return array $result 结果返回
	 */
	public function getOrderList($where = [],$page = 1,$num = 20){
		$result = ['state'=>0,'message'=>'未知错误','list'=>[],'count'=>0];

		//基础条件
		if(empty($where['orders.is_delete'])){
			$where['orders.is_delete'] = 0;
		}

		//列表信息获取
		$limit = ($page-1)*$num.",".$num;
		$list = array();
		$list = M($this->order_table." as orders")
				->field("orders.*,user.mobile")
				->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = orders.user_id")
				->where($where)
				->limit($limit)
				->order("orders.inputtime DESC,orders.id DESC")
				->select();

		$result['list'] = $list;

		//数量获取
		$count = M($this->order_table." as orders")
			->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = orders.user_id ")
			->where($where)
			->count();
		$result['count'] = empty($count) ? 0 : $count;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

	/**
	 * 订单详情获取
	 * @param int $order_id 订单id
	 * @return array $result 结果返回
	 */
	public function getOrderInfo($order_id = 0){
		$result = [];

		$order_id = check_int($order_id);
		$info = M($this->order_table." as orders")
			->field("orders.id,orders.user_id,user.mobile,user.credit")
			->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = orders.user_id")
			->where(["orders.id"=>$order_id])->find();
		if(!empty($info['id'])){
			$order_info = [];
			$order_obj = new \Yege\Order();
			$order_obj->order_id = $order_id;
			$order_obj->user_id = $info['user_id'];
			$order_info = $order_obj->getUserOrderInfo();
			if(!empty($order_info['order_info']['id'])){
				$order_info['order_info']['user_mobile'] = $info['mobile'];
				$order_info['order_info']['user_credit'] = $info['credit'];

				//顺便拿日志
				$order_info['order_log'] = $order_obj->getOrderLog();

				$result = $order_info;
			}
		}

		return $result;
	}

	/**
	 * 获取本周下单的所有订单统计信息
	 */
	public function getWeekAllOrderStatistics(){
		$data = [];

		$date = get_week_time();
		$start_time = $end_time = 0;
		if(!empty($date)){
			$start_time = $date['start_time'];
			$end_time = $date['end_time'];
		}

		if(!empty($start_time) && !empty($end_time)){
			$where = [
				"inputtime" => [
					["egt",$start_time],
					["elt",$end_time],
				],
				"is_delete" => 0,
			];
			$order_list = M($this->order_table)
				->field(["id,state,is_confirm,is_pay"])
				->where($where)
				->order("inputtime DESC,id DESC")
				->select();

			//数量统计
			$statistics = [
				"count" => count($order_list), //本周总订单数
				"wait_confirm" => ["num"=>0,"list"=>[]], //待确认订单数量
				"wait_delivery" => ["num"=>0,"list"=>[]], //待发货订单数
				"delivery_ing" => ["num"=>0,"list"=>[]], //配送中订单数
				"wait_settlement" => ["num"=>0,"list"=>[]], //待结算订单数
				"success" => ["num"=>0,"list"=>[]], //已完成订单数
				"is_close" => ["num"=>0,"list"=>[]], //已关闭订单数
				"dissent" => ["num"=>0,"list"=>[]], //有异议订单数
				"back" => ["num"=>0,"list"=>[]], //已退款订单数
			];
			foreach($order_list as $info){
				switch($info['state']){
					case C('STATE_ORDER_WAIT_CONFIRM'): //待确认
						$statistics['wait_confirm']['num']++;
						$statistics['wait_confirm']['list'][] = $info['id'];
						break;
					case C("STATE_ORDER_WAIT_DELIVERY"): //待发货
						$statistics['wait_delivery']['num']++;
						$statistics['wait_delivery']['list'][] = $info['id'];
						break;
					case C("STATE_ORDER_DELIVERY_ING"): //配送中
						$statistics['delivery_ing']['num']++;
						$statistics['delivery_ing']['list'][] = $info['id'];
						break;
					case C("STATE_ORDER_WAIT_SETTLEMENT"): //待结算
						$statistics['wait_settlement']['num']++;
						$statistics['wait_settlement']['list'][] = $info['id'];
						break;
					case C("STATE_ORDER_SUCCESS"): //已完成
						$statistics['success']['num']++;
						$statistics['success']['list'][] = $info['id'];
						break;
					case C("STATE_ORDER_CLOSE"): //已关闭
						$statistics['is_close']['num']++;
						$statistics['is_close']['list'][] = $info['id'];
						break;
					case C("STATE_ORDER_DISSENT"): //有异议
						$statistics['dissent']['num']++;
						$statistics['dissent']['list'][] = $info['id'];
						break;
					case C("STATE_ORDER_BACK"): //已退款
						$statistics['back']['num']++;
						$statistics['back']['list'][] = $info['id'];
						break;
				}
			}
			//统计数据处理
			foreach($statistics as $key => $val){
				if(!empty($val['list'])){
					$statistics[$key]['list'] = implode(",",$val['list']);
				}else{
					$statistics[$key]['list'] = "";
				}
			}

			$data['list'] = $order_list;
			$data['statistics'] = $statistics;
		}

		return $data;
	}

}
