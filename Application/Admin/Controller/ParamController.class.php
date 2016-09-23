<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 后台全站基础参数配置控制器
 *
 * 相关方法
 * operationList        操作项列表
 * showParam            操作详情页
 */

class ParamController extends PublicController {

    public function _initialize(){
        parent::_initialize();

        $this->param_model = D("Param");
    }

    /**
     * 操作项列表
     */
    public function operationList(){
        $Param = new \Yege\Param();

        //操作列表
        $operation_list = $Param->param_list;

        $this->assign("operation_list",$operation_list);
        $this->display();
    }

    /**
     * 操作详情页
     * @param string $param 操作参数
     */
    public function showParam($param = ""){
        if(!empty($param)){
            $Param = new \Yege\Param();

            if(!empty(IS_POST)){
                $post_info = I("post.");
                $result = $Param->saveDataByParam($param,$post_info);
                if($result['state'] == 1){
                    $this->success("操作成功","/Admin/Param/operationList");
                }else{
                    $this->error($result['message']);
                }
            }else{
                $data = $Param->getDataByParam($param);
                if($data['state'] == 1){
                    $this->assign("data",$data);
                    $this->display($data['display']);
                }else{
                    $this->error($data['message']);
                }
            }
        }else{
            $this->error("参数缺失");
        }
    }


    /**
     * 总控台
     */
    public function allOperation(){

        $data = D("Param")->getAllOperation();

        $this->assign("data",$data);
        $this->display();

    }

}