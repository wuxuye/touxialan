<?php
namespace Yege;

/**
 * 用户类
 *
 * 方法提供
 * getUserInfo                  用户信息获取 (此方法会为 user_info 赋值)
 * userRegisterByMobile         用户手机注册
 * userRegisterByMobileDo(私有) 用户手机注册执行逻辑 (此方法会改变 user_id 的值)
 * userLoginByMobile            用户手机登陆
 * userLoginByMobileDo(私有)    用户手机登录执行逻辑 (此方法会改变 user_id 的值)
 * editUserPassword             用户修改密码（进这个方法的时候$this->user_password是旧密码，之后无论成功与否都会被替换成新密码）
 * resetPasswordByResetCode     用户用重置用安全码重置密码
 * resetPasswordByMobile        根据用户手机号重置密码
 * updateUserActiveTime         更新用户最后活跃时间
 * userEditData                 修改用户信息
 * getUserReceiptAddress        获取用户收货地址列表
 * getUserReceiptAddressInfo    获取指定收货地址信息
 * saveUserReceiptAddress       添加/修改 用户收货地址
 * deleteUserReceiptAddress     删除用户收货地址
 * checkParam                   参数检验 (此方法可能会改变 user_id、user_name、user_password、nick_name与user_mobile的值)
 */

class User{

    //提供于外面赋值或读取的相关参数
    public $user_id = 0; //用户id
    public $user_name = ""; //用户名
    public $user_password = ""; //密码
    public $nick_name = ""; //用户昵称
    public $user_mobile = ""; //用户手机

    private $user_info = []; //用户表详细信息
    private $user_table = ""; //相关用户表
    private $user_receipt_address_table = ""; //用户收货地址信息表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->user_table = C("TABLE_NAME_USER");
        $this->user_receipt_address_table = C("TABLE_NAME_USER_RECEIPT_ADDRESS");
    }

    /**
     * 用户信息获取
     * @return array $result 结果返回
     *
     * 此方法会为 user_info 赋值
     *
     */
    public function getUserInfo($type = "user_id"){
        $result = ['state'=>0,'message'=>"未知错误"];

        $user_info = $where = array();
        $wrong = 0; //错误标记
        //根据类型选择查询的where
        switch($type){
            case 'user_id': //用用户id搜索
                $user_id = intval($this->user_id);
                if(!empty($user_id) && $user_id > 0){
                    $where['id'] = $user_id;
                }else{
                    $wrong = 1;
                }
                break;
            case 'user_name': //用username搜索
                $username = trim($this->user_name);
                if(!empty($username)){
                    $where['username'] = $username;
                }else{
                    $wrong = 1;
                }
                break;
            case 'nick_name': //用用户昵称搜索
                $nickname = trim($this->nick_name);
                if(!empty($nickname)){
                    $where['nick_name'] = $nickname;
                }else{
                    $wrong = 1;
                }
                break;
            case 'mobile': //用用户手机搜索
                $mobile = trim($this->user_mobile);
                if(!empty($mobile) && is_numeric($mobile)){
                    $where['mobile'] = $mobile;
                }else{
                    $wrong = 1;
                }
                break;
            default :
                $result['message'] = "未定义的查询类型";
                return $result;
        }
        if($wrong == 0){
            if(!empty($where)){
                $user_info = M($this->user_table)->where($where)->find();
                if(!empty($user_info['id'])){
                    //重组数据返回
                    $return_info = array();
                    $return_info['user_id'] = $user_info['id'];
                    $return_info['user_name'] = $user_info['username'];
                    $return_info['nick_name'] = $user_info['nick_name'];
                    $return_info['user_mobile'] = $user_info['mobile'];
                    $return_info['state'] = $user_info['state'];
                    $return_info['user_identity'] = $user_info['identity'];
                    $return_info['reset_code'] = $user_info['reset_code'];
                    $return_info['active_time'] = $user_info['active_time'];
                    $return_info['inputtime'] = $user_info['inputtime'];
                    $result['state'] = 1;
                    $result['result'] = $return_info;
                    $result['message'] = "获取成功";

                    $this->user_info = $user_info;
                }else{
                    $result['message'] = "未能获得用户信息";
                }
            }else{
                $result['message'] = "未能正确获取搜索条件";
            }
        }else{
            $result['message'] = "参数错误";
        }

        return $result;
    }

    /**
     * 用户手机注册
     * @return array $result 结果返回
     */
    public function userRegisterByMobile(){
        $result = ['state'=>0,'message'=>"未知错误"];

        //各参数检验
        $check_result = array();
        $check_result = $this->checkParam("user_mobile"); //手机检查
        if($check_result['state'] == 1){
            $check_result = array();
            $check_result = $this->checkParam("user_password"); //密码检查
            if($check_result['state'] == 1){
                //注册逻辑
                $register_result = array();
                $register_result = $this->userRegisterByMobileDo();
                if($register_result['state'] == 1){
                    $result['state'] = 1;
                    $result['message'] = "注册成功";
                    //将重置用安全码返回
                    $result['reset_code'] = $register_result['reset_code'];
                }else{
                    $result['message'] = $register_result['message'];
                }
            }else{
                $result['message'] = $check_result['message'];
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 用户手机注册执行逻辑
     * @return array $result 结果返回
     *
     * 此方法会改变 user_id 的值
     *
     */
    private function userRegisterByMobileDo(){
        $result = ['state'=>0,'message'=>"未知错误"];

        //重复性检查
        $info = $where = array();
        $where['mobile'] = $this->user_mobile;
        $info = M($this->user_table)->where($where)->find();
        if(empty($info)){
            //生成一个安全码
            $safe_code = substr(md5(time()),0,10);
            //生成重置码
            $reset_code = substr(md5($safe_code),-10,8);
            //基础参数组装
            $add = array();
            $add['password'] = md5($this->user_password.$safe_code);
            $add['safe_code'] = $safe_code;
            $add['reset_code'] = $reset_code;
            $add['mobile'] = $this->user_mobile;
            $add['active_time'] = $add['inputtime'] = $add['updatetime'] = time();
            $this->user_id = M($this->user_table)->add($add);
            if(!empty($this->user_id)){
                $result['state'] = 1;
                $result['message'] = "注册成功";
                $result['reset_code'] = $reset_code;

                //将用户id记录到session中
                session(C("HOME_USER_ID_SESSION_STR"),$this->user_id);

                //活动调用
                $activity_obj = new \Yege\Activity();
                $activity_obj->user_id = $this->user_id;
                $activity_obj->activityNewUserRegister();

                //发送一波消息
                add_user_message($this->user_id,"注册成功，之后的服务会默认提供给 手机号：".hidden_mobile($this->user_mobile)." 可以在会员中心更改绑定手机",1);

            }else{
                $result['message'] = "添加数据失败";
            }
        }else{
            $result['message'] = "手机号已存在";
        }

        return $result;
    }

    /**
     * 用户手机登陆
     * @return array $result 结果返回
     */
    public function userLoginByMobile(){
        $result = ['state'=>0,'message'=>"未知错误"];

        //各参数检验
        $check_result = array();
        $check_result = $this->checkParam("user_mobile"); //手机检查
        if($check_result['state'] == 1){
            //密码基础检验
            if(!empty($this->user_mobile) && (strlen($this->user_mobile) >= 8 && strlen($this->user_mobile) <= 16)){
                //登陆逻辑
                $login_result = array();
                $login_result = $this->userLoginByMobileDo();
                if($login_result['state'] == 1){
                    $result['state'] = 1;
                    $result['message'] = "登录成功";
                }else{
                    $result['message'] = $login_result['message'];
                }
            }else{
                $result['message'] = "请正确填写密码";
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 用户手机登录执行逻辑
     * @return array $result 结果返回
     */
    private function userLoginByMobileDo(){
        $result = ['state'=>0,'message'=>"未知错误"];

        //数据获取
        $info = array();
        $info = $this->getUserInfo("mobile");
        //用户状态不是 删除 才可以登录
        if($info['state'] == 1 && !empty($info['result']['user_id']) && $info['result']['state'] != C("STATE_USER_DELETE")){
            //密码核对
            $password = md5($this->user_password.$this->user_info['safe_code']);
            if($password == $this->user_info['password']){
                //将用户id记录到session中
                session(C("HOME_USER_ID_SESSION_STR"),$this->user_info['id']);
                //为user_id赋值
                $this->user_id = $this->user_info['id'];

                //更新最后活跃时间
                $this->updateUserActiveTime();

                $result['state'] = 1;
                $result['message'] = "登录成功";
            }else{
                $result['message'] = "密码错误";
            }
        }else{
            $result['message'] = "用户不存在";
        }

        return $result;
    }

    /**
     * 用户修改密码
     * @param $string $new_password 新密码
     * @return $result $result 结果返回
     */
    public function editUserPassword($new_password = ""){
        $result = ['state'=>0,'message'=>"未知错误"];

        //用户信息检测
        $info = array();
        $info = $this->getUserInfo();
        if(!empty($info['result'])){
            //旧密码检验
            $old_password = md5($this->user_password.$this->user_info['safe_code']);
            if($old_password == $this->user_info['password']){
                //新密码检查
                $this->user_password = $new_password;
                $check_result = array();
                $check_result = $this->checkParam("user_password");
                if($check_result['state'] == 1){
                    //将新密码更新至表中
                    $where = $save = array();
                    $where['id'] = $this->user_info['id'];
                    $save['password'] = md5($this->user_password.$this->user_info['safe_code']);
                    $save['updatetime'] = time();
                    if(M($this->user_table)->where($where)->save($save)){
                        $result['state'] = 1;
                        $result['message'] = "修改成功";

                        //更新最后活跃时间
                        $this->updateUserActiveTime();

                    }else{
                        $result['message'] = "修改失败";
                    }
                }else{
                    $result['message'] = "新密码错误：".$check_result['message'];
                }
            }else{
                $result['message'] = "请先正确填写旧密码";
            }
        }else{
            $result['message'] = "用户信息未获取";
        }

        return $result;
    }

    /**
     * 用户用重置用安全码重置密码
     * @param string $reset_code 重置用安全码
     * @return array $result 结果返回
     */
    public function resetPasswordByResetCode($reset_code = ""){
        $result = ['state'=>0,'message'=>'未知错误'];

        //手机检查
        $check_result = array();
        $check_result = $this->checkParam("user_mobile");
        if($check_result['state'] == 1){
            //重置用安全码检测
            $reset_code = trim($reset_code);
            $info = $where = [];
            $where['mobile'] = $this->user_mobile;
            $where['reset_code'] = $reset_code;
            $info = M($this->user_table)->where($where)->find();
            if(!empty($info['id'])){
                $reset_result = [];
                $reset_result = $this->resetPasswordByMobile();
                if($reset_result['state'] == 1){
                    $result['state'] = 1;
                    $result['message'] = "操作成功";
                }else{
                    $result['message'] = "重置失败，".$reset_result['message'];
                }
            }else{
                $result['message'] = "手机或重置安全码错误";
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 根据用户手机号重置密码
     * @return array $result 结果返回
     */
    public function resetPasswordByMobile(){

        /**
         * 此方法暂时是直接将密码重置为手机号后8位
         * so 暂不提供给用户自己调用
         */

        $result = ['state'=>0,'message'=>"未知错误"];

        //手机检查
        $check_result = array();
        $check_result = $this->checkParam("user_mobile");
        if($check_result['state'] == 1){
            $info = $this->getUserInfo("mobile");
            if(!empty($info['result'])){
                //根据手机号生成新密码
                $new_password = "";
                $mobile = $this->user_mobile;
                if(strlen($mobile)<8){ //小于8位
                    $new_password = "12345678";
                }else{
                    $new_password = substr($mobile,-8,8);
                }

                //将新密码更新至数据库
                $where = $save = array();
                $where['id'] = $this->user_info['id'];
                $save['password'] = md5($new_password.$this->user_info['safe_code']);
                $save['updatetime'] = time();
                if(M($this->user_table)->where($where)->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "重置成功";
                }else{
                    $result['message'] = "重置失败";
                }
            }else{
                $result['message'] = "用户信息未获取";
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 更新用户最后活跃时间
     */
    public function updateUserActiveTime(){
        $user_id = intval($this->user_id);
        if(!empty($user_id)){
            $save = $where = array();
            $where['id'] = $user_id;
            $save['active_time'] = time();
            if(!M($this->user_table)->where($where)->save($save)){
                add_wrong_log("更新用户最后活跃时间逻辑失败，传递来的参数 user_id : ".$user_id);
            }
        }
    }

    /**
     * 修改用户信息
     */
    public function userEditData(){
        $result = ['state'=>0,'message'=>'未知错误'];
        //基础参数检测
        $check_result = [];
        $check_result = $this->checkParam("user_id");
        if($check_result['state'] == 1){
            $check_result = [];
            $check_result = $this->checkParam("user_mobile");
            if($check_result['state'] == 1){
                $save = $where = [];
                $where['id'] = $this->user_id;
                $save['mobile'] = $this->user_mobile;
                if(!empty($this->nick_name)){
                    $check_result = [];
                    $check_result = $this->checkParam("nick_name");
                    if($check_result['state'] == 1){
                        $save['nick_name'] = $this->nick_name;
                    }else{
                        $result['message'] = $check_result['message'];
                        return $result;
                    }
                }
                $save['updatetime'] = time();
                if(M($this->user_table)->where($where)->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "编辑成功";
                }else{
                    $result['message'] = "编辑失败";
                }
            }else{
                $result['message'] = $check_result['message'];
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 获取用户收货地址列表
     */
    public function getUserReceiptAddress(){
        $result = ['state'=>0,'message'=>'未知错误'];
        //基础参数检测
        $check_result = [];
        $check_result = $this->checkParam("user_id");
        if($check_result['state'] == 1){
            $list = $where = [];
            $where['user_id'] = $this->user_id;
            $list = M($this->user_receipt_address_table)->where($where)->select();

            $result['state'] = 1;
            $result['list'] = $list;
            $result['message'] = "获取成功";

        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 获取指定收货地址信息
     * @param int $address_id 收货地址id
     */
    public function getUserReceiptAddressInfo($address_id = 0){
        $result = ['state'=>0,'message'=>'未知错误'];
        //参数检测
        $address_id = intval($address_id);
        if(!empty($address_id)){
            $info = $where = [];
            $where['id'] = $address_id;
            $info = M($this->user_receipt_address_table)->where($where)->find();

            if(!empty($info['id'])){
                $result['state'] = 1;
                $result['info'] = $info;
                $result['message'] = "获取成功";
            }else{
                $result['message'] = "未能获取收货地址信息";
            }
        }else{
            $result['message'] = "参数错误";
        }

        return $result;
    }

    /**
     * 添加/修改 用户收货地址
     * @param array $address_data 操作相关数据
     */
    public function saveUserReceiptAddress($address_data = []){
        $result = ['state'=>0,'message'=>'未知错误'];
        //基础参数检测
        $check_result = [];
        $check_result = $this->checkParam("user_id");
        if($check_result['state'] == 1){

            //操作用参数获取
            $address_id = intval($address_data['address_id']);
            $address_name = check_str($address_data['address_name']);
            if(!empty($address_name)){

                $is_add = 1;//操作方式  不是添加就是更新

                if(!empty($address_id)){
                    $info_result = [];
                    $info_result = $this->getUserReceiptAddressInfo($address_id);
                    if($info_result['state'] == 1 && !empty($info_result['info']['id'])){
                        $is_add = 0; //更新操作
                    }
                }

                $save = [];
                $save['address'] = $address_name;
                if($is_add == 1){ //新增数据操作

                    //此时检查最大数量
                    $address_list = [];
                    $address_list = $this->getUserReceiptAddress();
                    if(count($address_list['list']) < C("HOME_USER_MAX_RECEIPT_ADDRESS_NUM")){
                        $save['user_id'] = $this->user_id;
                        $save['inputtime'] = $save['updatetime'] = time();

                        //这是第一条 就将它设置成默认
                        if(empty(count($address_list['list']))){
                            $save['is_default'] = 1;
                        }

                        $add_result = M($this->user_receipt_address_table)->add($save);
                        if($add_result){
                            $result['state'] = 1;
                            $result['message'] = "添加成功";
                        }else{
                            $result['message'] = "数据添加失败";
                        }
                    }else{
                        $result['message'] = "一个用户最多只能有 ".C("HOME_USER_MAX_RECEIPT_ADDRESS_NUM")." 个备选收货地址";
                    }
                }else{ //更新数据
                    $where = [];
                    $where['id'] = $address_id;
                    $where['user_id'] = $this->user_id;
                    $save['updatetime'] = time();
                    if(M($this->user_receipt_address_table)->where($where)->save($save)){
                        $result['state'] = 1;
                        $result['message'] = "编辑成功";
                    }else{
                        $result['message'] = "编辑数据失败";
                    }
                }
            }else{
                $result['message'] = "错误的地址信息";
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 删除用户收货地址
     * @param int $address_id 地址id
     */
    public function deleteUserReceiptAddress($address_id = 0){
        $result = ['state'=>0,'message'=>'未知错误'];

        //基础参数检测
        $check_result = [];
        $check_result = $this->checkParam("user_id");
        if($check_result['state'] == 1){

            //操作用参数获取
            $address_id = intval($address_id);
            $where = [];
            $where['id'] = $address_id;
            $where['user_id'] = $this->user_id;
            if(M($this->user_receipt_address_table)->where($where)->delete()){
                $result['state'] = 1;
                $result['message'] = "删除成功";
            }else{
                $result['message'] = "删除失败";
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 设置默认收货地址
     * @param int $address_id 地址id
     */
    public function setDefaultUserReceiptAddress($address_id = 0){
        $result = ['state'=>0,'message'=>'未知错误'];

        //基础参数检测
        $check_result = [];
        $check_result = $this->checkParam("user_id");
        if($check_result['state'] == 1){

            //操作用参数获取
            $address_id = intval($address_id);
            M()->startTrans();
            //所有的相关地址变为 非默认
            $where = $save = [];
            $save['is_default'] = 0;
            $save['updatetime'] = time();
            $where['user_id'] = $this->user_id;
            if(M($this->user_receipt_address_table)->where($where)->save($save)){
                $where = $save = [];
                $save['is_default'] = 1;
                $save['updatetime'] = time();
                $where['id'] = $address_id;
                $where['user_id'] = $this->user_id;
                if(M($this->user_receipt_address_table)->where($where)->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "设置成功";
                    M()->commit();
                }else{
                    $result['message'] = "设置失败";
                    M()->rollback();
                }
            }else{
                $result['message'] = "初始化设置失败";
                M()->rollback();
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 参数检验（顺带过滤）
     * @param string $str 参数标示
     * @return array $result 结果返回
     *
     * 此方法可能会改变 user_id、user_name、user_password、nick_name与user_mobile的值
     *
     */
    public function checkParam($str = ""){
        $result = ['state'=>0,'message'=>"未知错误"];

        switch($str){
            case 'user_id': //用户id检查
                $param = intval($this->user_id);
                if($param <= 0){
                    $result['message'] = "用户id错误";
                }else{
                    $this->user_id = $param;
                    $result['state'] = 1;
                }
                break;
            case 'user_name': //用户名检查
                $param = trim($this->user_name);
                $chars = "^[A-Za-z0-9]{5-16}$";
                if (preg_match($chars, $param)){
                    $this->user_name = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "用户名错误，只能由 5~16位 26位英文字母与0~9组成";
                }
                break;
            case 'user_password': //用户密码检查
                $param = trim($this->user_password);
                $chars = "/^[A-Za-z0-9]{8,16}$/";
                if (preg_match($chars, $param)){
                    $this->user_password = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "密码错误，只能由 8~16位 26位英文字母与0~9组成";
                }
                break;
            case 'nick_name': //用户昵称检查
                $param = check_str($this->nick_name);
                if(strlen($param)<3 || strlen($param)>10){
                    $result['message'] = "昵称有误，长度在3~10之间";
                }else{
                    $this->nick_name = $param;
                    $result['state'] = 1;
                }
                break;
            case 'user_mobile': //用户手机检查
                $param = trim($this->user_mobile);
                if(is_mobile($param)){
                    $this->user_mobile = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "手机格式错误";
                }
                break;
            default : //未定义的检查类型
                $result['message'] = "未定义的检查类型";
                break;

        }

        return $result;
    }


}
