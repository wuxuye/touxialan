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
        $admin_user_info = get_login_user_info();
        if(!in_array($admin_user_info['user_identity'],C("ADMIN_ALLOW_USER_ID_LIST"))){
            $this->error("无效路径");
        }

    }

}