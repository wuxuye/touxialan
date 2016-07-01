<?php
/**
 * 用户信息相关后台Model
 * by wuxuye 2016-05-11
 *
 * 提供方法
 * getUserList			用户列表数据获取
 * changeUserState		改变用户状态
 * changeUserIdentity	改变用户身份信息
 * resetUserResetCode	重置用户重置用安全码
 * deleteUserMessage	删除一条指定的用户消息
 */
namespace Admin\Model;
use Think\Model\ViewModel;
use Yege\User;

class UserModel extends ViewModel{

	private $user_table = "";
	private $user_point_table = "";

	protected function _initialize(){
		$this->user_table = C("TABLE_NAME_USER");
		$this->user_point_table = C("TABLE_NAME_USER_POINTS");
		$this->user_message_table = C("TABLE_NAME_USER_MESSAGE");
	}

	/**
	 * 用户列表数据获取
	 * @param array $where where条件数组
	 * @param int $page 分页页码
	 * @param int $num 一页显示数量
	 * @return array $result 结果返回
	 */
	public function getUserList($where = array(),$page = 1,$num = 20){
		$result = ['state' => 0,'message'=> '未知错误','list' => [],'count' => 0];

		//列表信息获取
		$limit = ($page-1)*$num.",".$num;
		$list = array();
		$list = M($this->user_table." as user")
				->field("user.*,user_point.points")
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
				->join("left join ".C("DB_PREFIX").$this->user_point_table." as user_point on user.id = user_point.user_id ")
				->where($where)
				->count("user.id");
		$result['count'] = empty($count) ? 0 : $count;

		$result['state'] = 1;
		$result['message'] = "获取成功";

		return $result;
	}

	/**
	 * 改变用户状态
	 * @param int $user_id 用户id
	 * @param int $state 用户状态
	 * @return array $result 结果返回
	 */
	public function changeUserState($user_id = 0,$state = 0){
		$result = ['state' => 0,'message' => '未知错误'];

		if(!empty($user_id)){
			if(!empty(C("STATE_USER_STATE_LIST")[$state])){
				$save = $where = [];
				$where['id'] = $user_id;
				$save['state'] = $state;
				$save['updatetime'] = time();
				if(M(C("TABLE_NAME_USER"))->where($where)->save($save)){
					$result['state'] = 1;
					$result['message'] = '数据更新成功';
				}else{
					$result['message'] = '数据更新失败';
				}
			}else{
				$result['message'] = '状态错误';
			}
		}else{
			$result['message'] = '用户id缺失';
		}

		return $result;
	}

	/**
	 * 改变用户身份信息
	 * @param int $user_id 用户id
	 * @param int $identity 用户身份
	 * @return array $result 结果返回
	 */
	public function changeUserIdentity($user_id = 0,$identity = 0){
		$result = ['state' => 0,'message' => '未知错误'];

		if(!empty($user_id)){
			if(!empty(C("IDENTITY_USER_STATE_LIST")[$identity])){
				$save = $where = [];
				$where['id'] = $user_id;
				$save['identity'] = $identity;
				$save['updatetime'] = time();
				if(M(C("TABLE_NAME_USER"))->where($where)->save($save)){
					$result['state'] = 1;
					$result['message'] = '数据更新成功';
				}else{
					$result['message'] = '数据更新失败';
				}
			}else{
				$result['message'] = '身份错误';
			}
		}else{
			$result['message'] = '用户id缺失';
		}

		return $result;
	}

	/**
	 * 重置用户重置用安全码
	 * @param int $user_id 用户id
	 * @return array $result 结果返回
	 */
	public function resetUserResetCode($user_id = 0){
		$result = ['state' => 0,'message' => '未知错误'];

		if(!empty($user_id)){

			//生成重置码
			$reset_code = substr(md5(substr(md5(time()),0,10)),-10,8);

			$save = $where = [];
			$where['id'] = $user_id;
			$save['reset_code'] = $reset_code;
			$save['updatetime'] = time();
			if(M(C("TABLE_NAME_USER"))->where($where)->save($save)){
				$result['state'] = 1;
				$result['message'] = '数据更新成功';
				$result['reset_code'] = $reset_code;
			}else{
				$result['message'] = '数据更新失败';
			}

		}else{
			$result['message'] = '用户id缺失';
		}

		return $result;
	}

	/**
	 * 删除一条指定的用户消息
	 * @param int $message_id 消息id
	 * @return array $result 结果返回
	 */
	public function deleteUserMessage($message_id = 0){
		$result = ['state' => 0,'message' => '未知错误'];

		$message_id = intval($message_id);
		if(!empty($message_id)){
			if(M($this->user_message_table)->where(['id'=>$message_id])->delete()){
				$result['state'] = 1;
				$result['message'] = '操作成功';
			}else{
				$result['message'] = '操作失败';
			}
		}else{
			$result['message'] = '消息id错误';
		}

		return $result;
	}

}
