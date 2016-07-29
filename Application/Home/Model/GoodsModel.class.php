<?php
/**
 * 商品信息相关前台Model
 * by wuxuye 2016-04-22
 */
namespace Home\Model;
use Think\Model\ViewModel;

class GoodsModel extends ViewModel{

	private $attr_table = '';
	private $goods_table = '';
	private $goods_stock_table = '';
	private $statistics_sale_table = '';

	protected function _initialize(){
		$this->attr_table = C("TABLE_NAME_ATTR");
		$this->goods_table = C("TABLE_NAME_GOODS");
		$this->goods_stock_table = C("TABLE_NAME_GOODS_STOCK");
		$this->statistics_sale_table = C("TABLE_NAME_STATISTICS_SALE");
	}

	/**
	 * 获取栏目列表
	 */
	public function getColumnList(){
		$result = ["state"=>0,"message"=>"未知错误","data"=>[]];

		$Param = new \Yege\Param();
		$list_result = [];
		$list_result = $Param->getDataByParam('goodsListShowAttr');
		if($list_result['state'] == 1){
			$result['state'] = 1;
			$result['data'] = $list_result['data'];
			$result['message'] = "获取成功";
		}else{
			$result['message'] = "未能正确获取栏目：".$list_result['message'];
		}

		return $result;
	}

	/**
	 * 商品列表数据获取
	 * @param array $where where条件数组
	 * @param int $page 分页页码
	 * @param int $num 一页显示数量
	 * @return array $result 结果返回
	 */
	public function getGoodsList($where = [],$page = 1,$num = 20){
		$result = [];
		$result['state'] = 0;
		$result['message'] = "未知错误";
		$result['list'] = [];
		$result['count'] = 0;

		//基本条件
		$where['goods.state'] = C("STATE_GOODS_NORMAL"); //状态正常
		$where['goods.is_shop'] = 1; //上架状态

		//列表信息获取
		$field = [
			"goods.id","goods.name","goods.ext_name","goods.price","goods.point","goods.can_price",
			"goods.can_point","goods.describe","goods.goods_image", "attr.attr_name",
			"statistics.sale_num","stock.stock","stock.stock_unit",
		];
		$limit = ($page-1)*$num.",".$num;
		$list = [];
		$list = M($this->goods_table." as goods")
				->field($field)
				->join("left join ".C("DB_PREFIX").$this->attr_table." as attr on attr.id = goods.attr_id")
				->join("left join ".C("DB_PREFIX").$this->statistics_sale_table." as statistics on statistics.goods_id = goods.id")
				->join("left join ".C("DB_PREFIX").$this->goods_stock_table." as stock on stock.goods_id = goods.id")
				->where($where)
				->limit($limit)
				->order("goods.is_recommend DESC,goods.weight DESC,goods.id DESC")
				->select();

		foreach($list as $key => $val){
			$list[$key]['goods_image'] = "/".(empty($val['goods_image']) ? C("HOME_GOODS_EMPTY_IMAGE_URL") : $val['goods_image']);
			$list[$key]['stock_unit'] = empty($val['stock_unit']) ? '个' : check_str($val['stock_unit']);
			$list[$key]['stock'] = empty($val['stock']) ? 0 : check_int($val['stock']);
			$list[$key]['price'] = empty($val['can_price']) ? '-' : $val['price'];
		}

		$result['list'] = $list;

		//数量获取
		$count = M($this->goods_table." as goods")
				->join("left join ".C("DB_PREFIX").$this->attr_table." as attr on attr.id = goods.attr_id")
				->join("left join ".C("DB_PREFIX").$this->statistics_sale_table." as statistics on statistics.goods_id = goods.id")
				->join("left join ".C("DB_PREFIX").$this->goods_stock_table." as stock on stock.goods_id = goods.id")
				->where($where)
				->count();
		$result['count'] = empty($count) ? 0 : $count;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

}
