<?php
/**
 * 商品信息相关后台Model
 * by wuxuye 2016-03-21
 *
 * 提供方法
 * getGoodsList			商品列表数据获取
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class GoodsModel extends ViewModel{

	private $goods_table = '';
	private $goods_stock_table = '';
	private $user_table = '';
	private $attr_table = '';
	private $statistics_sale_table = '';

	protected function _initialize(){
		$this->goods_table = C("TABLE_NAME_GOODS");
		$this->goods_stock_table = C("TABLE_NAME_GOODS_STOCK");
		$this->user_table = C("TABLE_NAME_USER");
		$this->attr_table = C("TABLE_NAME_ATTR");
		$this->statistics_sale_table = C("TABLE_NAME_STATISTICS_SALE");
	}

	/**
	 * 商品列表数据获取
	 * @param array $where where条件数组
	 * @param int $page 分页页码
	 * @param int $num 一页显示数量
	 * @return array $result 结果返回
	 */
	public function getGoodsList($where = [],$page = 1,$num = 20){
		$result = ['state'=>0,'message'=>'未知错误','list'=>[],'count'=>0];

		//基本条件
		$where['goods.state'] = C("STATE_GOODS_NORMAL");

		//列表信息获取
		$limit = ($page-1)*$num.",".$num;
		$list = array();
		$list = M($this->goods_table." as goods")
				->field("goods.*,stock.stock as goods_stock,stock.stock_unit,user.username,user.nick_name,user.mobile,attr.attr_name,sale.sale_num,sale.sale_price,sale.sale_user,sale.statistics_time")
				->join("left join ".C("DB_PREFIX").$this->goods_stock_table." as stock on stock.goods_id = goods.id")
				->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = goods.belong_id ")
				->join("left join ".C("DB_PREFIX").$this->attr_table." as attr on attr.id = goods.attr_id")
				->join("left join ".C("DB_PREFIX").$this->statistics_sale_table." as sale on sale.goods_id = goods.id")
				->where($where)
				->limit($limit)
				->order("goods.is_recommend DESC,goods.weight DESC,goods.id DESC")
				->select();

		//列表数据处理
		foreach($list as $key => $val){
			$belong_show_name = $val['nick_name'];
			if(empty($belong_show_name)){
				$belong_show_name = $val['username'];
				if(empty($belong_show_name)){
					$belong_show_name = $val['mobile'];
				}
			}

			$list[$key]['belong_str'] = $belong_show_name;
			$list[$key]['is_shop_str'] = C("STATE_GOODS_IS_SHOP_LIST")[$val['is_shop']];
			$list[$key]['is_recommend_str'] = empty($val['is_recommend'])?'-':'推荐中';

			$list[$key]['goods_stock'] = check_int($val['goods_stock']);
			if(empty($list[$key]['goods_stock'])){
				$list[$key]['goods_stock'] = 0;
			}

			$list[$key]['sale_num'] = empty($val['sale_num']) ? 0 : check_int($val['sale_num']);
			$list[$key]['sale_price'] = empty($val['sale_price']) ? 0 : check_int($val['sale_price']);
			$list[$key]['sale_user'] = empty($val['sale_user']) ? 0 : check_int($val['sale_user']);
			$list[$key]['statistics_time'] = empty($val['statistics_time']) ? '未统计' : date("Y-m-d H:i:s",$val['statistics_time']);

		}

		$result['list'] = $list;

		//数量获取
		$count = M($this->goods_table." as goods")
				->join("left join ".C("DB_PREFIX").$this->goods_stock_table." as stock on stock.goods_id = goods.id")
				->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = goods.belong_id ")
				->join("left join ".C("DB_PREFIX").$this->attr_table." as attr on attr.id = goods.attr_id")
				->join("left join ".C("DB_PREFIX").$this->statistics_sale_table." as sale on sale.goods_id = goods.id")
				->where($where)
				->count();
		$result['count'] = empty($count) ? 0 : $count;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

}
