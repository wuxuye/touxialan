<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 前台AJAX控制器
 *
 * 相关方法
 * ====== 用户相关 ======
 * ajaxUserEditPassword     用户修改密码
 * ====== 商品相关 ======
 *
 *
 */

class AjaxController extends PublicController {

    public $result = [];
    public $post_info = [];

    public function _initialize(){
        parent::_initialize();

        //初始化结果
        $this->result['state'] = 0;
        $this->result['message'] = "未知错误";

        //获取提交参数
        $this->post_info = I("post.");

    }

    /**
     * 用户注册
     */
    public function ajaxUserRegister(){
        if(!wait_action()){
            $this->result['message'] = "操作过于频繁请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //对验证码进行判断
        $verify = trim($this->post_info['verify']);
        if(!empty($verify)){
            $Verify = new \Think\Verify();
            if($Verify->check($verify)){
                //手机注册逻辑
                $user_obj = new \Yege\User();
                $user_obj->user_mobile = trim($this->post_info['mobile']);
                $user_obj->user_password = trim($this->post_info['password']);
                $register_result = array();
                $register_result = $user_obj->userRegisterByMobile();
                if($register_result['state'] == 1){

                    $this->result['state'] = 1;
                    $this->result['message'] = '注册成功';
                    $this->result['user_id'] = $user_obj->user_id;
                    $this->result['reset_code'] = $register_result['reset_code'];

                }else{
                    $this->result['message'] = '注册失败：'.$register_result['message'];
                }
            }else{
                $this->result['message'] = "验证码错误";
            }
        }else{
            $this->result['message'] = "请正确填写验证码";
        }

        $this->ajaxReturn($this->result);

    }

    /**
     * 用户修改密码
     */
    public function ajaxUserEditPassword(){

        if(!wait_action()){
            $this->result['message'] = "操作过于频繁请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //先获取登录信息
        $user_info = get_login_user_info();

        if(!empty($user_info['user_id'])){
            $old_password = trim($this->post_info['old_password']);
            $new_password = trim($this->post_info['new_password']);
            $verify = trim($this->post_info['verify']);
            if(!empty($verify)){
                $Verify = new \Think\Verify();
                if($Verify->check($verify)){
                    //用户修改密码逻辑
                    $user_obj = new \Yege\User();
                    $user_obj->user_id = $user_info['user_id'];
                    $user_obj->user_password = $old_password;
                    $edit_result = array();
                    $edit_result = $user_obj->editUserPassword($new_password);
                    if($edit_result['state'] == 1){
                        $this->result['state'] = 1;
                        $this->result['message'] = "修改成功";
                    }else{
                        $this->result['message'] = "修改失败：".$edit_result['message'];
                    }
                }else{
                    $this->result['message'] = "验证码错误";
                }
            }else{
                $this->result['message'] = "请正确填写验证码";
            }
        }else{
            $this->result['message'] = "修改密码，请先登录";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 用户重置密码
     */
    public function ajaxUserResetPassword(){
        if(!wait_action()){
            $this->result['message'] = "操作过于频繁请稍后再试";
            $this->ajaxReturn($this->result);
        }

        $mobile = trim($this->post_info['mobile']);
        $reset_code = trim($this->post_info['reset_code']);
        $reset_result = [];
        $user_obj = new \Yege\User();
        $user_obj->user_mobile = $mobile;
        $reset_result = $user_obj->resetPasswordByResetCode($reset_code);
        if($reset_result['state'] == 1){
            $this->result['state'] = 1;
            $this->result['message'] = "重置成功";
        }else{
            $this->result['message'] = "操作失败：".$reset_result['message'];
        }

        $this->ajaxReturn($this->result);
    }

}