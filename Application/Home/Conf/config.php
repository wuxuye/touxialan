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

    /* logo图片地址 */
    "HOME_WEB_TOP_LOGO_IMAGE_URL" => "/Uploads/goods_images/2016-09-06/57ce32fd8ffce.jpg",

    /* 首页相关 */
    "HOME_INDEX_MAX_NOTICE_NUM" => 5, //首页显示最大公告数
    "HOME_INDEX_MAX_ACTIVITY_NUM" => 5, //首页显示最大活动数

    /* 前台用户相关 */
    "HOME_USER_MAX_RECEIPT_ADDRESS_NUM" => 3, //一个用户最大可拥有的收货地址数

    /* 商品相关 */
    "HOME_GOODS_DEFAULT_EMPTY_IMAGE_URL" => "Public/home/image/empty.jpg", //商品空图片连接
    "HOME_GOODS_DEFAULT_UNIT" => "个", //商品默认计量单位
    "HOME_GOODS_LIST_ONE_COLUMN_MAX_GOODS_NUM" => 5, //一个栏目展示的最大商品数量
    "HOME_GOODS_MAX_SEARCH_NUM" => 30, //商品列表搜索时最大的长度限制
    "HOME_GOODS_MAX_PARTICIPLE_NUM" => 3, //商品列表搜索时最大的分词限制
    "HOME_ALL_GOODS_LIST_MAX_GOODS_NUM" => 10, //全商品列表的一页最大商品数量


    /* 用户清单相关 */
    "HOME_CART_MAX_GOODS_NUM" => 20, //清单中最大能有的商品数量（0表示不限）

    /* 用户订单相关 */
    "HOME_ORDER_MAX_USER_WAIT_CONFIRM_NUM" => 2, //用户最多能拥有的未确认订单数
    "HOME_ORDER_MAX_USER_WAIT_SUCCESS_NUM" => 5, //用户最多能拥有的待完结订单数
    "HOME_USER_ORDER_LIST_MAX_ORDER_NUM" => 10, //用户订单列表一页显示订单数

    /* 用户消息相关 */
    "HOME_USER_MESSAGE_LIST_MAX_ORDER_NUM" => 10, //用户消息列表一页显示消息数

    /* 公告活动列表相关 */
    "HOME_NOTICE_LIST_MAX_SHOW_NUM" => 10, //公告列表一页显示公告数
    "HOME_ACTIVITY_LIST_MAX_SHOW_NUM" => 10, //活动列表一页显示活动数

    /* 导航栏列表 */
    "HOME_PUBLIC_NAV_LIST" => [
        [
            "str" => "瞧一瞧", //显示名
            "url" => "/", //url
            "param" => "index", //选中参数
        ],
        [
            "str" => "逛一逛",
            "url" => "/Home/Goods/goodsList",
            "param" => "list",
        ],
        [
            "str" => "我的清单",
            "url" => "/Home/Cart/cartList",
            "param" => "cart",
            "is_login" => 1, //必须登录
        ],
    ],

    /* 个人中心侧边栏列表 */
    "HOME_USER_CENTER_TAG_LIST" => [
        [
            "str" => "我的订单", //显示名
            "url" => "/Home/UserCenter/userOrderList", //url
            "param" => "order", //选中参数
        ],
        [
            "str" => "修改密码",
            "url" => "/Home/UserCenter/userEditPassword",
            "param" => "password",
        ],
        [
            "str" => "收货地址",
            "url" => "/Home/UserCenter/userReceiptAddressList",
            "param" => "address",
        ],
        [
            "str" => "消息提醒",
            "url" => "/Home/UserCenter/userMessage",
            "param" => "message",
        ],
        [
            "str" => "个人设置",
            "url" => "/Home/UserCenter/userInfo",
            "param" => "user_info",
        ],
    ],

    /* 过滤词汇表 */
    "HOME_GOODS_LIST_KEYWORD_FILTRATION_LIST" => [
        "艹","操","草","艹你妈","操你妈","草你妈"
    ],

);