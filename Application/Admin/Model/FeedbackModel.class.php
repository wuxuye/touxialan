<?php
/**
 * 用户反馈相关后台Model
 * by wuxuye 2016-09-09
 *
 * 提供方法
 * getUserFeedList			用户反馈数据获取
 */
namespace Admin\Model;
use Think\Model\ViewModel;

class FeedbackModel extends ViewModel{

	private $user_table = '';
	private $user_feedback_table = '';

	protected function _initialize(){
		$this->user_table = C("TABLE_NAME_USER");
		$this->user_feedback_table = C("TABLE_NAME_USER_FEEDBACK");
	}

	/**
	 * 用户反馈数据获取
	 * @param array $where where条件数组
	 * @param int $page 分页页码
	 * @param int $num 一页显示数量
	 * @return array $result 结果返回
	 */
	public function getUserFeedList($where = [],$page = 1,$num = 20){
		$result = ['state'=>0,'message'=>'未知错误','list'=>[],'count'=>0];

		//列表信息获取
		$limit = ($page-1)*$num.",".$num;
		$list = array();
		$list = M($this->user_feedback_table." as feedback")
				->field("feedback.*,user.mobile")
				->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = feedback.user_id ")
				->where($where)
				->limit($limit)
				->order("feedback.is_solve ASC,feedback.id DESC")
				->select();

		$result['list'] = $list;

		//数量获取
		$count = M($this->user_feedback_table." as feedback")
				->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = feedback.user_id ")
				->where($where)
				->count();
		$result['count'] = empty($count) ? 0 : $count;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

}
