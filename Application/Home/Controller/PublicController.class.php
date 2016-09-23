<?php

namespace Home\Controller;
use Think\Controller;

/**
 * 公共控制器
 */

class PublicController extends Controller {

    protected function _initialize(){
        header("Content-Type: text/html; charset=utf-8");

        $Param = new \Yege\Param();
        $web_state = $Param->getDataByParam("webState");
        if(!empty($web_state['data']['is_close'])){
            $this->error("暂不营业，老板正在整理东西。。。");
        }

        //登录用户信息
        $now_user = get_login_user_info();
        if(!empty($now_user)){
            $now_user['show_nick_name'] = "";
            $now_user['show_nick_name_str'] = "";
            //显示用用户名
            if(!empty($now_user['nick_name'])){
                $now_user['show_nick_name'] = $now_user['nick_name'];
            }elseif(!empty($now_user['username'])){
                $now_user['show_nick_name'] = $now_user['username'];
            }elseif(!empty($now_user['mobile'])){
                $now_user['show_nick_name'] = $now_user['mobile'];
            }

            $now_user['show_nick_name_str'] = cut_str($now_user['show_nick_name'],12);
        }
        $this->now_user_info = $now_user;

        //登录对导航栏的影响
        $nav_list = C('HOME_PUBLIC_NAV_LIST');
        foreach($nav_list as $key => $val){
            if(!empty($val['is_login']) && empty($this->now_user_info)){
                unset($nav_list[$key]);
            }
        }
        $this->nav_list = $nav_list;

    }

    /**
     * 操作错误跳转的快捷方法
     */
    protected function error($message='') {

        $this->home_head_left_title = "发生错误";

        $this->dispatchJump($message,0);
    }

    /**
     * 操作成功跳转的快捷方法
     */
    protected function success($message='') {

        $this->home_head_left_title = "操作成功";

        $this->dispatchJump($message,1);
    }

    /**
     * 跳转重写
     */
    private function dispatchJump($message,$status=1) {

        $this->hidden_nav = 1;

        //保证输出不受静态缓存影响
        C('HTML_CACHE_ON',false);
        if($status) { //发送成功信息
            $this->assign('message',$message);// 提示信息
            $this->display('Template/successShow');
        }else{
            $this->assign('message',$message);// 提示信息
            $this->display('Template/errorShow');
        }
        // 中止执行  避免出错后继续执行
        exit ;
    }



}