<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台标签管理控制器
 *
 * 相关方法
 * tagsList                  标签列表
 * disposePostParam（私有）   标签列表参数判断
 * addTag                    标签添加
 * editTag                   编辑标签
 */

class TagsController extends PublicController {

    private $tag_model = "";

    public function _initialize(){
        parent::_initialize();

        $this->tag_model = D("Tags");

    }

    /**
     * 标签列表
     */
    public function tagsList(){

        $dispose = array();
        $dispose = $this->disposePostParam();

        //单页数量
        $page_num = C("ADMIN_TAGS_LIST_PAGE_SHOW_NUM");

        $list = array();
        $list = $this->tag_model->getTagsList($dispose['where'],$dispose['page'],$page_num);

        //数据为空，页码大于1，就减1再查一遍
        if(empty($list['list']) && $dispose['page'] > 1){
            $dispose['page'] --;
            $list = $this->tag_model->getTagsList($dispose['where'],$dispose['page'],$page_num);
        }

        //分页
        $page_obj = new \Yege\Page($dispose['page'],$list['count'],$page_num);

        $this->assign("list",$list['list']);
        $this->assign("page",$page_obj->show());
        $this->assign("dispose",$dispose);
        $this->display();
    }

    /**
     * 标签列表参数判断
     * @return array $result
     */
    private function disposePostParam(){
        $result = array();
        $result['where'] = array();
        $result['page'] = 1;

        $post_info = array();
        $post_info = I("post.");

        $this->search_now_page = 1;
        if(!empty($post_info['search_now_page'])){
            $this->search_now_page = $post_info['search_now_page'];
            $result['page'] = $this->search_now_page;
        }

        return $result;
    }

    /**
     * 编辑标签
     * @param int $id 标签id
     */
    public function editTag($id = 0){
        $tag_info = array();
        $tag_obj = new \Yege\Tag();
        $tag_info = $tag_obj->getInfo($id);

        if($tag_info['state'] == 1){

            if(IS_POST){
                $post_info = I("post.");
                $tag_obj = new \Yege\Tag();
                $tag_obj->tag_id = intval($id);
                $tag_obj->tag_name = trim($post_info['tag_name']);
                $edit_result = array();
                $edit_result = $tag_obj->editTag();
                if($edit_result['state'] == 1){
                    $this->success("编辑成功","/Admin/Tags/tagsList");
                }else{
                    $this->error($edit_result['message']);
                }
            }else{
                $this->assign("id",$id);
                $this->assign("info",$tag_info['result']);
                $this->display();
            }

        }else{
            $this->error($tag_info['message']);
        }

    }

}