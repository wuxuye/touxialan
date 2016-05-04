<?php
return array(

    //域名
    "WEB_DOMAIN" => "http://www.txl.com",

    //存在session中的user_id标示
	"HOME_USER_ID_SESSION_STR" => "txl_user_id",

    //错误日志文件路径
    "_WRONG_FILE_URL_" => "/Log/wrong_log/",

    //相关表明
    "TABLE_NAME_ATTR" => "attr", //属性表
    "TABLE_NAME_GOODS" => "goods", //商品表
    "TABLE_NAME_GOODS_TAG_RELATE" => "goods_tag_relate", //商品标签关联表
    "TABLE_NAME_TAGS" => "tags", //标签表
    "TABLE_NAME_USER" => "user", //用户表
    "TABLE_NAME_USER_POINTS" => "user_points", //用户积分表
    "TABLE_NAME_USER_POINTS_LOG" => "user_points_log", //用户积分日志表

    //相关状态
    //====== 属性表状态 ======
    "STATE_ATTR_DELETE" => 0, //属性表的删除状态
    "STATE_ATTR_NORMAL" => 1, //属性表的正常状态
    "STATE_ATTR_STATE_LIST" => array( //属性表状态列表
        "0" => "删除",
        "1" => "正常",
    ),

    //====== 商品表状态 ======
    "STATE_GOODS_LOCK" => 0, //商品表的锁定状态
    "STATE_GOODS_NORMAL" => 1, //商品表的正常状态
    "STATE_GOODS_DELETE" => 2, //商品表的删除状态
    "STATE_GOODS_STATE_LIST" => array( //商品表状态列表
        "0" => "锁定",
        "1" => "正常",
        "2" => "删除",
    ),

    "STATE_GOODS_UNSHELVE" => 0, //商品表的下架状态
    "STATE_GOODS_SHELVE" => 1, //商品表的上架状态
    "STATE_GOODS_IS_SHOP_LIST" => array( //商品表商品上下架状态列表
        "0" => "未上架", //下架
        "1" => "正常", //上架
    ),

    //====== 标签表状态 ======
    "STATE_TAGS_DELETE" => 0, //标签表的删除状态
    "STATE_TAGS_NORMAL" => 1, //标签表的正常状态
    "STATE_TAGS_STATE_LIST" => array( //标签表状态列表
        "0" => "删除",
        "1" => "正常",
    ),

    //====== 用户表状态 ======
    "STATE_USER_FREEZE" => 0, //用户表的冻结状态
    "STATE_USER_NORMAL" => 1, //用户表的正常状态
    "STATE_USER_DELETE" => 2, //用户表的删除状态
    "STATE_USER_STATE_LIST" => array( //用户表状态列表
        "0" => "冻结",
        "1" => "正常",
        "2" => "删除",
    ),

    "IDENTITY_USER_USERS" => 0, //用户表的普通用户身份
    "IDENTITY_USER_ADMIN" => 1, //用户表的管理员用户身份
    "IDENTITY_USER_STATE_LIST" => array( //用户表身份列表
        "0" => "用户",
        "1" => "管理员",
    ),

    //积分相关
    "POINT_ACTIVITY_LIST" => array( //积分变更相关信息列表
        //积分增加类
        "new_user_register" => array( //新用户注册
            "point" => "3", //涉及积分
            "log" => "新用户注册", //简短日志
            "remark" => "欢迎新用户，注册即送5点积分", //详细说明
        ),
//        "new_user_register_activity" => array( //新用户注册活动
//            "start_time" => '2016-05-01',
//            "end_time" => '2016-05-04',
//            "point" => "2",
//            "log" => "新用户注册活动",
//            "remark" => "活动期间再送2点",
//        ),


        //积分减少类


        //积分变更类
        "admin_update_point" => array( //管理员变更积分
            "point" => "~", //自定义
            "log" => "系统操作",
            "remark" => "系统操作",
        ),
    ),

);