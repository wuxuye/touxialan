<?php
namespace Yege;

/**
 * 活动逻辑类
 *
 * 方法提供
 * activityNewUserRegister      新用户注册活动
 * activityUserAnswerQuestion   用户每日答题活动
 */

class Activity{

    //提供于外面赋值或读取的相关参数
    public $user_id = 0; //涉及用户id


    private $user_table = ""; //相关用户表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->user_table = C("TABLE_NAME_USER");
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



}
