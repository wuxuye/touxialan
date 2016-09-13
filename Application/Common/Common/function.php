<?php

/**
 * 公共函数库
 *
 * 相关方法
 *
 * =======获取数据相关=======
 * get_login_user_info  获取当前登录用户信息
 * get_session          session数据获取
 * get_week_time        获取本周的开始结束时间
 *
 * =======数据判断相关=======
 * is_mobile            验证手机号码
 * is_date              验证时间格式
 * wait_action          方法等待检测
 * check_shop_time      营业时间检测
 * get_work_time        获取工作时间
 *
 * =======功能相关=======
 * cut_str              字符串判断，在规定长度内 就原样返回，否则截取加...
 * hidden_mobile        隐藏手机号
 * set_login_back_url   设置登录后的回跳url
 * check_str            字符串过滤
 * check_int            数字转换取整
 * set_file_lock        设置文件锁
 * check_file_lock      检查文件锁
 * delete_file_lock     删除文件锁
 * update_session_time  更新session有效期
 *
 * =======测试相关=======
 * P                    测试打印
 * VP                   测试var_dump打印
 * Q                    测试打印最后条sql
 *
 * =======其他相关=======
 * add_user_message     用户数据操作记录
 * add_wrong_log        错误的日志记录
 * add_operation_log    记录操作日志
 *
 */

/**
 * 获取当前登录用户信息
 * @return array $user_info 用户基础信息
 */
function get_login_user_info(){
    $user_info = [];
    $user_id = get_session(C("HOME_USER_ID_SESSION_STR"));

    if(!empty($user_id)){
        //尝试用user_id获取数据
        $user_obj = new \Yege\User();
        $obj_result = [];
        $user_obj->user_id = $user_id;
        $obj_result = $user_obj->getUserInfo();
        if($obj_result['state'] == 1){
            $user_info = $obj_result['result'];
        }
    }

    return $user_info;
}

/**
 * session数据获取
 * @param string $session_name 要获取的session名
 * @return array $session_result 结果返回
 */
function get_session($session_name = ""){
    $session_result = "";
    $session_name = check_str($session_name);
    if(!empty($session_name)){
        $session_result = $_SESSION[$session_name];
    }
    return $session_result;
}

/**
 * 获取本周的开始结束时间
 * @return array $result 结果返回
 */
function get_week_time(){
    $result = [];

    //拿到逻辑时间
    $week = date("w",time());
    $week = empty($week) ? 7 : check_int($week);

    //将时间减到周一
    $date = strtotime(date("Y-m-d 00:00:00",time()-($week-1)*24*60*60));

    $start_time = $date;
    $end_time = $date+7*24*60*60-1;

    if(!empty($start_time) && !empty($end_time)){
        $result = [
            "start_time" => $start_time,
            "end_time" => $end_time,
        ];
    }

    return $result;
}

/**
 * 验证手机号码
 * @param string $mobile
 */
function is_mobile($mobile = ""){

    $chars = "/^1(3|4|5|7|8)\d{9}$/";
    if (preg_match($chars, $mobile)){
        return true;
    }

    return false;
}

/**
 * 验证时间格式
 * @param string $date_string 时间字符串
 * @param string $date_format 时间格式
 */
function is_date($date_string = "",$date_format = "Y-m-d"){
    if(date($date_format,strtotime($date_string)) == $date_string){
        return true;
    }
    return false;
}

/**
 * 方法等待检测
 * @param int $time 等待时间
 */
function wait_action($time = 5){
    $str = "wait_".ACTION_NAME;
    $session_time = check_int(get_session($str));
    if($session_time > time()){
        return false;
    }else{
        session($str,time()+$time);
        return true;
    }
}

/**
 * 营业时间检测
 */
function check_shop_time(){
    $shop_week_list = C("SHOP_WEEK_LIST");
    $week = date("w",time());
    if(in_array($week,$shop_week_list)){
        return 1;
    }
    return 0;
}

/**
 * 获取工作时间
 * @return array $result 结果返回
 */
function get_work_time(){
    $result = [
        "now_doing" => [],
        "next_confirm" => [],
        "next_send" => [],
    ];
    //工作周
    $shop_week_list = C("SHOP_WEEK_LIST");
    //工作时间
    $shop_work_list = C("SHOP_WORK_LIST");
    //当前时间
    $now_time = time();
    //当前起始时间
    $day_time = strtotime(date("Y-m-d 00:00:00",$now_time));
    //时隔
    $time = check_int($now_time - $day_time);
    //当前周
    $now_week = date("w",$now_time);
    //下个开门周
    $next_week = -1;
    foreach($shop_week_list as $info){
        if($info == 0){ //将0转化为7
            $info = 7;
        }
        if(($now_week < $info) && ($next_week == -1 || $next_week > $info)){
            $next_week = $info;
        }
    }
    $next_week = check_int($next_week);
    //下个开门起始时间
    $next_day = 0;
    if($next_week >= 1 && $next_week <= 7){
        $temp_week = empty($now_week) ? 7 : $now_week;
        $day = 0;
        $count = 0;
        while($temp_week != $next_week){
            $temp_week++;
            $temp_week = $temp_week > 7 ? 1 : $temp_week;
            $day ++;
            $count ++;
            if($count == 7){
                //异常跳出
                $day = 0;
                break;
            }
        }
        $next_day = $day_time + $day*24*60*60;
    }

    //当前周在工作周的前提下
    if(in_array($now_week,$shop_week_list)){
        foreach($shop_work_list as $info){
            //记录当前事项
            if(($info['is_confirm'] == 1 || $info['is_send'] == 1) && $info["start_time"] <= $time && $info["end_time"] >= $time && empty($result["now_doing"])){
                $result["now_doing"] = [
                    "start_time" => $info["start_time"] + $day_time,
                    "end_time" => $info["end_time"] + $day_time,
                    "tip" => $info["tip"],
                    "is_day" => 1,
                    "time_str" => "今天",
                ];
            }
            //记录下个确认订单时间
            if($info["start_time"] > $time && $info['is_confirm'] == 1 && empty($result["next_confirm"])){
                $result["next_confirm"] = [
                    "start_time" => $info["start_time"] + $day_time,
                    "end_time" => $info["end_time"] + $day_time,
                    "tip" => $info["tip"],
                    "is_day" => 1,
                    "time_str" => "今天",
                ];
            }
            //记录下个配送时间
            if($info["start_time"] > $time && $info['is_send'] == 1 && empty($result["next_send"])){
                $result["next_send"] = [
                    "start_time" => $info["start_time"] + $day_time,
                    "end_time" => $info["end_time"] + $day_time,
                    "tip" => $info["tip"],
                    "is_day" => 1,
                    "time_str" => "今天",
                ];
            }
        }
    }

    //为空值附上下次最近的对应事项
    if(!empty($next_day)){
        foreach($shop_work_list as $info){

            if(!empty($result["next_confirm"]) && !empty($result["next_send"])){
                break;
            }

            //记录下个确认订单时间
            if(!empty($info['is_confirm']) && empty($result["next_confirm"])){
                $result["next_confirm"] = [
                    "start_time" => $info["start_time"] + $next_day,
                    "end_time" => $info["end_time"] + $next_day,
                    "tip" => $info["tip"],
                    "is_day" => 0,
                    "time_str" => (empty($now_week)?"下":"").C("WEEK_STR_LIST")[$next_week],
                ];
                continue;
            }
            //记录下个配送时间
            if(!empty($info['is_send']) && empty($result["next_send"])){
                $result["next_send"] = [
                    "start_time" => $info["start_time"] + $next_day,
                    "end_time" => $info["end_time"] + $next_day,
                    "tip" => $info["tip"],
                    "is_day" => 0,
                    "time_str" => (empty($now_week)?"下":"").C("WEEK_STR_LIST")[$next_week],
                ];
                continue;
            }
        }
    }

    return $result;
}

/**
 * 字符串判断，在规定长度内 就原样返回，否则截取加...
 * 判断$str长度是否大于$length，是用$code截掉返回，不大于就原样返回
 * @param string $str 待处理字符串
 * @param int $length 规定长度
 * @param string $code 规定编码
 * @return string $result_str 结果字符串
 */
function cut_str($str="",$length=10,$code="utf-8"){
    $str = check_str($str);
    $length = check_int($length);
    $result_str = "";
    if(!empty($str) && $length>0){
        $str_len = mb_strlen($str,$code);
        if($str_len<=$length){
            $result_str = $str;
        }else{
            $result_str = mb_substr($str,0,$length,$code)."...";
        }
    }
    return $result_str;
}

/**
 * 隐藏手机号
 * @param string $mobile 要操作的手机号
 * @return string $result 结果返回
 */
function hidden_mobile($mobile = ""){
    $result = substr($mobile,0,3)."****".substr($mobile,-4,4);
    return $result;
}

/**
 * 设置登录后的回跳url
 * @param string $url 指定url
 */
function set_login_back_url($url = ""){
    if(empty($url)){
        $url = empty(__SELF__) ? "/".MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME : __SELF__;
    }

    $url = empty($url) ? "/" : $url;

    session(C("HOME_LOGIN_BACK_URL_SESSION_STR"),$url);
}

/**
 * 字符串过滤
 * @param string $str 待操作字符串
 * @return string $result_str 结果字符串返回
 */
function check_str($str = ""){
    $str = strip_tags(trim($str));
    return $str;
}

/**
 * 数字转换取整
 * @param string $str 待操作字符串
 * @return int $result_int 结果整数返回
 */
function check_int($str = ""){
    $result_int = (int)$str;
    return $result_int;
}

/**
 * 设置文件锁
 * @param string $file_name 文件名
 * @param string $folder 目录名
 */
function set_file_lock($file_name = '',$folder = ""){
    //目录检查
    $url = C("_FILE_LOCK_URL_").$folder;
    if(!file_exists($url)){
        mkdir($url);
    }
    //文件检查
    $url = C("_FILE_LOCK_URL_").$folder."/".$file_name;
    if(!file_exists($url)){
        fopen($url,"w");
    }
}

/**
 * 检查文件锁
 * @param string $file_name 文件名
 * @param string $folder 目录名
 * @param bool true 存在 false 不存在
 */
function check_file_lock($file_name = '',$folder = ""){
    //文件检查
    $url = C("_FILE_LOCK_URL_").$folder."/".$file_name;
    if(file_exists($url)){
        return true;
    }
    return false;
}

/**
 * 删除文件锁
 * @param string $file_name 文件名
 * @param string $folder 目录名
 * @param bool true 存在 false 不存在
 */
function delete_file_lock($file_name = '',$folder = ""){
    //文件检查
    $url = C("_FILE_LOCK_URL_").$folder."/".$file_name;
    if(file_exists($url)){
        unlink($url);
    }
}

/**
 * 更新session有效期
 */
function update_session_time(){
    //将当前的session里的用户id再次设置
    $user_id = get_session(C("HOME_USER_ID_SESSION_STR"));
    if(!empty($user_id)){
        cookie(C("HOME_SESSION_COOKIE_NAME"),cookie(C("HOME_SESSION_COOKIE_NAME")),604800);
    }
}

/**
 * 测试打印
 * @param array $array 需要打印的数组
 * @return array $result 打印结果
 */
function P($array = array()){
    echo "<pre>";
    print_r($array);
    exit;
}

/**
 * 测试var_dump打印
 * @param array $array 需要打印的数组
 * @return array $result 打印结果
 */
function VP($array = array()){
    var_dump($array);
    exit;
}

/**
 * 测试打印最后条sql
 * @return array $result 打印结果
 */
function Q(){
    echo "<br>".M()->_sql()."<br>";
    exit;
}

/**
 * 用户数据操作记录
 * @param int $user_id 用户id
 * @param string $remark 操作信息
 * @param int $is_show 是否需要显示在前台
 * @param int $is_check_remark 是否要处理备注信息
 */
function add_user_message($user_id = 0,$remark = "",$is_show = 0,$is_check_remark = 1){
    $user_id = check_int($user_id);
    $remark = $is_check_remark == 1 ? check_str($remark) : trim($remark);
    if(!empty($user_id) && !empty($remark)){
        $is_show = !empty($is_show) ? 1 : 0;
        $add = [];
        $add['user_id'] = $user_id;
        $add['remark'] = $remark;
        $add['is_show'] = $is_show;
        $add['inputtime'] = time();
        if(M(C("TABLE_NAME_USER_MESSAGE"))->add($add)){
            if($is_show == 1){
                //有需要显示给前台用户看的记录添加时，修改用户表的相关信息
                $save = $where = [];
                $where['id'] = $user_id;
                $save['is_message_tip'] = 1;
                M(C("TABLE_NAME_USER"))->where($where)->save($save);
            }
        }else{
            add_wrong_log("添加用户操作记录的时候添加失败，相关参数 user_id：".$user_id."，remark：".$remark."，is_show：".$is_show);
        }
    }else{
        add_wrong_log("添加用户操作记录的时候参数缺失，相关参数 user_id：".$user_id."，remark：".$remark);
    }
}

/**
 * 错误的日志记录
 * @param string $log 要记录的错误信息
 */
function add_wrong_log($log = ""){
    if(!empty($log)){

        if(!file_exists(C("_WRONG_FILE_URL_"))){
            mkdir(C("_WRONG_FILE_URL_"));
        }

        $log .= "\r\n逻辑时间：".date("Y-m-d H:i:s",time())."\r\n\r\n";

        $file_name = date("Y-m-d",time())."_log.txt";
        $url = C("_WRONG_FILE_URL_").$file_name;
        $file = fopen($url,"a+");
        fwrite($file,$log);
        fclose($file);
    }
}

/**
 * 记录操作日志
 * @param string $log 要记录的操作信息
 * @param string $folder 对应文件夹
 */
function add_operation_log($log = "",$folder = ""){
    if(!empty($log) && !empty($folder)){
        $url = C("_OPERATION_LOG_FILE_URL_").$folder;
        if(!file_exists($url)){
            mkdir($url);
        }
        $log .= "\r\n逻辑时间：".date("Y-m-d H:i:s",time())."\r\n\r\n";
        $file_name = date("Y-m-d",time())."_log.txt";
        $url .= $file_name;
        $file = fopen($url,"a+");
        fwrite($file,$log);
        fclose($file);
    }
}