<?php
/**
 * 商品信息相关后台Model
 * by wuxuye 2016-03-21
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class GoodsModel extends ViewModel{

	private $goods_table = '';
	private $user_table = '';
	private $attr_table = '';

	protected function _initialize(){
		$this->goods_table = C("TABLE_NAME_GOODS");
		$this->user_table = C("TABLE_NAME_USER");
		$this->attr_table = C("TABLE_NAME_ATTR");
	}

	/**
	 * 商品列表数据获取
	 * @param array $where where条件数组
	 * @param int $page 分页页码
	 * @param int $num 一页显示数量
	 * @return array $result 结果返回
	 */
	public function getGoodsList($where = array(),$page = 1,$num = 20){
		$result = array();
		$result['state'] = 0;
		$result['message'] = "未知错误";
		$result['list'] = array();
		$result['count'] = 0;

		//基本条件
		$where['goods.state'] = C("STATE_GOODS_NORMAL");

		//列表信息获取
		$limit = ($page-1)*$num.",".$num;
		$list = array();
		$list = M($this->goods_table." as goods")
				->field("goods.*,user.username,user.nick_name,user.mobile,attr.attr_name")
				->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = goods.belong_id ")
				->join("left join ".C("DB_PREFIX").$this->attr_table." as attr on attr.id = goods.attr_id")
				->where($where)
				->limit($limit)
				->order("id DESC")
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
			$list[$key]['tab_str'] = tab_dispose($val['tab']); //标签

		}

		$result['list'] = $list;

		//数量获取
		$count = M($this->goods_table." as goods")
				->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = goods.belong_id ")
				->join("left join ".C("DB_PREFIX").$this->attr_table." as attr on attr.id = goods.attr_id")
				->where($where)
				->count();
		$result['count'] = empty($count) ? 0 : $count;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

}
