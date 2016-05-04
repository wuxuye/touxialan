<?php
namespace Yege;

/**
 * 活动类
 *
 * 方法提供
 */

class Activity{

    //提供于外面赋值或读取的相关参数
    public $user_id = 0; //涉及用户id


    private $user_table = ""; //相关用户表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->user_table = C("TABLE_NAME_USER");
    }



}
