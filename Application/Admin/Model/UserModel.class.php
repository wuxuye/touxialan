<?php
/**
 * 用户信息相关后台Model
 * by wuxuye 2016-05-11
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class UserModel extends ViewModel{

	private $user_table = "";
	private $user_point_table = "";

	protected function _initialize(){
		$this->user_table = C("TABLE_NAME_USER");
		$this->user_point_table = C("TABLE_NAME_USER_POINTS");
	}

	/**
	 * 用户列表数据获取
	 * @param array $where where条件数组
	 * @param int $page 分页页码
	 * @param int $num 一页显示数量
	 * @return array $result 结果返回
	 */
	public function getUserList($where = array(),$page = 1,$num = 20){
		$result = array();
		$result['state'] = 0;
		$result['message'] = "未知错误";
		$result['list'] = array();
		$result['count'] = 0;

		//列表信息获取
		$limit = ($page-1)*$num.",".$num;
		$list = array();
		$list = M($this->user_table." as user")
				->field("user.*")
				->join("left join ".C("DB_PREFIX").$this->user_point_table." as user_point on user.id = user_point.user_id ")
				->where($where)
				->limit($limit)
				->order("user.id DESC")
				->select();

		//列表数据处理
		foreach($list as $key => $val){

		}

		$result['list'] = $list;

		//数量获取
		$count = M($this->user_table." as user")
				->field("user.*")
				->join("left join ".C("DB_PREFIX").$this->user_point_table." as user_point on user.id = user_point.user_id ")
				->where($where)
				->count();
		$result['count'] = empty($count) ? 0 : $count;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

}
