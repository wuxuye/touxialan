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
	private $order_goods_table = '';

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

		//列表数据处理
		foreach($list as $key => $val){

		}

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
				$result = $order_info;
			}
		}

		return $result;
	}

	/**
	 * 获取今天下单的所有订单统计信息
	 */
	public function getTodayAllOrderStatistics(){
		$data = [];

		$start_time = strtotime(date("Y-m-d 00:00:00",time()));
		$end_time = strtotime(date("Y-m-d 23:59:59",time()));

		$where = [
			"inputtime" => [
				["egt",$start_time],
				["elt",$end_time],
			],
		];
		$order_list = M($this->order_table)
			->where($where)
			->order("inputtime DESC,id DESC")
			->select();

		$statistics = [
			"count" => count($order_list), //今天总订单数
			"no_dispose" => 0, //未处理
			"is_dispose" => 0, //已处理

		];

		$data['list'] = $order_list;
		$data['statistics'] = $statistics;

		return $data;
	}

}
