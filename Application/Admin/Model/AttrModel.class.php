<?php
/**
 * 属性信息相关后台Model
 * by wuxuye 2016-03-31
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class AttrModel extends ViewModel{

	private $attr_table = '';

	protected function _initialize(){
		$this->attr_table = C("TABLE_NAME_ATTR");
	}

	/**
	 * 属性列表数据获取
	 * @param array $where where条件数组
	 * @param int $parent_id 父级id
	 * @return array $result 结果返回
	 */
	public function getAttrList($where = array(),$parent_id = 0){
		$result = array();
		$result['state'] = 0;
		$result['message'] = "未知错误";
		$result['list'] = array();
		$result['count'] = 0;

		$parent_id = intval($parent_id);
		if(empty($parent_id) || $parent_id < 0){
			$parent_id = 0;
		}

		//基本条件
		$where['attr.parent_id'] = $parent_id;
		$where['attr.state'] = C("STATE_ATTR_NORMAL");

		//列表信息获取
		$list = array();
		$list = M($this->attr_table." as attr")
			->field("attr.*,parent_attr.parent_id as p_id,parent_attr.attr_name as parent_name,child_attr.child_num")
			->join("left join ".C("DB_PREFIX").$this->attr_table." as parent_attr on attr.parent_id = parent_attr.id")
			->join("left join (select count(id) as child_num,parent_id from ".C("DB_PREFIX").$this->attr_table." where state = 1 group by parent_id ) as child_attr on attr.id = child_attr.parent_id ")
			->where($where)
			->order("attr.id ASC")
			->select();

		//列表数据处理
//		foreach($list as $key => $val){
//			$list[$key]['state_str'] = C('STATE_ATTR_STATE_LIST')[$val['state']]; //状态
//		}

		$result['list'] = $list;

		//数量获取
		$count = M($this->attr_table." as attr")
			->field("attr.*,parent_attr.attr_name as parent_name")
			->join("left join ".C("DB_PREFIX").$this->attr_table." as parent_attr on attr.parent_id = parent_attr.id")
			->where($where)
			->count();
		$result['count'] = empty($count) ? 0 : $count;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

}
