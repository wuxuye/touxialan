<?php
namespace Yege;

/**
 * 用户反馈类
 *
 * 方法提供
 * addFeedback              添加反馈信息
 * getFeedbackInfo          问题反馈详情获取
 * solveFeedback            解决问题反馈
 */

class Feedback{

    //提供于外面赋值或读取的相关参数
    public $feedback_id = 0; //反馈id
    public $user_id = 0; //用户id
    public $type = 0; //反馈类型
    public $message = ""; //反馈问题
    public $solve_plan = ""; //解决方案

    private $user_table = ""; //用户信息表
    private $user_feedback_table = ""; //用户信息反馈记录表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->user_table = C("TABLE_NAME_USER");
        $this->user_feedback_table = C("TABLE_NAME_USER_FEEDBACK");
    }


    /**
     * 添加反馈信息
     */
    public function addFeedback(){
        $result = ["state"=>0,"message"=>"未知错误"];

        $message = check_str($this->message);
        $user_id = check_int($this->user_id);
        $type = check_int($this->type);

        if(!empty($message)){
            $user_info = [];
            $user_obj = new \Yege\User();
            $user_obj->user_id = $user_id;
            $user_info = $user_obj->getUserInfo();
            if($user_info['state'] == 1 && !empty($user_info['result'])){
                //类型判断是否在取值范围内
                if(!empty(C("FEEDBACK_TYPE_LIST")[$type])){
                    $user_info = $user_info['result'];
                    //向用户信息反馈记录表中添加数据
                    $add = [
                        "user_id" => $this->user_id,
                        "type" => $this->type,
                        "message" => $this->message,
                        "inputtime" => time(),
                    ];
                    if(M($this->user_feedback_table)->add($add)){
                        $result['state'] = 1;
                        $result['message'] = '反馈信息添加成功';
                    }else{
                        $result['message'] = "反馈信息添加失败";
                    }
                }else{
                    $result['message'] = "反馈类型不在取值范围内";
                }
            }else{
                $result['message'] = "未能获取用户信息";
            }
        }else{
            $result['message'] = "请正确填写反馈内容";
        }

        return $result;
    }

    /**
     * 问题反馈详情获取
     */
    public function getFeedbackInfo(){
        $info = [];
        $feedback_id = check_int($this->feedback_id);

        if(!empty($feedback_id)){
            $info = M($this->user_feedback_table)->where(["id"=>$feedback_id])->find();
            if(empty($info['id'])){
                $info = [];
            }
        }

        return $info;
    }

    /**
     * 解决问题反馈
     */
    public function solveFeedback(){
        $result = ["state"=>0,"message"=>"未知错误"];

        $info = $this->getFeedbackInfo();
        if(!empty($info['id'])){
            $save = [
                "is_solve" => 1,
                "solve_plan" => $this->solve_plan,
                "solve_time" => time(),
            ];
            if(M($this->user_feedback_table)->where(["id"=>$info['id']])->save($save)){

                //解决问题的时候给对应的用户发一条消息
                add_user_message($info['user_id'],"您 ".date("Y-m-d H:i:s",$info['inputtime'])." 提交的问题已经解决，<a target='_blank' href='/Home/user/showFeedback/id/".$info['id']."' >点击查看详情</a>",1,0);

                $result['state'] = 1;
                $result['message'] = '反馈信息已处理';
            }else{
                $result['message'] = "更新问题反馈信息表失败";
            }
        }else{
            $result['message'] = "未能获取问题反馈详情";
        }

        return $result;
    }



}
