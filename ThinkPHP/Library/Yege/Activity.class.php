<?php
namespace Yege;

/**
 * 活动逻辑类
 *
 * 方法提供
 * getActivityList              获取活动列表
 * activityNewUserRegister      新用户注册活动
 * activityUserAnswerQuestion   用户每日答题活动
 */

class Activity{

    //提供于外面赋值或读取的相关参数
    public $user_id = 0; //涉及用户id

    private $activity_table = ""; //活动表
    private $user_table = ""; //相关用户表
    private $activity_question_history_statistics_table = "";
    private $activity_question_user_answer_table = "";

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->activity_table = C("TABLE_NAME_ACTIVITY");
        $this->activity_question_history_statistics_table = C("TABLE_NAME_ACTIVITY_QUESTION_HISTORY_STATISTICS");
        $this->activity_question_user_answer_table = C("TABLE_NAME_ACTIVITY_QUESTION_USER_ANSWER");
        $this->user_table = C("TABLE_NAME_USER");
    }

    /**
     * 获取活动列表
     * @param array $where 搜索条件
     * @param int $page 页码
     * @param int $num 单页数量
     * @return array $list 结果返回
     */
    public function getActivityList($where = [],$page = 1,$num = 20){
        $result = [
            "list" => [],
            "count" => 0,
        ];

        //基础参数
        $where["is_delete"] = 0;

        $limit = ($page-1)*$num.",".$num;

        $activity_list = M($this->activity_table)
            ->where($where)
            ->limit($limit)
            ->order("inputtime DESC")
            ->select();

        $result['list'] = $activity_list;

        //数量获取
        $count = M($this->activity_table)
            ->where($where)
            ->count();
        $result['count'] = empty($count) ? 0 : $count;

        return $result;
    }

    /**
     * 新用户注册活动
     * 需要 user_id
     */
    public function activityNewUserRegister(){
        //积分逻辑
        $point_obj = new \Yege\Point();
        $point_obj->user_id = $this->user_id;
        $temp_result = [];
        $point_obj->operation_tab = "new_user_register";
        $temp_result = $point_obj->updateUserPoints();
        if($temp_result['state'] != 1){
            //错误日志记录
            $log = "新用户注册，积分赠送逻辑失败\r\n涉及用户id：".$point_obj->user_id."  调用活动：".$point_obj->operation_tab."\r\n当时的数据返回json：".json_encode($temp_result,JSON_UNESCAPED_UNICODE);
            add_wrong_log($log);
        }
        //活动积分
        $temp_result = [];
        $point_obj->operation_tab = "new_user_register_activity";
        $temp_result = $point_obj->updateUserPoints();
        if($temp_result['state'] != 1){
            //错误日志记录
            $log = "新用户注册，活动积分赠送逻辑失败\r\n涉及用户id：".$point_obj->user_id."  调用活动：".$point_obj->operation_tab."\r\n当时的数据返回json：".json_encode($temp_result,JSON_UNESCAPED_UNICODE);
            add_wrong_log($log);
        }
    }

    /**
     * 用户每日答题活动
     * 需要 user_id
     */
    public function activityUserAnswerQuestion(){
        //积分逻辑
        $point_obj = new \Yege\Point();
        $point_obj->user_id = $this->user_id;
        $temp_result = [];
        $point_obj->operation_tab = "answer_question_every_day_activity";
        $temp_result = $point_obj->updateUserPoints();
        if($temp_result['state'] != 1){
            //错误日志记录
            $log = "活动 - 每日答题，用户积分获得逻辑失败\r\n涉及用户id：".$point_obj->user_id."  调用活动：".$point_obj->operation_tab."\r\n当时的数据返回json：".json_encode($temp_result,JSON_UNESCAPED_UNICODE);
            add_wrong_log($log);
        }
    }



}
