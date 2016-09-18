<?php
return array(

    /* 网站相关 */
    "WEB_DOMAIN" => "http://www.txl.com", //域名

    /* session相关 */
    "HOME_SESSION_COOKIE_NAME" => "TXLUSERSESSION", //session存在用户端的cookie名
	"HOME_USER_ID_SESSION_STR" => "txl_user_id", //存在session中的user_id标示

    /* 日志相关 */
    "_WRONG_FILE_URL_" => "./Log/wrong_log/", //错误日志文件路径
    "_OPERATION_LOG_FILE_URL_" => "./Log/", //操作日志根目录

    /* 各种日志文件夹列表 */
    "GOODS_STOCK_CHANGE_FOLDER_NAME" => "goods_stock/", //商品库存变化相关日志
    "ACTIVITY_QUESTION_FOLDER_NAME" => "activity_question/", //每日问答 题目相关日志

    /* 路径相关 */
    "_FILE_LOCK_URL_" => "./FileLock/", //文件锁根目录
    "_FILE_GOODS_IMAGE_FILE_URL_" => "/Uploads/goods_images", //商品图片的存放路径
    "_FILE_QUESTION_IMAGE_FILE_URL_" => "/Uploads/question_images", //问题图片的存放路径

    /* 营业时间 */
    "SHOP_WEEK_LIST" => [ //周六、周日
        0,6
    ],

    /* 工作时间列表 */
    "SHOP_WORK_LIST" => [
        [
            "start_time" => 0, //晚上0点
            "end_time" => 28800, //早上8点
            "tip" => "下班",
            "is_close" => 1,
        ],[
            "start_time" => 28800, //早8点
            "end_time" => 37800, //早10点半
            "tip" => "确认订单", //提示文字
            "is_confirm" => 1, //确认标记
        ],[
            "start_time" => 37800, //早10点半
            "end_time" => 39600, //早11点
            "tip" => "准备发货",
        ],[
            "start_time" => 39600, //早11点
            "end_time" => 46800, //下午1点
            "tip" => "配送商品",
            "is_send" => 1, //配送标记
        ],[
            "start_time" => 46800, //下午1点
            "end_time" => 52200, //下午2点半
            "tip" => "确认订单",
            "is_confirm" => 1, //确认标记
        ],[
            "start_time" => 52200, //下午2点半
            "end_time" => 54000, //下午3点
            "tip" => "准备发货",
        ],[
            "start_time" => 54000, //下午3点
            "end_time" => 59400, //下午4点半
            "tip" => "配送商品",
            "is_send" => 1, //配送标记
        ],[
            "start_time" => 59400, //下午4点半
            "end_time" => 61200, //下午5点
            "tip" => "确认订单",
            "is_confirm" => 1, //确认标记
        ],[
            "start_time" => 61200, //下午5点
            "end_time" => 63000, //下午5点半
            "tip" => "准备发货",
        ],[
            "start_time" => 63000, //下午5点半
            "end_time" => 68400, //晚上7点
            "tip" => "配送商品",
            "is_send" => 1, //配送标记
        ],[
            "start_time" => 68400, //晚上7点
            "end_time" => 73800, //晚上8点半
            "tip" => "确认订单",
            "is_confirm" => 1, //确认标记
        ],[
            "start_time" => 73800, //晚上8点半
            "end_time" => 75600, //晚上9点
            "tip" => "准备发货",
        ],[
            "start_time" => 75600, //晚上9点
            "end_time" => 79200, //晚上10点
            "tip" => "配送商品",
            "is_send" => 1, //配送标记
        ],[
            "start_time" => 79200, //晚上10点
            "end_time" => 86399, //晚上23:59:59
            "tip" => "下班",
            "is_close" => 1,
        ],
    ],

    /* 星期转换 */
    "WEEK_STR_LIST" => [
        "1" => "星期一",
        "2" => "星期二",
        "3" => "星期三",
        "4" => "星期四",
        "5" => "星期五",
        "6" => "星期六",
        "7" => "星期天",
    ],

    /* 送货时间段 */
    "SHOP_SEND_TIME_LIST" => [
        "lunch" => [
            "info" => "中餐时间",
            "time" => "11:00~13:00",
            "start_time" => 39600, //早11点
            "end_time" => 46800, //下午1点
        ],
        "afternoon" => [
            "info" => "零食时间",
            "time" => "15:00~16:30",
            "start_time" => 54000, //下午3点
            "end_time" => 59400, //下午4点半
        ],
        "dinner" => [
            "info" => "晚餐时间",
            "time" => "17:30~19:00",
            "start_time" => 63000, //下午5点半
            "end_time" => 68400, //晚上7点
        ],
        "night" => [
            "info" => "夜宵时间",
            "time" => "21:00~22:00",
            "start_time" => 75600, //晚上9点
            "end_time" => 79200, //晚上10点
        ],
    ],

    /* 送货星期 */
    "SHOP_SEND_WEEK_LIST" => [
        "6" => [
            "week_str" => "星期六",
        ],
        "7" => [
            "week_str" => "星期天",
        ],
    ],

    /* 业务用手机号 */
    "WEB_USE_MOBILE" => 18888888888,
    /* 业务用QQ号 */
    "WEB_USE_QQ" => 3219939283,
    /* 业务用支付宝账号 */
    "WEB_USE_ALIPAY" => '1163025244@qq.com',

    /* 数据表相关 */
    //普通表
    "TABLE_NAME_ATTR"                   => "attr", //属性表
    "TABLE_NAME_CART"                   => "cart", //用户清单表（购物车、收藏）
    "TABLE_NAME_GOODS"                  => "goods", //商品表
    "TABLE_NAME_GOODS_STOCK"            => "goods_stock", //商品库存信息表
    "TABLE_NAME_GOODS_TAG_RELATE"       => "goods_tag_relate", //商品标签关联表
    "TABLE_NAME_NOTICE"                 => "notice", //公告表
    "TABLE_NAME_ORDER"                  => "order", //订单表
    "TABLE_NAME_ORDER_GOODS"            => "order_goods", //订单关联商品表
    "TABLE_NAME_ORDER_LOG"              => "order_log", //订单日志表
    "TABLE_NAME_PARAM"                  => "param", //全站基础参数配置表
    "TABLE_NAME_SEARCH_KEYWORD"         => "search_keyword", //搜索关键词记录表
    "TABLE_NAME_STATISTICS_ATTR"        => "statistics_attr", //商品属性统计表
    "TABLE_NAME_STATISTICS_SALE"        => "statistics_sale", //商品销量统计表
    "TABLE_NAME_STATISTICS_TAG"         => "statistics_tag", //商品标签统计表
    "TABLE_NAME_TAGS"                   => "tags", //标签表
    "TABLE_NAME_USER"                   => "user", //用户表
    "TABLE_NAME_USER_FEEDBACK"          => "user_feedback", //用户信息反馈记录表
    "TABLE_NAME_USER_MESSAGE"           => "user_message", //用户消息表
    "TABLE_NAME_USER_POINTS"            => "user_points", //用户积分表
    "TABLE_NAME_USER_POINTS_LOG"        => "user_points_log", //用户积分日志表
    "TABLE_NAME_USER_RECEIPT_ADDRESS"   => "user_receipt_address", //用户收货地址信息表

    //活动类表
    //====== 每日问答 ======
    "TABLE_NAME_ACTIVITY"                               =>  'activity', //活动信息表
    "TABLE_NAME_ACTIVITY_QUESTION_BANK"                 =>  'activity_question_bank', //活动表-每日问答-题库表
    "TABLE_NAME_ACTIVITY_QUESTION_HISTORY_STATISTICS"   =>  'activity_question_history_statistics', //活动表-每日问答-题库表
    "TABLE_NAME_ACTIVITY_QUESTION_USER_ANSWER"          =>  'activity_question_user_answer', //活动表-每日问答-题库表

    //表相关状态
    //====== 属性表状态 ======
    "STATE_ATTR_DELETE" => 0, //属性表的删除状态
    "STATE_ATTR_NORMAL" => 1, //属性表的正常状态
    "STATE_ATTR_STATE_LIST" => [ //属性表状态列表
        "0" => "删除",
        "1" => "正常",
    ],

    //====== 商品表状态 ======
    "STATE_GOODS_LOCK" => 0, //商品表的锁定状态
    "STATE_GOODS_NORMAL" => 1, //商品表的正常状态
    "STATE_GOODS_DELETE" => 2, //商品表的删除状态
    "STATE_GOODS_STATE_LIST" => [ //商品表状态列表
        "0" => "锁定",
        "1" => "正常",
        "2" => "删除",
    ],

    "STATE_GOODS_UNSHELVE" => 0, //商品表的下架状态
    "STATE_GOODS_SHELVE" => 1, //商品表的上架状态
    "STATE_GOODS_IS_SHOP_LIST" => [ //商品表商品上下架状态列表
        "0" => "未上架", //下架
        "1" => "正常", //上架
    ],

    //====== 标签表状态 ======
    "STATE_TAGS_DELETE" => 0, //标签表的删除状态
    "STATE_TAGS_NORMAL" => 1, //标签表的正常状态
    "STATE_TAGS_STATE_LIST" => [ //标签表状态列表
        "0" => "删除",
        "1" => "正常",
    ],

    //====== 用户表状态 ======
    "STATE_USER_FREEZE" => 0, //用户表的冻结状态
    "STATE_USER_NORMAL" => 1, //用户表的正常状态
    "STATE_USER_DELETE" => 2, //用户表的删除状态
    "STATE_USER_STATE_LIST" => [ //用户表状态列表
        "0" => "冻结",
        "1" => "正常",
        "2" => "删除",
    ],

    "IDENTITY_USER_USERS" => 0, //用户表的普通用户身份
    "IDENTITY_USER_ADMIN" => 1, //用户表的管理员用户身份
    "IDENTITY_USER_STATE_LIST" => [ //用户表身份列表
        "0" => "用户",
        "1" => "管理员",
    ],

    //====== 用户信息反馈记录表反馈类型 ======
    "FEEDBACK_TYPE_ANSWER" => 1, //问题反馈
    "FEEDBACK_TYPE_ORDER_DISSENT" => 2, //订单异议
    "FEEDBACK_TYPE_SUGGEST" => 3, //意见与建议
    "FEEDBACK_TYPE_LIST" => [ //反馈类型列表
        "1" => "问题反馈",
        "2" => "订单异议",
        "3" => "意见与建议",
    ],

    //====== 清单表状态 ======
    "PAY_TYPE_CART_MONEY" => 1, //现金支付
    "PAY_TYPE_CART_POINT" => 2, //积分支付
    "PAY_TYPE_CART_LIST" => [ //清单表支付方式
        "1" => "现金支付",
        "2" => "积分支付",
    ],

    //====== 订单表状态 ======
    "STATE_ORDER_WAIT_CONFIRM" => 1, //待确认
    "STATE_ORDER_WAIT_DELIVERY" => 2, //待发货
    "STATE_ORDER_DELIVERY_ING" => 3, //配送中
    "STATE_ORDER_WAIT_SETTLEMENT" => 4, //待结算
    "STATE_ORDER_SUCCESS" => 5, //已完成
    "STATE_ORDER_CLOSE" => 6, //已关闭
    "STATE_ORDER_DISSENT" => 7, //有异议
    "STATE_ORDER_BACK" => 8, //已退款
    "STATE_ORDER_LIST" => [ //订单表状态列表
        "1" => "待确认",
        "2" => "待发货",
        "3" => "配送中",
        "4" => "待结算",
        "5" => "已完成",
        "6" => "已关闭",
        "7" => "有异议",
        "8" => "已退款",
    ],

    //====== 活动表-每日问答-题库表状态 ======
    "STATE_ACTIVITY_QUESTION_BANK_WAIT" => 0, //题库表的待发布状态
    "STATE_ACTIVITY_QUESTION_BANK_NORMAL" => 1, //题库表的正常状态
    "STATE_ACTIVITY_QUESTION_BANK_DELETE" => 2, //题库表的删除状态
    "STATE_ACTIVITY_QUESTION_BANK_STATE_LIST" => [ //题库表的状态列表
        "0" => "待审核",
        "1" => "正常",
        "2" => "删除",
    ],


    /* 活动相关 */
    //====== 活动 - 积分相关 ======
    //积分变更相关信息列表
    "ACTIVITY_POINT_LIST" => [
        //积分增加类
        "new_user_register" => [ //新用户注册
            "point" => "3", //涉及积分
            "log" => "新用户注册", //简短日志
            "remark" => "欢迎新用户，注册即送3点积分", //详细说明
        ],
        "new_user_register_activity" => [ //新用户注册活动
            "start_time" => '2016-05-06',
            "end_time" => '2017-01-01',
            "point" => "2",
            "log" => "新用户注册活动",
            "remark" => "活动期间再送2点",
        ],
        "answer_question_every_day_activity" => [ //每日答题活动
            "point" => "1",
            "log" => "每日答题活动",
            "remark" => "完成今日答题，获得1点积分",
        ],
        "order_cancel" => [ //取消订单
            "point" => "~", //自定义
            "log" => "取消订单",
            "remark" => "取消订单",
        ],

        //积分减少类
        "goods_consume" => [ //商品消费
            "point" => "~", //自定义
            "log" => "商品消费",
            "remark" => "商品消费",
        ],

        //积分变更类
        "admin_update_point" => [ //管理员变更积分
            "point" => "~", //自定义
            "log" => "系统操作",
            "remark" => "系统操作",
        ],
    ],

    //====== 活动 - 每日问答 ======
    //问题标签
    "ACTIVITY_QUESTION_TAB_LIST" => [
        "common" => "普通",
    ],
    //发布问题天数偏移值
    "ACTIVITY_QUESTION_PUBLISH_DEVIATION_DAY" => 7,
    //发布问题对应于偏移值的倍数列表
    "ACTIVITY_QUESTION_PUBLISH_MULTIPLE_LIST" => [
        "0"=>1,
        "4"=>2,
        "8"=>3,
        "10"=>5,
    ],
    //发布问题几率极限值
    "ACTIVITY_QUESTION_PUBLISH_MAX_VAL" => 80,


);