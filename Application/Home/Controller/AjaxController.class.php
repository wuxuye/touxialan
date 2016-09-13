<?php

namespace Home\Controller;
use Think\Controller;
use Yege\Feedback;

/**
 * 前台AJAX控制器
 *
 * 相关方法
 * ====== 用户相关 ======
 * ajaxUserRegister         用户注册
 * ajaxUserLogin            用户登录
 * ajaxUserEditPassword     用户修改密码
 * ajaxUserResetPassword    用户重置密码
 * ajaxUserFeedback         用户问题反馈
 * ====== 商品相关 ======
 * ajaxAddGoodsToUserCart   添加商品至用户清单中
 * ====== 用户中心相关 ======
 * ajaxUserCenterSaveReceiptAddress         操作收货地址
 * ajaxUserCenterSetDefaultReceiptAddress   设置默认收货地址
 * ajaxUserCenterDeleteReceiptAddress       删除指定收货地址
 * ====== 用户清单相关 ======
 * ajaxDeleteCartGoods      删除清单商品
 * ajaxDeleteAllCartGoods   清空清单商品
 * ajaxCreateOrder          清单数据生成订单
 * ====== 用户订单相关 ======
 * ajaxUserDeleteOrder      用户删除订单
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

        //ajax请求判断
        if(!IS_AJAX){
            $this->result['message'] = "非法操作";
            $this->ajaxReturn($this->result);
        }

        //获取提交参数
        $this->post_info = I("post.");

    }

    /**
     * 用户注册
     */
    public function ajaxUserRegister(){
        if(!wait_action()){
            $this->result['message'] = "操作过于频繁，请稍后再试";
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
            $this->result['message'] = "操作过于频繁，请稍后再试";
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
                        $this->result['message'] = $edit_result['message'];
                    }
                }else{
                    $this->result['message'] = "您填写的验证码有误";
                }
            }else{
                $this->result['message'] = "请正确填写验证码";
            }
        }else{
            $this->result['message'] = "登录后，才可以使用此功能";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 用户重置密码
     */
    public function ajaxUserResetPassword(){
        if(!wait_action()){
            $this->result['message'] = "操作过于频繁，请稍后再试";
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
     * 用户问题反馈
     */
    public function ajaxUserFeedback(){
        if(!wait_action(60)){
            $this->result['message'] = "操作过于频繁，请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //先获取登录信息
        $user_info = $this->now_user_info;

        if(!empty($user_info['id'])){
            $feedback_type = check_int($this->post_info['feedback_type']);
            $feedback_order_id = check_int($this->post_info['feedback_order_id']);
            $feedback_content = check_str($this->post_info['feedback_content']);

            //首先尝试拿到订单信息
            if($feedback_type == C("FEEDBACK_TYPE_ORDER_DISSENT")){
                $order_obj = new \Yege\Order();
                $order_obj->user_id = $user_info['id'];
                $order_obj->order_id = $feedback_order_id;
                $order_info = $order_obj->getUserOrderInfo();
                if(empty($order_info['order_info']['id'])){
                    $this->result['message'] = "未能获取订单信息";
                    $this->ajaxReturn($this->result);
                }else{
                    $feedback_content = "订单：".$order_info['order_info']['order_code']."  ".$feedback_content;
                }
            }

            $Feedback = new \Yege\Feedback();
            $Feedback->user_id = $user_info['id'];
            $Feedback->type = $feedback_type;
            $Feedback->message = $feedback_content;
            $result = $Feedback->addFeedback();
            if($result['state'] == 1){
                $this->result['state'] = 1;
                $this->result['message'] = "操作成功";
            }else{
                $this->result['message'] = $result['message'];
            }
        }else{
            $this->result['message'] = "操作失败，请先登录";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 添加商品至用户清单中
     */
    public function ajaxAddGoodsToUserCart(){
        if(!wait_action(2)){
            $this->result['message'] = "操作过于频繁，请稍后再试";
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
            $this->result['message'] = "操作过于频繁，请稍后再试";
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
                    $this->result['message'] = $address_result['message'];
                }
            }else{
                $this->result['message'] = "请填写正确的地址信息，长度保持在50字以内";
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
            $this->result['message'] = "操作过于频繁，请稍后再试";
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
        if(!wait_action(3)){
            $this->result['message'] = "操作过于频繁，请稍后再试";
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

        if(!wait_action(3)){
            $this->result['message'] = "操作过于频繁，请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //获取登录信息
        $user_info = $this->now_user_info;
        if(!empty($user_info['id'])){

            $cart_id = check_int($this->post_info['cart_id']);

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
            $this->result['message'] = "操作过于频繁，请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //获取登录信息
        $user_info = $this->now_user_info;
        if(!empty($user_info['id'])){

            D("Cart")->deleteAllCart($user_info['id']);
            $this->result['state'] = 1;
            $this->result['message'] = "删除成功";

        }else{
            $this->result['message'] = "登录后才能使用此功能";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 清单数据生成订单
     */
    public function ajaxCreateOrder(){
        $this->result['tip_title'] = "";
        if(!wait_action()){
            $this->result['message'] = "操作过于频繁，请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //获取登录信息
        $user_info = $this->now_user_info;
        if(!empty($user_info['id'])){

            $code = check_str($this->post_info['code']);
            if(!empty($code)){

                $cart_send_week = check_int($this->post_info['cart_send_week']);
                $cart_send_time = check_str($this->post_info['cart_send_time']);
                $cart_send_address = check_str($this->post_info['cart_send_address']);
                $cart_remark = check_str($this->post_info['cart_remark']);

                $Order = new \Yege\Order();
                $Order->user_id = $user_info['id'];
                $Order->send_week = $cart_send_week;
                $Order->send_time = $cart_send_time;
                $Order->send_address = $cart_send_address;
                $Order->remark = $cart_remark;

                //对商品串码进行解析
                $Order->goods_code = $code;
                $Order->analysisGoodsCode();
                //订单生成
                $order_result = [];
                $order_result = $Order->createOrder();

                if($order_result['state'] == 1){
                    $this->result['state'] = 1;
                    $this->result['order_id'] = $order_result['order_id'];
                    $this->result['message'] = "订单生成成功";

                    //刷新用户登录时间
                    update_session_time();

                }else{
                    $this->result['tip_title'] = $order_result['tip_title'];
                    $this->result['message'] = $order_result['message'];
                }
            }else{
                $this->result['message'] = "请先选择需要购买的商品";
            }
        }else{
            $this->result['message'] = "登录后才能使用此功能";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 用户删除订单
     */
    public function ajaxUserDeleteOrder(){
        if(!wait_action(10)){
            $this->result['message'] = "操作过于频繁，请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //获取登录信息
        $user_info = $this->now_user_info;
        if(!empty($user_info['id'])){
            $order_id = check_int($this->post_info['order_id']);
            $order_obj = new \Yege\Order();
            $order_obj->order_id = $order_id;
            $order_obj->user_id = $user_info['id'];
            $order_info = $order_obj->getUserOrderInfo();
            if(!empty($order_info['order_info']['id'])){
                $order_obj->deleteInvalidOrder($order_info['order_info']['order_code']);
                $this->result['state'] = 1;
                $this->result['message'] = "删除成功";
            }else{
                $this->result['message'] = "未能获取订单信息";
            }
        }else{
            $this->result['message'] = "登录后才能使用此功能";
        }

        $this->ajaxReturn($this->result);
    }

    /**
     * 修改用户昵称
     */
    public function ajaxUpdateUserNickname(){
        if(!wait_action()){
            $this->result['message'] = "操作过于频繁，请稍后再试";
            $this->ajaxReturn($this->result);
        }

        //获取登录信息
        $user_info = $this->now_user_info;
        if(!empty($user_info['id'])){
            $nickname = check_str($this->post_info['nickname']);
            if(strlen($nickname) >= 2 && strlen($nickname) <= 10){
                $user_obj = new \Yege\User();
                $user_obj->user_id = $user_info['id'];
                $user_obj->user_mobile = $user_info['mobile'];
                $user_obj->nick_name = $nickname;
                $temp_result = $user_obj->userEditData();
                if($temp_result['state'] == 1){
                    $this->result['state'] = 1;
                    $this->result['message'] = "修改成功";
                }else{
                    $this->result['message'] = "修改失败：".$temp_result['message'];
                }
            }else{
                $this->result['message'] = "请正确填写昵称，在2~10个字以内。";
            }
        }else{
            $this->result['message'] = "登录后才能使用此功能";
        }

        $this->ajaxReturn($this->result);
    }

}