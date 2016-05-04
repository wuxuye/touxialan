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
 * resetPasswordByMobile        根据用户手机号重置密码
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

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->user_table = C("TABLE_NAME_USER");
    }

    /**
     * 用户信息获取
     * @return array $result 结果返回
     *
     * 此方法会为 user_info 赋值
     *
     */
    public function getUserInfo($type = "user_id"){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

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
                    $return_info['last_login'] = $user_info['logintime'];
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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        //重复性检查
        $info = $where = array();
        $where['mobile'] = $this->user_mobile;
        $info = M($this->user_table)->where($where)->find();
        if(empty($info)){
            //生成一个安全码
            $safe_code = substr(md5(time()),0,10);
            //基础参数组装
            $add = array();
            $add['password'] = md5($this->user_password.$safe_code);
            $add['safe_code'] = $safe_code;
            $add['mobile'] = $this->user_mobile;
            $add['inputtime'] = $add['updatetime'] = time();
            $this->user_id = M($this->user_table)->add($add);
            if(!empty($this->user_id)){
                $result['state'] = 1;
                $result['message'] = "注册成功";
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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        //数据获取
        $info = array();
        $info = $this->getUserInfo("mobile");
        //身份是0才可以登录
        if($info['state'] == 1 && !empty($info['result']['user_id']) && $info['result']['identity'] == 0){
            //密码核对
            $password = md5($this->user_password.$this->user_info['safe_code']);
            if($password == $this->user_info['password']){
                //将用户id记录到session中
                session(C("HOME_USER_ID_SESSION_STR"),$this->user_info['id']);
                //为user_id赋值
                $this->user_id = $this->user_info['id'];

                //更新最后登录时间
                $save = $where = array();
                $where['id'] = $this->user_id;
                $save['logintime'] = time();
                M($this->user_table)->where($where)->save($save);

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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

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
                    }else{
                        $result['message'] = "修改失败";
                    }
                }else{
                    $result['message'] = $check_result['message'];
                }
            }else{
                $result['message'] = "旧密码错误";
            }
        }else{
            $result['message'] = "用户信息未获取";
        }

        return $result;
    }


    /**
     * 根据用户手机号重置密码
     * @return $result $result 结果返回
     */
    public function resetPasswordByMobile(){

        /**
         * 此方法暂时是直接将密码重置为手机号后8位
         * so 暂不提供给用户自己调用
         */

        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

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
     * 参数检验（顺带过滤）
     * @param string $str 参数标示
     * @return array $result 结果返回
     *
     * 此方法可能会改变 user_id、user_name、user_password、nick_name与user_mobile的值
     *
     */
    public function checkParam($str = ""){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

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
                $param = strip_tags(trim($this->nick_name));
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
