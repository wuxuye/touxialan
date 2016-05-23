<?php
return array(

    /* 后台数据库设置 */
    'DB_TYPE'               =>  'mysql',        // 数据库类型
    'DB_HOST'               =>  '127.0.0.1',    // 服务器地址
    'DB_NAME'               =>  'txl',          // 数据库名
    'DB_USER'               =>  'root',         // 用户名
    'DB_PWD'                =>  '',             // 密码
    'DB_PORT'               =>  '3306',         // 端口
    'DB_PREFIX'             =>  'tl_',          // 数据库表前缀


    /* 后台资源文件路径 */
    //图片、样式、js与bootstrap路径
    "_ADMIN_IMG_" => "/Public/admin/image",
    "_ADMIN_CSS_" => "/Public/admin/css",
    "_ADMIN_JS_" => "/Public/admin/js",
    "_ADMIN_BOOTSTRAP_" => "/Public/admin/bootstrap",

    //后台上传商品图片的存放路径
    "ADMIN_GOODS_IMAGE_FILE_URL" => "/Uploads/goods_images",

    //后台上传问题图片的存放路径
    "ADMIN_QUESTION_IMAGE_FILE_URL" => "/Uploads/question_images",

    //图片存储文件夹
    "ADMIN_SAVE_IMAGE_GOODS" => "goods_images", //商品图片文件夹
    "ADMIN_SAVE_IMAGE_QUESTION" => "question_images", //问答活动图片文件夹


    /* 后台管理员设置 */
    //后台默认管理员id
    "ADMIN_DEFAULT_USER_ID" => "1",

    //后台被允许的管理员列表
    "ADMIN_ALLOW_USER_ID_LIST" => [
        1,
    ],


    /* 后台列表展示相关 */
    //后台首页列表
    "ADMIN_INDEX_LIST" => [
        "Goods" => [
            "show_tab" => "商品相关",
            "show_list" => [
                "Goods/goodsList" => '商品列表',
                "Tags/tagsList" => '标签列表',
            ],
        ],
        "Attr" => [
            "show_tab" => "属性相关",
            "show_list" => [
                "Attr/attrList" => '属性列表',
            ],
        ],
        "User" => [
            "show_tab" => "用户相关",
            "show_list" => [
                "User/userList" => '用户列表',
                "User/userMessageList" => '用户消息记录列表',
                "User/userPointList" => '用户积分记录列表',
            ],
        ],
        "Activity" => [
            "show_tab" => "活动相关",
            "show_list" => [
                "Activity/questionBankList" => '每日问答 - 题库列表',
            ],
        ],
        "JiaoBen" => [
            "show_tab" => "脚本相关",
            "show_list" => [
                "JiaoBen" => '脚本列表',
            ],
        ],
    ],

    //后台脚本列表
    "ADMIN_JIAO_BEN_LIST" => [
        "cleanGoodsImage" => "商品图片清理",
        "cleanGoodsTags" => "商品标签清理",
        "cleanQuestionImage" => "每日问答活动题目图片清理",
    ],

    //后台页码相关
    "ADMIN_GOODS_LIST_PAGE_SHOW_NUM" => 20,         //管理员后台商品列表单页数量显示
    "ADMIN_TAGS_LIST_PAGE_SHOW_NUM" => 20,          //管理员后台标签列表单页数量显示
    "ADMIN_USER_LIST_PAGE_SHOW_NUM" => 20,          //管理员后台用户列表单页数量显示
    "ADMIN_USER_MESSAGE_LIST_PAGE_SHOW_NUM" => 20,  //管理员后台用户消息记录列表单页数量显示
    "ADMIN_USER_POINT_LIST_PAGE_SHOW_NUM" => 20,    //管理员后台用户积分记录列表单页数量显示
    "ADMIN_QUESTION_BANK_LIST_PAGE_SHOW_NUM" => 20, //管理员后台每日问答活动列表单页数量显示

    //后台列表字段搜索列表
    //============商品表相关============
    "ADMIN_GOODS_LIST_SEARCH_TIME_TYPE_LIST" => [ //商品列表时间搜索列表
        "1" => "商品添加时间",
    ],
    "ADMIN_GOODS_LIST_SEARCH_INFO_TYPE_LIST" => [ //商品列表字段搜索列表
        "1" => "商品id",
        "2" => "商品归属(手机)",
    ],
    //============用户表相关============
    "ADMIN_USER_LIST_SEARCH_TIME_TYPE_LIST" => [ //用户列表时间搜索列表
        "1" => "用户注册时间",
        "2" => "最后活跃时间",
    ],
    "ADMIN_USER_LIST_SEARCH_INFO_TYPE_LIST" => [ //商品列表字段搜索列表
        "1" => "用户id",
        "2" => "用户名",
        "3" => "用户昵称",
    ],

);