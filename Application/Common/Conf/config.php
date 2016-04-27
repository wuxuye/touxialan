<?php
return array(

    //域名
    "WEB_DOMAIN" => "http://www.txl.com",

    //资源文件路径(前台)
    "_HOME_IMG_" => "/Public/home/image",
    "_HOME_CSS_" => "/Public/home/css",
    "_HOME_JS_" => "/Public/home/js",

    //资源文件路径(后台)
    "_ADMIN_IMG_" => "/Public/admin/image",
    "_ADMIN_CSS_" => "/Public/admin/css",
    "_ADMIN_JS_" => "/Public/admin/js",

    //存在session中的user_id标示
	"HOME_USER_ID_SESSION_STR" => "txl_user_id",

    //后台默认管理员id
    "ADMIN_DEFAULT_USER_ID" => "1",

    //后台被允许的管理员列表
    "ADMIN_ALLOW_USER_ID_LIST" => array(
        1,
    ),

    //后台上传商品图片的存放路径
    "ADMIN_GOODS_IMAGE_FILE_URL" => "/Uploads/goods_images",

    //相关表明
    "TABLE_NAME_GOODS" => "goods", //商品表
    "TABLE_NAME_USER" => "user", //用户表
    "TABLE_NAME_ATTR" => "attr", //属性表

    //相关状态
    //====== 用户表状态 ======
    "STATE_USER_FREEZE" => 0, //用户表的冻结状态
    "STATE_USER_NORMAL" => 1, //用户表的正常状态
    "STATE_USER_DELETE" => 2, //用户表的删除状态
    "STATE_USER_STATE_LIST" => array( //用户表状态列表
        "0" => "冻结",
        "1" => "正常",
        "2" => "删除",
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

    //====== 属性表状态 ======
    "STATE_ATTR_DELETE" => 0, //属性表的删除状态
    "STATE_ATTR_NORMAL" => 1, //属性表的正常状态
    "STATE_ATTR_STATE_LIST" => array( //属性表状态列表
        "0" => "删除",
        "1" => "正常",
    ),

    //后台脚本列表
    "ADMIN_JIAO_BEN_LIST" => array(
        "cleanGoodsImage" => "商品图片清理",
    ),

);