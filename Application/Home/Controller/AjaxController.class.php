<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 前台AJAX控制器
 *
 * 相关方法
 * ====== 用户相关 ======
 * ajaxUserRegister         用户注册
 * ajaxUserLogin            用户登录
 * ajaxUserEditPassword     用户修改密码
 * ajaxUserResetPassword    用户重置密码
 * ====== 商品相关 ======
 * ajaxAddGoodsToUserCart   添加商品至用户清单中
 * ====== 用户中心相关 ======
 * ajaxUserCenterSaveReceiptAddress         操作收货地址
 * ajaxUserCenterSetDefaultReceiptAddress   设置默认收货地址
 * ajaxUserCenterDeleteReceiptAddress       删除指定收货地址
 * ====== 用户清单相关 ======
 * ajaxDeleteCartGoods      删除清单商品
 * ajaxDeleteAllCartGoods   清空清单商品
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
        $verify = check_str($this->post_info['verify']);
        if(!empty($verify)){
            $Verify = new \Think\Verify();
            if($Verify->check($verify)){
                //手机注册逻辑
                $user_obj = new \Yege\User();
                $user_obj->user_mobile = check_str($this->post_info['mobile']);
                $user_obj->user_password = check_str($this->post_info['password']);
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
     * 用户登录
     */
    public function ajaxUserLogin(){

        //对验证码进行判断
        $verify = check_str($this->post_info['verify']);
        if(!empty($verify)){
            $Verify = new \Think\Verify();
            if($Verify->check($verify)){
                //手机登录逻辑
                $user_obj = new \Yege\User();
                $user_obj->user_mobile = check_str($this->post_info['mobile']);
                $user_obj->user_password = check_str($this->post_info['password']);
                $login_result = array();
                $login_result = $user_obj->userLoginByMobile();
                if($login_result['state'] == 1){
                    $this->result['state'] = 1;
                    $this->result['message'] = "登陆成功";
                    $this->result['back_url'] = get_session(C("HOME_LOGIN_BACK_URL_SESSION_STR")); //返回回跳地址
                    //无论如何 清除回跳地址
                    unset($_SESSION[C("HOME_LOGIN_BACK_URL_SESSION_STR")]);
                }else{
                    $this->result['message'] = "登陆失败：".$login_result['message'];
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
        $user_info = $this->now_user_info;

        if(!empty($user_info['id'])){
            $old_password = check_str($this->post_info['old_password']);
            $new_password = check_str($this->post_info['new_password']);
            $verify = check_str($this->post_info['verify']);
            if(!empty($verify)){
                $Verify = new \Think\Verify();
                if($Verify->check($verify)){
                    //用户修改密码逻辑
                    $user_obj = new \Yege\User();
                    $user_obj->user_id = $user_info['id'];
                    $user_obj->user_password = $old_password;
                    $edit_result = array();
                    $edit_result = $user_obj->editUserPassword($new_password);
                    if($edit_result['state'] == 1){
                        $this->result['state'] = 1;
                        $this->result['message'] = "修改成功";
                        //清除当前session
                        unset($_SESSION[C("HOME_USER_ID_SESSION_STR")]);
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

        $mobile = check_str($this->post_info['mobile']);
        $reset_code = check_str($this->post_info['reset_code']);
        $reset_result = [];
        $user_obj = new \Yege\User();
        $user_obj->user_mobile = $mobile;
        $reset_result = $user_obj->resetPasswordByResetCode($reset_code);
        if($reset_result['state'] == 1){
            $this->result['state'] = 1;
            $this->result['message'] = "重置成功";
            //清除当前session
            unset($_SESSION[C("HOME_USER_ID_SESSION_STR")]);
        }else{
            $this->result['message'] = "操作失败：".$reset_result['message'];
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 添加商品至用户清单中
     */
    public function ajaxAddGoodsToUserCart(){
        if(!wait_action(1)){
            $this->result['message'] = "操作过于频繁请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //先获取登录信息
        $user_info = $this->now_user_info;

        if(!empty($user_info['id'])){
            $goods_id = check_int($this->post_info['goods_id']);

            $Cart = new \Yege\Cart();
            $Cart->user_id = $user_info['id'];
            $Cart->goods_id = $goods_id;
            $temp_result = [];
            $temp_result = $Cart->addGoodsToUserCart();

            if($temp_result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = '操作成功';
            }else{
                $this->result['message'] = "添加商品失败：".$temp_result['message'];
            }
        }else{
            $this->result['message'] = "添加商品失败，请先登录";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 操作收货地址
     */
    public function ajaxUserCenterSaveReceiptAddress(){
        if(!wait_action()){
            $this->result['message'] = "操作过于频繁请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //先获取登录信息
        $user_info = $this->now_user_info;

        if(!empty($user_info['id'])){
            $address_id = check_int($this->post_info['address_id']);
            $address_name = check_str($this->post_info['address_name']);
            if(!empty($address_name) && mb_strlen($address_name,'utf-8') <= 50){
                //数据操作
                $user_obj = new \Yege\User();
                $user_obj->user_id = $user_info['id'];
                $address_result = [];
                $address_data = ["address_id"=>$address_id,"address_name"=>$address_name];
                $address_result = $user_obj->saveUserReceiptAddress($address_data);
                if($address_result['state'] == 1){
                    $this->result['state'] = 1;
                    $this->result['message'] = "操作成功";
                }else{
                    $this->result['message'] = "操作收货地址失败：".$address_result['message'];
                }
            }else{
                $this->result['message'] = "请填写正确的地址信息";
            }
        }else{
            $this->result['message'] = "操作收货地址，请先登录";
        }


        $this->ajaxReturn($this->result);
    }

    /**
     * 设置默认收货地址
     */
    public function ajaxUserCenterSetDefaultReceiptAddress(){
        if(!wait_action()){
            $this->result['message'] = "操作过于频繁请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //先获取登录信息
        $user_info = $this->now_user_info;

        if(!empty($user_info['id'])){
            $address_id = check_int($this->post_info['id']);
            //数据操作
            $user_obj = new \Yege\User();
            $user_obj->user_id = $user_info['id'];
            $address_result = [];
            $address_result = $user_obj->setDefaultUserReceiptAddress($address_id);
            if($address_result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = "设置成功";
            }else{
                $this->result['message'] = "设置失败：".$address_result['message'];
            }
        }else{
            $this->result['message'] = "设置失败，请先登录";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 删除指定收货地址
     */
    public function ajaxUserCenterDeleteReceiptAddress(){
        if(!wait_action()){
            $this->result['message'] = "操作过于频繁请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //先获取登录信息
        $user_info = $this->now_user_info;

        if(!empty($user_info['id'])){
            $address_id = check_int($this->post_info['id']);
            //数据操作
            $user_obj = new \Yege\User();
            $user_obj->user_id = $user_info['id'];
            $address_result = [];
            $address_result = $user_obj->deleteUserReceiptAddress($address_id);
            if($address_result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = "删除成功";
            }else{
                $this->result['message'] = "删除失败：".$address_result['message'];
            }
        }else{
            $this->result['message'] = "删除失败，请先登录";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 删除清单商品
     */
    public function ajaxDeleteCartGoods(){

        if(!wait_action()){
            $this->result['message'] = "操作过于频繁请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //获取登录信息
        $user_info = $this->now_user_info;
        if(!empty($user_info['id'])){

            $cart_id = intval($this->post_info['cart_id']);

            if(!empty($cart_id)){
                //详情获取
                $info = D("Cart")->getCartInfo($cart_id);
                if(!empty($info['id'])){
                    if($info['user_id'] == $user_info['id']){
                        D("Cart")->deleteCart($cart_id);
                        $this->result['state'] = 1;
                        $this->result['message'] = "删除成功";
                    }else{
                        $this->result['message'] = "数据不匹配";
                    }
                }else{
                    $this->result['message'] = "未能获取详情";
                }
            }else{
                $this->result['message'] = "参数缺失";
            }

        }else{
            $this->result['message'] = "请先登录才能使用此功能";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 清空清单商品
     */
    public function ajaxDeleteAllCartGoods(){

        if(!wait_action()){
            $this->result['message'] = "操作过于频繁请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //获取登录信息
        $user_info = $this->now_user_info;
        if(!empty($user_info['id'])){

            D("Cart")->deleteAllCart($user_info['id']);
            $this->result['state'] = 1;
            $this->result['message'] = "删除成功";

        }else{
            $this->result['message'] = "请先登录才能使用此功能";
        }

        $this->ajaxReturn($this->result);
    }

}