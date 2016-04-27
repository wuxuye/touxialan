<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台脚本控制器
 *
 * 相关方法
 * cleanGoodsImage   清理掉商品图片中没用的图片
 *
 */

class JiaoBenController extends PublicController {

    public function _initialize(){
        parent::_initialize();
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

}