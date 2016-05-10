<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台脚本控制器
 *
 * 相关方法
 * cleanGoodsImage  清理掉商品图片中没用的图片
 * cleanGoodsTags   商品标签清理
 *
 */

class JiaoBenController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        //不要超时
        set_time_limit(0);

    }

    //脚本列表
    public function index(){

        $this->assign("jiao_ben_list",C("ADMIN_JIAO_BEN_LIST"));

        $this->display();
    }

    //商品图片清理
    public function cleanGoodsImage(){

        $goods_table = C("TABLE_NAME_GOODS");

        //找到所有在用的商品图片(无论商品状态，只要存在于商品表)
        $goods_image = $where = array();
        $where['goods_image'] = array("neq","");
        $goods_image = M($goods_table)->where($where)->field("goods_image")->select();

        $goods_image_list = array();
        foreach($goods_image as $key => $val){
            $goods_image_list[] = '/'.$val['goods_image'];
        }

        $delete_num = 0;

        $file_list = array();
        $file_list = scandir(".".C("ADMIN_GOODS_IMAGE_FILE_URL"));
        foreach($file_list as $key => $val){
            if(date("Y-m-d",strtotime($val)) == $val){
                $image_list = array();
                $image_list = scandir(".".C("ADMIN_GOODS_IMAGE_FILE_URL")."/".$val);
                foreach($image_list as $file){
                    $file_url = ".".C("ADMIN_GOODS_IMAGE_FILE_URL")."/".$val."/".$file;
                    if(is_file($file_url)){
                        if(!in_array(C("ADMIN_GOODS_IMAGE_FILE_URL")."/".$val."/".$file,$goods_image_list)){
                            if(unlink($file_url)){
                                $delete_num ++;
                            }
                        }
                    }
                }

            }
        }

        echo "OK ".$delete_num." 个文件被删除";
    }

    //商品标签清理
    public function cleanGoodsTags(){

        $delete_num = 0;

        $goods_table = C("TABLE_NAME_GOODS");
        $tags_table = C("TABLE_NAME_TAGS");
        $goods_tags_relate_table = C("TABLE_NAME_GOODS_TAG_RELATE");

        //先获取关联表中的所有数据
        $all_relate_tags = M($goods_tags_relate_table)->select();
        foreach($all_relate_tags as $key => $val){
            //逐个去拿到商品 与 标签
            $goods_info = $tags_info = $where = [];
            $where['id'] = $val['goods_id'];
            $goods_info = M($goods_table)->where($where)->find();
            if(empty($goods_info['id'])){
                $where = [];
                $where['goods_id'] = $val['goods_id'];
                if(M($goods_tags_relate_table)->where($where)->delete()){
                    $delete_num ++;
                }
            }

            $where = [];
            $where['id'] = $val['tag_id'];
            $tags_info = M($tags_table)->where($where)->find();
            if(empty($tags_info['id'])){
                $where = [];
                $where['tag_id'] = $val['tag_id'];
                if(M($goods_tags_relate_table)->where($where)->delete()){
                    $delete_num ++;
                }
            }
        }

        echo "OK ".$delete_num." 条标签关联记录被删除";

    }

}