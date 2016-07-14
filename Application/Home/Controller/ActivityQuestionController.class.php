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

    public $activity_start_time = 0; //活动时间段开始时间
    public $activity_end_time = 0; //活动时间段结束时间
    public $activity_close = 0; //关闭活动
    public $activity_end = 0; //活动结束

    private $user_id = 0; //涉及用户id

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

        if(empty($user_info['id'])){
            //记录回跳url
            set_login_back_url("/Home/ActivityQuestion/showPublishQuestion");
            //跳转至用户登录
            redirect("/Home/User/userLogin");
        }

        //活动时间段 早上9点 到 下午4点
        $this->activity_start_time = strtotime(date("Y-m-d 09:00:00",time()));
        $this->activity_end_time = strtotime(date("Y-m-d 17:00:00",time()));
        $this->user_id = $user_info['id'];
        $this->activity_question_obj = new \Yege\ActivityQuestion();
    }

    /**
     * 获取当前发布题目详情
     * @return array $result 结果数组
     */
    public function showPublishQuestion(){
        //当前发布题目详情
        $info = [];
        $info = $this->activity_question_obj->getIsPublishQuestionInfo();
        //用户回答信息详情
        $user_answer = [];
        if(!empty($info['id'])){
            $user_answer = $this->activity_question_obj->getUserAnswer($info['id'],$this->user_id);
            if($user_answer['state'] != 1){
                $this->error($user_answer['message']);
            }
        }

        //活动时间段判断
        $is_active = 0;
        if((time() > $this->activity_start_time) && (time() < $this->activity_end_time)){
            $is_active = 1;
        }

        if(IS_POST){
            $post_info = I("post.");
            $user_id = check_int($this->user_id);
            $question_id = check_int($post_info['question_id']);
            $user_select = check_int($post_info['user_select']);
            if(empty($user_id) || empty($question_id) || empty($user_select)){
                $this->error("相关参数错误，请稍后刷新页面后再试");
            }

            if($is_active != 1){
                $this->error("暂不在活动时间段内，无法参与活动");
            }

            $result = [];
            $result = $this->activity_question_obj->userAnswerQuestion($question_id,$user_select);

            if($result['state'] == 1){
                //跳回这张页面
                redirect("/Home/ActivityQuestion/showPublishQuestion");
            }else{
                $this->error("提交答案失败：".$result['message']);
            }

        }

        $this->assign("info",$info);
        $this->assign("answer_info",$user_answer['answer_info']);
        $this->assign("is_active",$is_active);
        $this->display();
    }


}