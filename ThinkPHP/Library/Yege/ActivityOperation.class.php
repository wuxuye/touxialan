<?php
namespace Yege;

/**
 * 活动 - 每日答题类
 *
 * 相关活动方法：
 *  getQuestionBankList		    获取题库列表方法
 *  addQuestion				    添加题目方法
 *  getQuestionInfo			    获取题目详情方法
 *  getIsPublishQuestionInfo    拿到正在发布的题目详情（会触发问题发布）
 *  analyseOption               解析选项详情
 *  editQuestion                编辑题目方法
 *  updateQuestionState		    改变题目状态方法
 *  publishQuestion		        发布题目方法
 *  setNextPublish              标记次日发布
 *  deleteQuestionImage         删除题目图片
 */

class ActivityOperation{

    private $activity_question_bank_table = '';

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->activity_question_bank_table = C("TABLE_NAME_ACTIVITY_QUESTION_BANK");
    }

    /**
     * 获取题库列表方法
     * @param array $where where条件数组
     * @param int $page 分页页码
     * @param int $num 一页显示数量
     * @return array $result 结果返回
     */
    public function getQuestionBankList($where = [],$page = 1,$num = 20){
        $result = ['state'=>0,'message'=>'未知错误','list'=>[],'count'=>0];

        //列表信息获取
        $limit = ($page-1)*$num.",".$num;
        $list = [];

        //排序规则 当前正在发布 > 次日发布 > id最大

        $list = M($this->activity_question_bank_table." as question")
            ->field("question.*")
            ->where($where)
            ->limit($limit)
            ->order([
                "question.is_publish DESC",
                "question.is_next DESC",
                "question.last_publish_time DESC",
            ])
            ->select();

        //列表数据处理
        foreach($list as $key => $val){
            $list[$key]['option_info_result'] = $this->analyseOption($val['option_info']);
        }

        $result['list'] = $list;

        //数量获取
        $count = M($this->activity_question_bank_table." as question")
            ->where($where)
            ->count();
        $result['count'] = empty($count) ? 0 : $count;

        $result['state'] = 1;
        $result['message'] = "获取成功";

        return $result;
    }

    /**
     * 添加题目方法
     * @param array $param 参与逻辑的相关参数
     * @return array $result 结果返回
     */
    public function addQuestion($param = []){
        $result = ['state'=>0,'message'=>'未知错误'];

        //对各种参数进行检验
        //标签
        $question_tab = trim($param['question_tab']);
        if(empty($question_tab) || empty(C("ACTIVITY_QUESTION_TAB_LIST")[$question_tab])){
            $result['message'] = '请填写正确的问题标签';
            return $result;
        }
        //题目内容
        $question_content = trim($param['question_content']);
        if(empty($question_content)){
            $result['message'] = '请填写正确的问题描述';
            return $result;
        }

        //选项信息
        $option_info = json_encode($param['option_info']);
        if(empty($option_info)){
            $result['message'] = '请填写正确的选项信息';
            return $result;
        }

        //其他信息
        $question_image = trim($param['question_image']);

        $add = [];
        $add['question_tab'] = $question_tab;
        $add['question_content'] = $question_content;
        $add['question_image'] = $question_image;
        $add['option_info'] = $option_info;
        $add['inputtime'] = $add['updatetime'] = time();

        if(M($this->activity_question_bank_table)->add($add)){
            $result['state'] = 1;
            $result['message'] = '添加成功';
        }else{
            $result['message'] = '数据添加失败';
        }

        return $result;
    }

    /**
     * 获取题目详情方法
     * @param int $id 题目id
     * @return array $result 题目信息
     */
    public function getQuestionInfo($id = 0){
        $result = [];

        $where = [];
        $where['id'] = $id;
        $result = M($this->activity_question_bank_table)->where($where)->find();

        $result['option_info_result'] = $this->analyseOption($result['option_info']);

        return $result;
    }

    /**
     * 拿到正在发布的题目详情（会触发问题发布）
     * @return array $result 题目信息
     */
    public function getIsPublishQuestionInfo(){
        $result = [];

        $start_time = $end_time = 0;
        $start_time = strtotime(date("Y-m-d 00:00:00",time()));
        $end_time = strtotime(date("Y-m-d 23:59:59",time()));

        //获取 发布状态是1 发布时间是今天的题目信息
        $data = $where = [];
        $where['is_publish'] = 1;
        $where['last_publish_time'][] = ['egt',$start_time];
        $where['last_publish_time'][] = ['elt',$end_time];
        $data = M($this->activity_question_bank_table)->field("id")->where($where)->find();

        //没有信息 就开始一个获取题目的算法
        if(empty($data['id'])){

            //天数偏移值
            $deviation_day = C("ADMIN_ACTIVITY_QUESTION_PUBLISH_DEVIATION_DAY");

            //先拿到题库里的 正常状态的 数据
            $data = $where = [];
            $where['state'] = C("STATE_ACTIVITY_QUESTION_BANK_NORMAL");
            //不要最近 $deviation_day 天内发布过的题目
            $where['last_publish_time'] = ['lt',strtotime(date("Y-m-d 00:00:00",time()))-$deviation_day*24*60*60];
            $data = M($this->activity_question_bank_table)->field("id,is_next,last_publish_time")->where($where)->select();

            //循环检测
            $weight = []; //权重
            $multiple_list = C("ACTIVITY_QUESTION_PUBLISH_MULTIPLE_LIST"); //倍数列表
            foreach($data as $question){
                //如果有标记次日发布的 就直接发布这个问题
                if($question['is_next'] == 1){
                    $publish_result = [];
                    $publish_result = $this->publishQuestion($question['id']);
                    if($publish_result['state'] == 1){
                        $result = $this->getQuestionInfo($question['id']);
                    }else{
                        //发布失败记录错误日志
                        add_wrong_log("活动 - 每日问答 逻辑出错。涉及问题id：".$question['id'].",发布问题失败：".$publish_result['message']);
                    }
                    return $result;
                }else{
                    //次于 标记次日发布 优先级的判断 新题目就直接发布（最后发布时间为0）
                    if(empty($question['last_publish_time'])){
                        $publish_result = [];
                        $publish_result = $this->publishQuestion($question['id']);
                        if($publish_result['state'] == 1){
                            $result = $this->getQuestionInfo($question['id']);
                        }else{
                            //发布失败记录错误日志
                            add_wrong_log("活动 - 每日问答 逻辑出错。涉及问题id：".$question['id'].",发布问题失败：".$publish_result['message']);
                        }
                        return $result;
                    }else{
                        //根据偏差天数 为权重数组赋值
                        $reference_day = strtotime(date("Y-m-d 00:00:00",time()))-$deviation_day*24*60*60; //参考天数
                        $question_day = intval(($reference_day - strtotime(date("Y-m-d 00:00:00",$question['last_publish_time']))) / (24*60*60));
                        $multiple = 0; //结果倍数
                        foreach($multiple_list as $key => $val){
                            if($question_day >= $key){
                                $multiple = $val;
                            }else{
                                break;
                            }
                        }
                        //跟进倍数给权重数组赋值
                        $max = $multiple*$question_day;
                        for($i = 0;$i < $max;$i++){
                            $weight[] = $question['id'];
                        }
                        //最后在权重数组中随机获取一个数据
                        $question_id = $weight[array_rand($weight)];
                        $publish_result = [];
                        $publish_result = $this->publishQuestion($question_id);
                        if($publish_result['state'] == 1){
                            $result = $this->getQuestionInfo($question_id);
                        }else{
                            //发布失败记录错误日志
                            add_wrong_log("活动 - 每日问答 逻辑出错。涉及问题id：".$question_id.",发布问题失败：".$publish_result['message']);
                        }
                    }
                }
            }

        }else{
            $result = $this->getQuestionInfo($data['id']);
        }

        return $result;
    }

    /**
     * 解析选项详情
     * @param string $option_str 选项详情
     * @return array $result 解析结果
     */
    public function analyseOption($option_str = ""){
        $result = [];
        $data = [];
        $data = json_decode($option_str,true);

        //数组检测
        if(!empty($data['option'][1]) && !empty($data['option'][2]) &&
            !empty($data['option'][3]) && !empty($data['option'][4]) &&
            !empty($data['option'][$data['is_right']])){
            $result = $data;
        }

        return $result;
    }

    /**
     * 编辑题目方法
     * @param array $param 参与逻辑的相关参数
     * @return array $result 结果返回
     */
    public function editQuestion($param = []){
        $result = ['state'=>0,'message'=>'未知错误'];

        $where = $save = [];
        //对各种参数进行检验
        //存在性
        $id = intval($param['id']);
        $info = [];
        $info = $this->getQuestionInfo($id);
        if(empty($info['id'])){
            $result['message'] = '未能获取问题详情';
            return $result;
        }
        $where['id'] = $info['id'];

        //标签
        if(!empty($param['question_tab'])){
            $question_tab = trim($param['question_tab']);
            if(empty($question_tab) || empty(C("ACTIVITY_QUESTION_TAB_LIST")[$question_tab])){
                $result['message'] = '请填写正确的问题标签';
                return $result;
            }
            $save['question_tab'] = $question_tab;
        }

        //题目内容
        if(!empty($param['question_content'])){
            $question_content = trim($param['question_content']);
            if(empty($question_content)){
                $result['message'] = '请填写正确的问题描述';
                return $result;
            }
            $save['question_content'] = $question_content;
        }

        //选项信息
        if(!empty($param['option_info'])){
            $option_info = json_encode($param['option_info']);
            if(empty($option_info)){
                $result['message'] = '请填写正确的选项信息';
                return $result;
            }
            $save['option_info'] = $option_info;
        }

        //其他信息
        if(!empty($param['question_image'])){
            $question_image = trim($param['question_image']);
            $save['question_image'] = $question_image;
        }

        if($info['is_publish'] != 1){ //不在发布状态 就修改一些参数
            $save['is_publish'] = 0; //取消正在发布状态
            $save['is_next'] = 0; //取消下次发布状态
            $save['state'] = C("STATE_ACTIVITY_QUESTION_BANK_WAIT"); //状态会被变回待审核
        }

        $save['updatetime'] = time();

        if(M($this->activity_question_bank_table)->where($where)->save($save)){
            $result['state'] = 1;
            $result['message'] = '编辑成功';

            //操作日志记录
            add_operation_log("id为：".$id."的问题，相关信息被修改，涉及参数的json格式为：".json_encode($save),C("ACTIVITY_QUESTION_FOLDER_NAME"));

        }else{
            $result['message'] = '数据修改失败';
        }

        return $result;
    }

    /**
     * 改变题目状态方法
     * @param int $id 题目id
     * @param int $state 要改变的状态值
     * @return array $result 结果返回
     */
    public function updateQuestionState($id = 0,$state = 0){
        $result = ['state'=>0,'message'=>'未知错误'];

        $where = $save = [];
        //对各种参数进行检验
        //存在性
        $id = intval($id);
        $info = [];
        $info = $this->getQuestionInfo($id);
        if(empty($info['id'])){
            $result['message'] = '未能获取问题详情';
            return $result;
        }
        if($info['is_publish'] == 1){
            $result['message'] = '题目正在发布中，无法修改';
            return $result;
        }
        $where['id'] = $info['id'];

        if(!empty(C("STATE_ACTIVITY_QUESTION_BANK_STATE_LIST")[$state])){
            $save['state'] = $state;
            $save['updatetime'] = time();

            if(M($this->activity_question_bank_table)->where($where)->save($save)){

                //操作日志记录
                add_operation_log("id为：".$id."的问题，状态被更改为：".C("STATE_ACTIVITY_QUESTION_BANK_STATE_LIST")[$state]."(".$state.")",C("ACTIVITY_QUESTION_FOLDER_NAME"));

                $result['state'] = 1;
                $result['message'] = '修改成功';
            }else{
                $result['message'] = '数据修改失败';
            }

        }else{
            $result['message'] = '错误的状态';
        }

        return $result;
    }

    /**
     * 发布题目方法
     * @param int $id 题目id
     * @return array $result 结果返回
     */
    public function publishQuestion($id = 0){
        $result = ['state'=>0,'message'=>'未知错误'];

        //存在性
        $id = intval($id);
        $info = [];
        $info = $this->getQuestionInfo($id);
        //一系列数据检验
        if(empty($info['id'])){
            $result['message'] = '未能获取问题详情';
            return $result;
        }
        if($info['state'] != C("STATE_ACTIVITY_QUESTION_BANK_NORMAL")){
            $result['message'] = '当前状态无法发布问题';
            return $result;
        }
        if($info['is_publish'] == 1){
            $result['message'] = '问题已经是正在发布状态';
            return $result;
        }

        //准备发布问题
        M()->startTrans();
        //所有正常问题的次日发布状态与发布状态改为0
        $where = $save = [];
        $where['state'] = C("STATE_ACTIVITY_QUESTION_BANK_NORMAL");
        $save['is_next'] = 0;
        $save['is_publish'] = 0;
        $save['updatetime'] = time();
        if(M($this->activity_question_bank_table)->where($where)->save($save)){
            //指定问题的发布状态改为1
            $where = $save = [];
            $where['id'] = $id;
            $save['is_publish'] = 1;
            $save['last_publish_time'] = $save['updatetime'] = time();
            if(M($this->activity_question_bank_table)->where($where)->save($save)){
                $result['state'] = 1;
                $result['message'] = '发布成功';

                //操作日志记录
                add_operation_log("id为：".$id."的问题，发布成功",C("ACTIVITY_QUESTION_FOLDER_NAME"));

                M()->commit();
            }else{
                M()->rollback();
                $result['message'] = '数据更新失败';
            }
        }else{
            M()->rollback();
            $result['message'] = '批量更新数据失败';
        }

        return $result;
    }

    /**
     * 标记次日发布
     * @param int $id 题目id
     * @return array $result 结果返回
     */
    public function setNextPublish($id = 0){
        $result = ['state'=>0,'message'=>'未知错误'];

        //存在性
        $id = intval($id);
        $info = [];
        $info = $this->getQuestionInfo($id);
        //一系列数据检验
        if(empty($info['id'])){
            $result['message'] = '未能获取问题详情';
            return $result;
        }
        if($info['state'] != C("STATE_ACTIVITY_QUESTION_BANK_NORMAL")){
            $result['message'] = '当前状态无法标记次日发布';
            return $result;
        }
        if($info['is_next'] == 1){
            $result['message'] = '问题已经被标记为次日发布';
            return $result;
        }

        //准备标记次日发布
        M()->startTrans();
        //所有正常问题的次日发布状态改为0
        $where = $save = [];
        $where['state'] = C("STATE_ACTIVITY_QUESTION_BANK_NORMAL");
        $save['is_next'] = 0;
        $save['updatetime'] = time();
        if(M($this->activity_question_bank_table)->where($where)->save($save)){
            //指定问题的次日发布状态改为1
            $where = $save = [];
            $where['id'] = $id;
            $save['is_next'] = 1;
            $save['updatetime'] = time();
            if(M($this->activity_question_bank_table)->where($where)->save($save)){
                $result['state'] = 1;
                $result['message'] = '设置成功';

                //操作日志记录
                add_operation_log("id为：".$id."的问题，被标记为次日发布",C("ACTIVITY_QUESTION_FOLDER_NAME"));

                M()->commit();
            }else{
                M()->rollback();
                $result['message'] = '数据更新失败';
            }
        }else{
            M()->rollback();
            $result['message'] = '批量更新数据失败';
        }

        return $result;
    }

    /**
     * 删除题目图片
     * @param int $id 题目id
     * @return array $result 结果返回
     */
    public function deleteQuestionImage($id = 0){
        $result = ['state'=>0,'message'=>'未知错误'];

        $where = $save = [];
        $where['id'] = $id;
        $save['question_image'] = '';
        $save['updatetime'] = time();
        if(M($this->activity_question_bank_table)->where($where)->save($save)){
            $result['state'] = 1;
            $result['message'] = '删除成功';

            //操作日志记录
            add_operation_log("id为：".$id."的问题，图片信息被删除",C("ACTIVITY_QUESTION_FOLDER_NAME"));

        }else{
            $result['message'] = '删除失败';
        }

        return $result;
    }

}
