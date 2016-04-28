<?php
/**
 * 标签信息相关后台Model
 * by wuxuye 2016-04-28
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class TagsModel extends ViewModel{

	private $tags_table = '';

	protected function _initialize(){
		$this->tags_table = C("TABLE_NAME_TAGS");
	}

	/**
	 * 标签列表数据获取
	 * @param array $where where条件数组
	 * @param int $page 分页页码
	 * @param int $num 一页显示数量
	 * @return array $result 结果返回
	 */
	public function getTagsList($where = array(),$page = 1,$num = 20){
		$result = array();
		$result['state'] = 0;
		$result['message'] = "未知错误";
		$result['list'] = array();
		$result['count'] = 0;

		//基本条件
		$where['tags.state'] = C("STATE_TAGS_NORMAL");

		//列表信息获取
		$limit = ($page-1)*$num.",".$num;
		$list = array();
		$list = M($this->tags_table." as tags")
				->field("tags.*")
				->where($where)
				->limit($limit)
				->order("tags.id DESC")
				->select();

		$result['list'] = $list;

		//数量获取
		$count = M($this->tags_table." as tags")
				->where($where)
				->count();
		$result['count'] = empty($count) ? 0 : $count;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

}
