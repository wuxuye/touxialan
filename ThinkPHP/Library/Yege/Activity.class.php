<?php
namespace Yege;

/**
 * 活动逻辑类
 *
 * 方法提供
 * getActivityList              获取活动列表
 * addActivity                  添加活动
 * editActivity                 编辑活动
 * deleteActivity               删除活动
 * getInfo                      根据活动id获取基本信息
 * activityNewUserRegister      新用户注册活动
 * activityUserAnswerQuestion   用户每日答题活动
 */

class Activity{

    //提供于外面赋值或读取的相关参数
    public $user_id = 0; //涉及用户id
    public $activity_id = 0; //活动id
    public $activity_title = ""; //活动标题
    public $activity_url = ""; //活动链接
    public $activity_start_time = ""; //活动开始时间
    public $activity_end_time = ""; //活动结束时间


    private $activity_table = ""; //活动表
    private $user_table = ""; //相关用户表
    private $activity_question_history_statistics_table = "";
    private $activity_question_user_answer_table = "";
    private $activity_info = []; //活动详情

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
     * 添加活动
     */
    public function addActivity(){
        $result = ['state'=>0,'message'=>'未知错误'];

        $activity_title = check_str($this->activity_title);
        $activity_url = check_str($this->activity_url);
        $activity_start_time = check_str($this->activity_start_time);
        $activity_end_time = check_str($this->activity_end_time);

        if(!empty($activity_title) && !empty($activity_url)){
            $add = [];
            $add['title'] = $activity_title;
            $add['url'] = $activity_url;
            $add['start_time'] = is_date($activity_start_time) ? strtotime($activity_start_time) : 0;
            $add['end_time'] = is_date($activity_end_time) ? strtotime($activity_end_time) : 0;
            $add['inputtime'] = $add['updatetime'] = time();

            if(M($this->activity_table)->add($add)){
                $result['state'] = 1;
                $result['message'] = "添加成功";
            }else{
                $result['message'] = "活动添加失败";
            }
        }else{
            $result['message'] = "活动标题与链接不能为空";
        }

        return $result;
    }

    /**
     * 编辑活动
     * @result array $result 结果返回
     */
    public function editActivity(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //尝试获取详情信息
        $activity_info = [];
        $activity_info = $this->getInfo();
        if($activity_info['state'] == 1){

            $activity_title = check_str($this->activity_title);
            $activity_url = check_str($this->activity_url);
            $activity_start_time = check_str($this->activity_start_time);
            $activity_end_time = check_str($this->activity_end_time);

            if(!empty($activity_title) && !empty($activity_url)){
                $save = $where = [];
                $where['id'] = $this->activity_info['id'];
                $save['title'] = $activity_title;
                $save['url'] = $activity_url;
                $save['start_time'] = is_date($activity_start_time) ? strtotime($activity_start_time) : 0;
                $save['end_time'] = is_date($activity_end_time) ? strtotime($activity_end_time) : 0;
                $save['updatetime'] = time();

                if(M($this->activity_table)->where($where)->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "修改成功";
                }else{
                    $result['message'] = "活动修改失败";
                }
            }else{
                $result['message'] = "活动标题与链接不能为空";
            }
        }else{
            $result['message'] = $activity_info['message'];
        }

        return $result;
    }

    /**
     * 删除活动
     * @result array $result 结果返回
     */
    public function deleteActivity(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //尝试获取详情信息
        $activity_info = [];
        $activity_info = $this->getInfo();
        if($activity_info['state'] == 1){
            //修改数据
            $save = $where = [];
            $where['id'] = $this->activity_info['id'];
            $save['is_delete'] = 1;
            $save['updatetime'] = time();
            if(M($this->activity_table)->where($where)->save($save)){
                $result['state'] = 1;
                $result['message'] = "删除成功";
            }else{
                $result['message'] = "删除失败";
            }
        }else{
            $result['message'] = $activity_info['message'];
        }

        return $result;
    }

    /**
     * 根据活动id获取基本信息
     * @result array $result 结果返回
     */
    public function getInfo(){
        $result = ['state'=>0,'result'=>[],'message'=>'未知错误'];

        $activity_id  = check_int($this->activity_id);
        if(!empty($activity_id) && $activity_id > 0){
            //基础数据获取
            $info = $where = [];
            $where['id'] = $activity_id;
            $info = M($this->activity_table)->where($where)->find();
            if(!empty($info)){
                $result['state'] = 1;
                $result['result'] = $info;
                $result['message'] = "获取成功";
                $this->activity_info = $info;
            }else{
                $result['message'] = "未能获取活动信息";
            }
        }else{
            $result['message'] = "活动id错误";
        }

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
