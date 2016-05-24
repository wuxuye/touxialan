<?php
namespace Yege;

/**
 * 活动数据操作类
 *
 * 相关活动方法：
 *	每日问答
 * 		getQuestionBankList		    获取题库列表方法
 * 		addQuestion				    添加题目方法
 * 		getQuestionInfo			    获取题目详情方法
 *      getIsPublishQuestionInfo    拿到正在发布的题目详情
 *      analyseOption               解析选项详情
 *      editQuestion                编辑题目方法
 * 		updateQuestionState		    改变题目状态方法
 *      publishQuestion		        发布题目方法
 *      setNextPublish              标记次日发布
 */

class ActivityDataOperation{

    private $activity_question_bank_table = '';

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->activity_question_bank_table = C("TABLE_NAME_ACTIVITY_QUESTION_BANK");
    }

    //==========每日问答==========
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
            ->order("question.is_publish,question.is_next,question.id DESC")
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
     * 拿到正在发布的题目详情
     * @return array $result 题目信息
     */
    public function getIsPublishQuestionInfo(){
        $result = [];

        $start_time = $end_time = 0;
        $start_time = strtotime(date("Y-m-d 00:00:00",time()));
        $end_time = strtotime(date("Y-m-d 23:59:59",time()));

        $where = [];
        $where['is_publish'] = 1;
        $where[1][]['last_publish_time'] = ['egt',$start_time];
        $where[1][]['last_publish_time'] = ['elt',$end_time];
        $where[1]['_logic'] = "OR";
        $result = M($this->activity_question_bank_table)->where($where)->find();

        if(empty($result['id'])){
            $result = [];
        }

        $result['option_info_result'] = $this->analyseOption($result['option_info']);

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
        if($info['is_publish'] == 1){
            $result['message'] = '题目正在发布中，无法修改';
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
            $option_info = json_encode(trim($param['option_info']));
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

        $save['is_publish'] = 0; //取消正在发布状态
        $save['state'] = C("STATE_ACTIVITY_QUESTION_BANK_WAIT"); //状态会被变回待发布
        $save['updatetime'] = time();

        if(M($this->activity_question_bank_table)->where($where)->save($save)){
            $result['state'] = 1;
            $result['message'] = '编辑成功';
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
     * 获取发布题目算法
     * @return int $result_id 一个可发布的作业id
     */
    public function getPublishId(){
        $result_id = 0;

        //首先看今天是否已经有题目发布
        $now_publish = $this->getIsPublishQuestionInfo();
        if(!empty($now_publish)){
            //获取失败，今天已有作业发布
            return 0;
        }
        //再拿到题库里的 正常状态的 数据 按次日发布>最后发布时间从小到大排序


        return $result_id;
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
            $result['message'] = '当前状态无法发布作业';
            return $result;
        }
        if($info['is_publish'] == 1){
            $result['message'] = '作业已经是正在发布状态';
            return $result;
        }

        //准备发布作业
        M()->startTrans();
        //所有正常作业的次日发布状态与发布状态改为0
        $where = $save = [];
        $where['state'] = C("STATE_ACTIVITY_QUESTION_BANK_NORMAL");
        $save['is_next'] = 0;
        $save['is_publish'] = 0;
        $save['updatetime'] = time();
        if(M($this->activity_question_bank_table)->where($where)->save($save)){
            //指定作业的发布状态改为1
            $where = $save = [];
            $where['id'] = $id;
            $save['is_publish'] = 1;
            $save['last_publish_time'] = $save['updatetime'] = time();
            if(M($this->activity_question_bank_table)->where($where)->save($save)){
                $result['state'] = 1;
                $result['message'] = '发布成功';
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
            $result['message'] = '作业已经被标记为次日发布';
            return $result;
        }

        //准备标记次日发布
        M()->startTrans();
        //所有正常作业的次日发布状态改为0
        $where = $save = [];
        $where['state'] = C("STATE_ACTIVITY_QUESTION_BANK_NORMAL");
        $save['is_next'] = 0;
        $save['updatetime'] = time();
        if(M($this->activity_question_bank_table)->where($where)->save($save)){
            //指定作业的次日发布状态改为1
            $where = $save = [];
            $where['id'] = $id;
            $save['is_next'] = 1;
            $save['updatetime'] = time();
            if(M($this->activity_question_bank_table)->where($where)->save($save)){
                $result['state'] = 1;
                $result['message'] = '设置成功';
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

}
