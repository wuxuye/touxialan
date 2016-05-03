<?php
namespace Yege;

/**
 * 积分类
 *
 * 方法提供
 *
 */

class Point{

    //提供于外面赋值或读取的相关参数
    public $user_id = 0; //用户id
    public $log = ""; //日志信息
    public $points = 0; //操作积分

    private $user_table = ""; //相关用户表
    private $user_points_table = ""; //相关用户积分表
    private $user_points_log_table = ""; //相关用户积分日志表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->user_table = C("TABLE_NAME_USER");
        $this->user_points_table = C("TABLE_NAME_USER_POINTS");
        $this->user_points_log_table = C("TABLE_NAME_USER_POINTS_LOG");
    }

    /**
     * 更新用户积分信息
     * @param array $return 结果返回
     */
    public function updateUserPoints(){
        $return = ['state'=>0,'message'=>'未知错误'];



        return $return;
    }

}
