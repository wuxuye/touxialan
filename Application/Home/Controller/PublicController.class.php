<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 公共控制器
 */

class PublicController extends Controller {

    protected function _initialize(){
        header("Content-Type: text/html; charset=utf-8");

        $now_user = get_login_user_info();

        if(!empty($now_user)){
            $now_user['show_nick_name'] = "";
            $now_user['show_nick_name_str'] = "";
            //显示用用户名
            if(!empty($now_user['nick_name'])){
                $now_user['show_nick_name'] = $now_user['nick_name'];
            }
            if(!empty($now_user['username'])){
                $now_user['show_nick_name'] = $now_user['username'];
            }
            if(!empty($now_user['mobile'])){
                $now_user['show_nick_name'] = $now_user['mobile'];
            }
            $now_user['show_nick_name_str'] = cut_str($now_user['show_nick_name'],12);
        }

        $this->now_user_info = $now_user;

    }

}