<?php
return array(

    /* 前台数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'txl',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'tl_',    // 数据库表前缀


    /* 前台资源文件路径 */
    //图片、样式与js路径
    "_HOME_IMG_" => "/Public/home/image",
    "_HOME_CSS_" => "/Public/home/css",
    "_HOME_JS_" => "/Public/home/js",

    /* 未登录回跳用session */
    "HOME_LOGIN_BACK_URL_SESSION_STR" => "txl_login_back_url",

    /* 前台用户相关 */
    "HOME_USER_MAX_RECEIPT_ADDRESS_NUM" => 3, //一个用户最大可拥有的收货地址数

    /* 商品相关 */
    "HOME_GOODS_DEFAULT_EMPTY_IMAGE_URL" => "Public/home/image/empty.jpg", //商品空图片连接
    "HOME_GOODS_DEFAULT_UNIT" => "个", //商品默认计量单位
    "HOME_GOODS_LIST_ONE_COLUMN_MAX_GOODS_NUM" => 5, //一个栏目展示的最大商品数量
    "HOME_ALL_GOODS_LIST_MAX_GOODS_NUM" => 2, //全商品列表的一页最大商品数量



);