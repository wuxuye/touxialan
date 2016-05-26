<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 前台活动 - 每日答题控制器
 *
 * 相关活动方法：
 * showPublishQuestion  获取当前发布题目详情
 */

class ActivityQuestionController extends ActivityController {

    public $activity_close = 0; //关闭活动
    public $activity_end = 0; //活动结束



    public function _initialize(){
        parent::_initialize();

        //基础检测

        if($this->activity_end != 0){
            echo "活动已结束";exit;
        }
        if($this->activity_close != 0){
            echo "活动关闭";exit;
        }

        //进这个活动要先登录 首先获取用户登录信息
        $user_info = [];
        $user_info = get_login_user_info();

        if(empty($user_info['user_id'])){
            //记录回跳url
            set_login_back_url("/Home/ActivityQuestion/showPublishQuestion");
            //跳转至用户登录
            redirect("/Home/User/userLogin");
        }

        $this->activity_question_obj = new \Yege\ActivityQuestion();
    }

    /**
     * 获取当前发布题目详情
     * @return array $result 结果数组
     */
    public function showPublishQuestion(){
        $info = $this->activity_question_obj->getIsPublishQuestionInfo();
        $user_answer =
        $this->assign("info",$info);
        $this->display();
    }


}