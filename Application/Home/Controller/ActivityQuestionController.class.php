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

    private $start_time = 0;
    private $end_time = 0;

    public function _initialize(){
        parent::_initialize();

        //基础检测

        //进这个活动要先登录 首先获取用户登录信息
        $user_info = [];
        $user_info = $this->now_user_info;

        if(empty($user_info['id'])){
            //记录回跳url
            set_login_back_url("/Home/ActivityQuestion/showPublishQuestion");
            //跳转至用户登录
            redirect("/Home/User/userLogin");
        }

        $ActivityQuestion = new \Yege\ActivityQuestion();
        //活动状态获取
        $activity_state = $ActivityQuestion->getActivityState();

        if(!empty($activity_state['is_end'])){
            $this->error("该活动已结束");
        }

        $this->start_time = $activity_state['start_time'];
        $this->end_time = $activity_state['end_time'];

    }

    /**
     * 获取当前发布题目详情
     * @return array $result 结果数组
     */
    public function showPublishQuestion(){

        $ActivityQuestion = new \Yege\ActivityQuestion();

        //当前发布题目详情
        $info = [];
        $info = $ActivityQuestion->getIsPublishQuestionInfo();

        //用户回答信息详情
        $user_answer = [];
        if(!empty($info['id'])){
            $ActivityQuestion->question_id = $info['id'];
            $ActivityQuestion->user_id = $this->now_user_info['id'];
            $user_answer = $ActivityQuestion->getUserAnswer();
            if($user_answer['state'] != 1){
                $this->error($user_answer['message']);
            }
        }

        //活动时间段判断
        $is_active = 0;
        if((time() > $this->start_time) && (time() < $this->end_time)){
            $is_active = 1;
        }

        $this->home_head_left_title = "答题活动";
        $this->hidden_nav = 1;

        $this->assign("info",$info);
        $this->assign("answer_info",$user_answer['answer_info']);
        $this->assign("is_active",$is_active);
        $this->display();
    }


}