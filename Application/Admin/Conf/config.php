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
        "Order" => [
            "show_tab" => "订单相关",
            "show_list" => [
                "Order/orderList" => '订单列表',
                "Order/orderUnifiedOperation" => '订单统一操作',
            ],
        ],
        "Goods" => [
            "show_tab" => "商品相关",
            "show_list" => [
                "Goods/goodsList" => '商品列表',
                "Tags/tagsList" => '标签列表',
                "Attr/attrList" => '属性列表',
                "Goods/goodsStockLog" => '商品库存记录',
            ],
        ],
        "User" => [
            "show_tab" => "用户相关",
            "show_list" => [
                "User/userList" => '用户列表',
                "User/userMessageList" => '用户消息记录列表',
                "User/userPointList" => '用户积分记录列表',
                "User/userFeedbackList" => '用户反馈记录列表',
            ],
        ],
        "Activity" => [
            "show_tab" => "活动相关",
            "show_list" => [
                "Activity/activityList" => '活动列表',
                "ActivityQuestion/questionBankList" => '每日问答 - 题库列表',
            ],
        ],
        "Statistics" => [
            "show_tab" => "统计相关",
            "show_list" => [
                "Statistics/fundStatistics" => '资金统计',
                "Statistics/orderStatistics" => '订单统计',
                "Statistics/saleStatistics" => '销量统计',
                "Statistics/attrStatisticsList" => '属性统计列表',
                "ActivityQuestion/dataStatistics" => '每日问答 - 数据统计',
            ],
        ],
        "Fund" => [
            "show_tab" => "资金相关",
            "show_list" => [
                "Fund/fundList" => '资金流水',
            ],
        ],
        "Article" => [
            "show_tab" => "文章相关",
            "show_list" => [
                "Article/noticeList" => '公告列表',
            ],
        ],
        "JiaoBen" => [
            "show_tab" => "脚本相关",
            "show_list" => [
                "JiaoBen" => '脚本列表',
            ],
        ],
        "Param" => [
            "show_tab" => "全站参数配置",
            "show_list" => [
                "Param/operationList" => '总操作列表',
                "Param/allOperationControl" => '酷炫霸气的烨哥总控台',
            ]
        ],
    ],

    //后台脚本列表
    "ADMIN_JIAO_BEN_LIST" => [
        "cleanGoodsImage"           => "商品图片清理",
        "cleanGoodsTags"            => "商品标签清理",
        "cleanQuestionImage"        => "每日问答活动题目图片清理",
        "getPublishQuestionInfo"    => "获取当前发布的题目信息（手动触发题目发布）",
        "cleanQuestionStatistics"   => "清理题目统计",
        "cleanGoodsFileLock"        => "清理商品文件锁",
    ],

    //后台页码相关
    "ADMIN_ORDER_LIST_PAGE_SHOW_NUM" => 10,             //管理员后台订单列表单页数量显示
    "ADMIN_GOODS_LIST_PAGE_SHOW_NUM" => 20,             //管理员后台商品列表单页数量显示
    "ADMIN_GOODS_STOCK_LOG_LIST_PAGE_SHOW_NUM" => 20,   //管理员后台商品库存信息列表单页数量显示
    "ADMIN_TAGS_LIST_PAGE_SHOW_NUM" => 20,              //管理员后台标签列表单页数量显示
    "ADMIN_USER_LIST_PAGE_SHOW_NUM" => 20,              //管理员后台用户列表单页数量显示
    "ADMIN_USER_MESSAGE_LIST_PAGE_SHOW_NUM" => 20,      //管理员后台用户消息记录列表单页数量显示
    "ADMIN_USER_POINT_LIST_PAGE_SHOW_NUM" => 20,        //管理员后台用户积分记录列表单页数量显示
    "ADMIN_USER_FEEDBACK_LIST_PAGE_SHOW_NUM" => 20,     //管理员后台用户反馈信息列表单页数量显示
    "ADMIN_QUESTION_BANK_LIST_PAGE_SHOW_NUM" => 20,     //管理员后台每日问答活动列表单页数量显示
    "ADMIN_NOTICE_LIST_PAGE_SHOW_NUM" => 20,            //管理员后台公告列表单页数量显示
    "ADMIN_ACTIVITY_LIST_PAGE_SHOW_NUM" => 20,          //管理员后台活动列表单页数量显示
    "ADMIN_FUND_LIST_PAGE_SHOW_NUM" => 20,              //管理员后台资金流水列表单页数量显示

    //后台列表字段搜索列表
    //============订单表相关============
    "ADMIN_ORDER_LIST_SEARCH_TIME_TYPE_LIST" => [ //订单列表时间搜索列表
        "1" => "下单时间",
        "2" => "订单确认时间",
        "3" => "确认付款时间",
    ],
    "ADMIN_ORDER_LIST_SEARCH_INFO_TYPE_LIST" => [ //订单列表字段搜索列表
        "1" => "订单id",
        "2" => "订单序列号",
        "3" => "用户手机号",
        "4" => "配送详细地址",
        "5" => "用户确认手机号",
        "6" => "备注信息",
    ],
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
    "ADMIN_USER_LIST_SEARCH_INFO_TYPE_LIST" => [ //用户列表字段搜索列表
        "1" => "用户id",
        "2" => "用户名",
        "3" => "用户昵称",
    ],

    "ADMIN_USER_FEEDBACK_LIST_SEARCH_TIME_TYPE_LIST" => [ //用户反馈信息列表时间搜索列表
        "1" => "问题反馈时间",
        "2" => "问题解决时间",
    ],
    "ADMIN_USER_FEEDBACK_LIST_SEARCH_INFO_TYPE_LIST" => [ //用户反馈信息列表字段搜索列表
        "1" => "用户手机",
        "2" => "反馈信息",
        "3" => "解决方案",
    ],
    //============活动表相关============
    "ADMIN_ACTIVITY_QUESTION_BANK_LIST_SEARCH_TIME_TYPE_LIST" => [ //活动 - 题库列表时间搜索列表
        "1" => "添加时间",
        "2" => "最后发布时间",
    ],
    "ADMIN_ACTIVITY_QUESTION_BANK_LIST_SEARCH_INFO_TYPE_LIST" => [ //活动 - 题库列表字段搜索列表
        "1" => "题目编号",
        "2" => "题目内容",
    ],

    //============文章相关============
    "ADMIN_NOTICE_LIST_SEARCH_TIME_TYPE_LIST" => [ //公告列表时间搜索列表
        "1" => "发布时间",
        "2" => "最后更新时间",
    ],
    "ADMIN_NOTICE_LIST_SEARCH_INFO_TYPE_LIST" => [ //公告列表字段搜索列表
        "1" => "公告标题",
        //"2" => "公告内容",
    ],

);