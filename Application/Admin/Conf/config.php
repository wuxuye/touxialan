<?php
return array(

    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',        // 数据库类型
    'DB_HOST'               =>  '127.0.0.1',    // 服务器地址
    'DB_NAME'               =>  'txl',          // 数据库名
    'DB_USER'               =>  'root',         // 用户名
    'DB_PWD'                =>  '',             // 密码
    'DB_PORT'               =>  '3306',         // 端口
    'DB_PREFIX'             =>  'tl_',          // 数据库表前缀

    //资源文件路径(后台)
    "_ADMIN_IMG_" => "/Public/admin/image",
    "_ADMIN_CSS_" => "/Public/admin/css",
    "_ADMIN_JS_" => "/Public/admin/js",

    //后台默认管理员id
    "ADMIN_DEFAULT_USER_ID" => "1",

    //后台被允许的管理员列表
    "ADMIN_ALLOW_USER_ID_LIST" => array(
        1,
    ),

    //后台上传商品图片的存放路径
    "ADMIN_GOODS_IMAGE_FILE_URL" => "/Uploads/goods_images",

    //后台脚本列表
    "ADMIN_JIAO_BEN_LIST" => array(
        "cleanGoodsImage" => "商品图片清理",
        "cleanGoodsTags" => "商品标签清理",
    ),

    //后台页码相关
    "ADMIN_GOODS_LIST_PAGE_SHOW_NUM" => 20, //管理员后台商品列表单页数量显示
    "ADMIN_TAGS_LIST_PAGE_SHOW_NUM" => 20, //管理员后台标签列表单页数量显示

    //后台列表字段搜索列表
    "ADMIN_GOODS_LIST_SEARCH_TIME_TYPE_LIST" => array( //商品列表时间搜索列表
        "1" => "商品添加时间",
    ),
    "ADMIN_GOODS_LIST_SEARCH_INFO_TYPE_LIST" => array( //商品列表字段搜索列表
        "1" => "商品id",
        "2" => "商品归属",
    ),

);