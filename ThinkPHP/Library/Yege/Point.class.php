<?php
namespace Yege;

/**
 * 积分类
 *
 * 方法提供
 * updateUserPoints     更新用户积分信息
 * getUserPointInfo     获取用户积分信息
 * getPointInfoByTag    获取指定积分标记的详细信息
 */

class Point{

    //提供于外面赋值或读取的相关参数
    public $user_id = 0; //用户id
    public $log = ""; //日志信息
    public $points = 0; //操作积分
    public $operation_tab = ""; //积分标记
    public $remark = ""; //详细备注信息

    private $user_table = ""; //相关用户表
    private $user_points_table = ""; //相关用户积分表
    private $user_points_log_table = ""; //相关用户积分日志表

    private $point_info = []; //积分信息

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->user_table = C("TABLE_NAME_USER");
        $this->user_points_table = C("TABLE_NAME_USER_POINTS");
        $this->user_points_log_table = C("TABLE_NAME_USER_POINTS_LOG");
    }

    /**
     * 更新用户积分信息
     * @return array $result 结果返回
     */
    public function updateUserPoints(){
        $result = ['state'=>0,'message'=>'未知错误'];

        $operation_tab = trim($this->operation_tab);
        //根据标记获取积分详情
        $operation_tab_result = $this->getPointInfoByTag();
        if($operation_tab_result['state'] == 1){

            //更新积分
            $this->points = $operation_tab_result['points'];

            $user_id = intval($this->user_id);
            $points = intval($this->points);
            $log = trim($this->log);
            $remark = trim($this->remark);
            if(empty($log)){
                $log = $operation_tab_result['log'];
            }
            if(empty($remark)){
                $remark = $operation_tab_result['remark'];
            }

            //基础参数检验
            if(empty($user_id) || empty($points) || empty($operation_tab)){
                $result['message'] = "参数缺失";
                return $result;
            }

            //获取用户信息
            $user_info = $point_info = [];
            $user_obj = new \Yege\User();
            $user_obj->user_id = $user_id;
            $user_info = $user_obj->getUserInfo();
            if($user_info['state'] == 1){
                //获取积分信息
                $point_info = $this->getUserPointInfo();
                if($point_info['state'] == 1){
                    $result_point = $point_info['result']['point_value'] + $points;
                    if($result_point >= 0){
                        //更新积分信息
                        $save = $where = array();
                        $save['points'] = $result_point;
                        $save['updatetime'] = time();
                        $where['user_id'] = $user_id;
                        if(M($this->user_points_table)->where($where)->save($save)){
                            //日志记录
                            if(empty($log)){
                                $log = "未知操作";
                            }

                            $add = array();
                            $add['user_id'] = $user_id;
                            $add['log'] = $log;
                            $add['remark'] = $remark;
                            $add['points'] = $points;
                            $add['result_points'] = $result_point;
                            $add['operation_tab'] = $operation_tab;
                            $add['inputtime'] = time();
                            M($this->user_points_log_table)->add($add);

                            $result['state'] = 1;
                            $result['message'] = "更新成功";

                        }else{
                            $result['message'] = "积分信息变更失败";
                        }
                    }else{
                        $result['message'] = "积分错误";
                        $result['not_enough_point'] = 1;
                        $result['should_point'] = $points;
                        $result['has_point'] = $point_info['result']['point_value'];
                    }
                }else{
                    $result['message'] = $point_info['message'];
                }
            }else{
                $result['message'] = $user_info['message'];
            }

        }else{
            $result['message'] = $operation_tab_result['message'];
            if(!empty($operation_tab_result['not_in_time'])){
                $result['not_in_time'] = $operation_tab_result['not_in_time'];
            }
        }

        return $result;
    }

    /**
     * 获取用户积分信息
     * @return array $result 结果返回
     */
    public function getUserPointInfo(){
        $result = ['state'=>0,'message'=>'未知错误'];

        $user_id = intval($this->user_id);
        if(!empty($user_id)){
            $info = $where = [];
            $where['user_id'] = $user_id;
            $info = M($this->user_points_table)->where($where)->find();
            if(empty($info['id'])){
                //没有数据，就为用户生成新数据
                $add = [];
                $add['user_id'] = $user_id; //用户id
                $add['points'] = 0; //初始积分
                $add['updatetime'] = time(); //最后更新时间
                $info['id'] = M($this->user_points_table)->add($add);
                if(!empty($info['id'])){
                    $info['user_id'] = $add['user_id'];
                    $info['points'] = $add['points'];
                    $info['updatetime'] = $add['updatetime'];
                }else{
                    $result['message'] = '新数据添加失败';
                    return $result;
                }
            }

            $result['state'] = 1;
            $result['message'] = '获取成功';
            $info_result = [];
            $info_result['point_user_id'] = $info['user_id'];
            $info_result['point_value'] = $info['points'];
            $result['result'] = $info_result;
            $this->point_info = $info;

        }else{
            $result['message'] = '参数错误(user_id)';
        }

        return $result;
    }

    /**
     * 获取指定积分标记的详细信息
     * @return $result 结果返回
     */
    public function getPointInfoByTag(){
        $result = ['state'=>0,'message'=>'未知错误'];

        $operation_tab = trim($this->operation_tab);
        $point_activity_list = C("POINT_ACTIVITY_LIST");
        if(!empty($point_activity_list[$operation_tab])){
            $point_info = $point_activity_list[$operation_tab];
            $points = $point_info['point'];
            $log = $point_info['log'];
            $remark = $point_info['remark'];

            //开始结束时间
            $start_time = empty($point_info['start_time']) ? 0 : strtotime($point_info['start_time']." 00:00:00");
            $end_time = empty($point_info['end_time']) ? 0 : strtotime($point_info['end_time']." 23:59:59");

            if(!empty($start_time) || !empty($end_time)){
                //受时间限制的积分活动
                $now_time = time();
                //先判断开始时间
                if(!empty($start_time)){
                    if($now_time < $start_time){
                        $result['message'] = "积分活动还未开始";
                        $result['not_in_time'] = 1;
                        return $result;
                    }
                }

                //再判断结束时间
                if(!empty($end_time)){
                    if($now_time > $end_time){
                        $result['message'] = "积分活动已经结束";
                        $result['not_in_time'] = 1;
                        return $result;
                    }
                }

                if(empty($points)){
                    $result['message'] = "错误的积分值";
                    return $result;
                }else{
                    if($points == "~"){
                        //自定义积分就拿传进来的值
                        $points = $this->points;
                        if(empty($points)){
                            $result['message'] = "需要先定义积分值";
                            return $result;
                        }
                    }
                }
            }else{
                //不受时间限制的积分活动
                if(empty($points)){
                    $result['message'] = "错误的积分值";
                    return $result;
                }else{
                    if($points == "~"){
                        //自定义积分就拿传进来的值
                        $points = $this->points;
                        if(empty($points)){
                            $result['message'] = "需要先定义积分值";
                            return $result;
                        }
                    }
                }
            }

            //跑到这表成功
            $result['state'] = 1;
            $result['message'] = "数据获取成功";
            $result['points'] = $points;
            $result['operation_tab'] = $operation_tab;
            $result['log'] = $log;
            $result['remark'] = $remark;

        }else{
            $result['message'] = "未能找到对应的积分信息";
        }

        return $result;
    }

}
