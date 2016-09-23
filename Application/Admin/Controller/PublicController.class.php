<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 公共控制器
 */

class PublicController extends Controller {

    protected function _initialize(){
        header("Content-Type: text/html; charset=utf-8");

        //admin模块检测用户
        $this->checkAdmin();



    }

    /**
     * 进入后台的用户检测逻辑
     */
    private function checkAdmin(){

        //常规检测， 登录用户要有管理员身份 和 在被允许的管理员列表中
        $admin_user_info = get_login_user_info();
        if($admin_user_info['identity'] != C("IDENTITY_USER_ADMIN") || !in_array($admin_user_info['id'],C("ADMIN_ALLOW_USER_ID_LIST"))){
            redirect("/Home/Template/errorShow");
        }

        //只能在火狐浏览器中登录后台
        if(stripos($_SERVER["HTTP_USER_AGENT"],'Firefox') === false){
            redirect("/Home/Template/errorShow");
        }

    }

}