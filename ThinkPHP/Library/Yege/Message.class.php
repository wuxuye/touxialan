<?php
namespace Yege;

/**
 * 记录信息类
 *
 * 方法提供
 * getUserMessageList   获取消息记录信息
 * getUserPointLogList  获取积分记录信息
 */

class Message{

    public $user_id = 0; //用户id

    private $user_table = ""; //相关用户表
    private $user_message_table = ""; //相关用户信息表
    private $user_points_log_table = ""; //相关用户积分记录表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->user_table = C("TABLE_NAME_USER");
        $this->user_message_table = C("TABLE_NAME_USER_MESSAGE");
        $this->user_points_log_table = C("TABLE_NAME_USER_POINTS_LOG");
    }

    /**
     * 获取消息记录信息
     * @param array $where 搜索条件
     * @param int $is_all 是否要全部数据
     * @param int $page 页码($is_all = 0 时生效)
     * @param int $num 单页数量($is_all = 0 时生效)
     * @return array $list 结果返回
     */
    public function getUserMessageList($where = [],$is_all = 1,$page = 1,$num = 20){
        $list = [];

        $is_all = empty($is_all) ? 0 : 1;

        //全部数据获取
        if($is_all == 1){
            $list = M($this->user_message_table." as user_message")
                ->field("user_message.*,user.mobile,user.nick_name")
                ->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = user_message.user_id")
                ->where($where)
                ->order("user_message.id DESC")
                ->select();
        }else{ //部分数据获取

            $page = check_int($page);
            if($page <= 0){
                $page = 1;
            }
            $num = check_int($num);
            if($num <= 0){
                $num = 1;
            }

            $limit = ($page-1)*$num.",".$num;

            $list = M($this->user_message_table." as user_message")
                ->field("user_message.*,user.mobile,user.nick_name")
                ->join("left join ".C("DB_PREFIX").$this->user_table." as user on user.id = user_message.user_id")
                ->where($where)
                ->limit($limit)
                ->order("user_message.id DESC")
                ->select();
        }

        return $list;
    }

    /**
     * 清除用户消息提醒
     * @param int $user_id 用户id
     */
    public function cleanUserMessageTip(){
        $user_id = check_int($this->user_id);
        M($this->user_table)->where(["user_id"=>$user_id])->save(["is_message_tip"=>0]);
    }

    /**
     * 获取积分记录信息
     * @param array $where 搜索条件
     * @param int $is_all 是否要全部数据
     * @param int $page 页码($is_all = 0 时生效)
     * @param int $num 单页数量($is_all = 0 时生效)
     * @return array $result 结果返回
     */
    public function getUserPointLogList($where = [],$is_all = 1,$page = 1,$num = 20){
        $result = ['state'=>0,'message'=>'未知错误','list'=>[]];

        $is_all = empty($is_all) ? 0 : 1;

        //全部数据获取
        if($is_all == 1){
            $result['list'] = M($this->user_points_log_table)
                ->where($where)
                ->order("id DESC")
                ->select();
        }else{ //部分数据获取

            $page = check_int($page);
            if($page <= 0){
                $page = 1;
            }
            $num = check_int($num);
            if($num <= 0){
                $num = 1;
            }

            $limit = ($page-1)*$num.",".$num;

            $result['list'] = M($this->user_points_log_table)
                ->where($where)
                ->limit($limit)
                ->order("id DESC")
                ->select();
        }

        $result['state'] = 1;
        $result['message'] = '获取成功';

        return $result;
    }

}
