<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台活动控制器
 *
 * 相关活动方法：
 *	每日问答
 *      questionBankList                            题库列表
 *      disposeQuestionBankListPostParam(私有)        题库列表参数判断
 *      addQuestion                                 添加题目方法
 *      editQuestion                                编辑题目方法
 */

class ActivityController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->activity_data_operation_obj = new \Yege\ActivityDataOperation();
    }

    //==========每日问答==========
    /**
     * 题库列表
     */
    public function questionBankList(){
        $dispose = array();
        $dispose = $this->disposeQuestionBankListPostParam();

        //单页数量
        $page_num = C("ADMIN_QUESTION_BANK_LIST_PAGE_SHOW_NUM");

        $list = array();
        $list = $this->activity_data_operation_obj->getQuestionBankList($dispose['where'],$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list['list']) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $this->activity_data_operation_obj->getQuestionBankList($dispose['where'],$dispose['page'],$page_num);
        }

        P($list['list']);

        //分页
        $page_obj = new \Yege\Page($dispose['page'],$list['count'],$page_num);

        $this->assign("question_tab_list",C("ACTIVITY_QUESTION_TAB_LIST"));
        $this->assign("question_bank_state_list",C("STATE_ACTIVITY_QUESTION_BANK_STATE_LIST"));
        $this->assign("question_bank_wait",C("STATE_ACTIVITY_QUESTION_BANK_WAIT"));
        $this->assign("list",$list['list']);
        $this->assign("page",$page_obj->show());
        $this->assign("dispose",$dispose);
        $this->display();
    }

    /**
     * 题库列表参数判断
     * @return array $result
     */
    private function disposeQuestionBankListPostParam(){
        $result = array();
        $result['where'] = array();
        $result['page'] = 1;

        $post_info = array();
        $post_info = I("post.");

        //页码
        if(!empty($post_info['search_now_page'])){
            $result['page'] = $post_info['search_now_page'];
        }

        $where = array();

//        //时间搜索类型
//        $post_info['search_start_time'] = trim($post_info['search_start_time']);
//        $post_info['search_end_time'] = trim($post_info['search_end_time']);
//        $post_info['search_time_type'] = intval($post_info['search_time_type']);
//        if(!empty($post_info['search_start_time']) || !empty($post_info['search_end_time'])){
//            $start_time = is_date($post_info['search_start_time'])?strtotime($post_info['search_start_time']):0;
//            $end_time = is_date($post_info['search_end_time'])?strtotime(date("Y-m-d 23:59:59",strtotime($post_info['search_end_time']))):0;
//            switch($post_info['search_time_type']){
//                case 1: //商品添加时间
//                    if(!empty($start_time)){
//                        $where['goods.inputtime'][] = array("egt",$start_time);
//                        $result['search_start_time'] = $post_info['search_start_time'];
//                    }
//                    if(!empty($end_time)){
//                        $where['goods.inputtime'][] = array("elt",$end_time);
//                        $result['search_end_time'] = $post_info['search_end_time'];
//                    }
//                    break;
//            }
//            $result['search_time_type'] = $post_info['search_time_type'];
//        }
//
//        //字段类型搜索
//        $post_info['search_info'] = trim($post_info['search_info']);
//        $post_info['search_info_type'] = intval($post_info['search_info_type']);
//        if(!empty($post_info['search_info'])){
//            switch($post_info['search_info_type']){
//                case 1: //商品id
//                    $post_info['search_info'] = intval($post_info['search_info']);
//                    $where['goods.id'] = $post_info['search_info'];
//                    break;
//                case 2: //商品归属(手机)
//                    $where['user.mobile'] = array('like',"%".$post_info['search_info']."%");
//                    break;
//            }
//            $result['search_info_type'] = $post_info['search_info_type'];
//            $result['search_info'] = $post_info['search_info'];
//        }
//
//        //商品名称搜索
//        $post_info['search_goods_name'] = trim($post_info['search_goods_name']);
//        if(!empty($post_info['search_goods_name'])){
//            $where['goods.name'] = array('like',"%".$post_info['search_goods_name']."%");
//            $result['search_goods_name'] = $post_info['search_goods_name'];
//        }
//
//        //商品扩展名搜索
//        $post_info['search_ext_name'] = trim($post_info['search_ext_name']);
//        if(!empty($post_info['search_ext_name'])){
//            $where['goods.ext_name'] = array('like',"%".$post_info['search_ext_name']."%");
//            $result['search_ext_name'] = $post_info['search_ext_name'];
//        }

        $result['where'] = $where;

        return $result;
    }

    /**
     * 添加题目
     */
    public function addQuestion(){

        if(IS_POST){
            $post_info = I("post.");

            //选项信息处理
            $option_info = "";
            $option_info = $this->doOptionInfo($post_info['option'],$post_info['is_right']);
            if(empty($option_info)){
                $this->error("请先正确填写4个选项 并 选择一个正确答案");
            }

            $post_info['option_info'] = $option_info;

            //图片判断
            $image_temp = array();
            if(!empty($_FILES['question_image']['name'])){
                $image_config = [];
                $image_config['folder'] = C("ADMIN_SAVE_IMAGE_QUESTION");
                $image_obj = new \Yege\Image($image_config);
                $image_temp = $image_obj->upload_image($_FILES['question_image']);
            }
            if(!empty($image_temp['url'])){
                $post_info['question_image'] = $image_temp['url'];
            }

            $add_result = [];
            $add_result = $this->activity_data_operation_obj->addQuestion($post_info);
            if($add_result['state'] == 1){
                $this->success("添加成功");
            }else{
                $this->error("添加失败：".$add_result['message']);
            }

        }else{
            $this->assign("tag_list",C("ACTIVITY_QUESTION_TAB_LIST"));
            $this->display();
        }

    }

    /**
     * 选项处理
     * @param array $option 选项列表
     * @param int $is_right 正确选项
     * @return string $result 结果返回
     */
    private function doOptionInfo($option = [],$is_right = 0){
        $result = "";

        $option_info = [];
        //组装4个选项
        $option_info[1] = trim($option[1]);
        $option_info[2] = trim($option[2]);
        $option_info[3] = trim($option[3]);
        $option_info[4] = trim($option[4]);
        //4个选项都不能为空
        foreach($option_info as $info){
            if(empty($info)){
                return [];
            }
        }
        //正确选项检测
        if(!empty($option_info[$is_right])){
            $data = [
                'option'=>$option_info,
                'is_right'=>$is_right,
            ];
            $result = json_encode($data);
        }

        return $result;
    }

    /**
     * 编辑题目
     * @param int $id 题目id
     */
    public function editQuestion($id = 0){

        //获取题目信息
        $info = [];
        $info = $this->activity_data_operation_obj->getQuestionInfo();

        if(!empty($info['id'])){

            if(IS_POST){
                $post_info = I("post.");

                $post_info['id'] = $id;

                //图片判断
                $image_temp = array();
                if(!empty($_FILES['question_image']['name'])){
                    $image_obj = new \Yege\Image();
                    $image_temp = $image_obj->upload_image($_FILES['question_image']);
                }
                if(!empty($image_temp['url'])){
                    $post_info['question_image'] = $image_temp['url'];
                }

                $edit_result = [];
                $edit_result = $this->activity_data_operation_obj->editQuestion($post_info);
                if($edit_result['state'] == 1){
                    $this->success("编辑成功");
                }else{
                    $this->error("编辑失败：".$edit_result['message']);
                }

            }else{
                $this->assign("tag_list",C("ACTIVITY_QUESTION_TAB_LIST"));
                $this->assign("info",$info);
                $this->display();
            }
        }else{
            $this->error("未能获取信息");
        }
    }

}