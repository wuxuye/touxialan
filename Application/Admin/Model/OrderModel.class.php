<?php
/**
 * 订单信息相关后台Model
 * by wuxuye 2016-08-30
 *
 * 提供方法
 * getOrderList			订单列表数据获取
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

}
