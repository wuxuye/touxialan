<?php
namespace Yege;

/**
 * 商品类
 *
 * 方法提供
 * addGoods                 商品添加
 * addGoodsDo（私有）       商品添加执行逻辑（此方法会改变 goods_id 的值）
 * editGoods                商品编辑方法
 * editGoodsDo              商品编辑执行逻辑
 * checkParam               参数检验（此方法可能会改变 goods_id、goods_belong_id、goods_name、goods_ext_name、goods_price与goods_describe的值）
 * getGoodsInfo             商品详情获取
 * shelveGoods              商品上架
 * unshelveGoods            商品下架
 * deleteGoods              删除商品
 * getGoodsStock            获取商品库存信息
 * updateGoodsStock         修改库存信息
 * statisticsSale           商品销量统计
 * getStatisticsData        获取统计数据
 * getStatisticsDataList    获取统计数据详情列表
 */

class Goods{

    //提供于外面赋值或读取的相关参数
    public $goods_id = 0; //商品id
    public $goods_belong_id = 0; //属归id
    public $goods_name = ""; //商品名称
    public $goods_ext_name = ""; //商品扩展名
    public $goods_attr_id = 0; //商品属性id
    public $goods_price = 0; //商品单价
    public $goods_point = 0; //商品所需积分
    public $goods_can_price = 0; //允许price结算
    public $goods_can_point = 0; //允许point结算
    public $goods_describe = ""; //商品描述
    public $goods_is_recommend = 0; //商品是否被推荐
    public $goods_weight = 0; //商品权重
    public $goods_image = ""; //商品图片

    private $goods_table = ""; //相关商品表
    private $goods_stock_table = ""; //商品库存表
    private $goods_stock_log_table = ""; //商品库存记录表
    private $order_table = ""; //订单表
    private $order_goods_table = ""; //订单商品表
    private $statistics_sale_table = ''; //商品总销量统计表
    private $statistics_sale_day_table = ''; //商品日销量统计表
    private $user_table = ""; //相关用户表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->goods_table = C("TABLE_NAME_GOODS");
        $this->goods_stock_table = C("TABLE_NAME_GOODS_STOCK");
        $this->goods_stock_log_table = C("TABLE_NAME_GOODS_STOCK_LOG");
        $this->order_table = C("TABLE_NAME_ORDER");
        $this->order_goods_table = C("TABLE_NAME_ORDER_GOODS");
        $this->statistics_sale_table = C("TABLE_NAME_STATISTICS_SALE");
        $this->statistics_sale_day_table = C("TABLE_NAME_STATISTICS_SALE_DAY");
        $this->user_table = C("TABLE_NAME_USER");
    }

    /**
     * 商品添加方法
     * @return array $result 结果返回
     */
    public function addGoods(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //对各种参数进行检验
        $check_list = [ //待检测的参数列表
            "goods_name","goods_ext_name","goods_attr_id","goods_price",
            "goods_point", "goods_can_price","goods_can_point","goods_describe",
            "goods_belong_id","goods_image","goods_weight",
        ];
        $wrong = 0;
        foreach($check_list as $param){
            $checkout_result = [];
            $checkout_result = $this->checkParam($param);
            if($checkout_result['state'] != 1){
                $result['message'] = $checkout_result['message'];
                $wrong = 1;
                break;
            }
        }

        //没有错误就开始添加逻辑
        if($wrong == 0){
            $add_result = [];
            $add_result = $this->addGoodsDo();
            if($add_result['state'] == 1){
                $result['state'] = 1;
                $result['message'] = "商品添加成功";
            }else{
                $result['message'] = $add_result['message'];
            }
        }

        return $result;
    }

    /**
     * 商品添加执行逻辑
     * @return array $result 结果返回
     *
     * 此方法会改变 goods_id 的值
     *
     */
    public function addGoodsDo(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //重复性检查 (同个归属人 正常商品中 同商品名 与 扩展名)
        $goods = $where = array();
        $where['name'] = $this->goods_name;
        $where['ext_name'] = $this->goods_ext_name;
        $where['belong_id'] = $this->goods_belong_id;
        $where['state'] = C("STATE_GOODS_NORMAL");
        $goods = M($this->goods_table)->where($where)->find();
        if(empty($goods)){
            //基础参数组装
            $add = array();
            $add['belong_id'] = $this->goods_belong_id;
            $add['name'] = $this->goods_name;
            $add['ext_name'] = $this->goods_ext_name;
            $add['attr_id'] = $this->goods_attr_id;
            $add['price'] = $this->goods_price;
            $add['point'] = $this->goods_point;
            $add['can_price'] = $this->goods_can_price;
            $add['can_point'] = $this->goods_can_point;
            $add['describe'] = $this->goods_describe;
            $add['is_recommend'] = empty($this->goods_is_recommend)?0:1;
            $add['weight'] = $this->goods_weight;
            $add['goods_image'] = $this->goods_image;
            $add['inputtime'] = $add['updatetime'] = time();
            $this->goods_id = M($this->goods_table)->add($add);
            if(!empty($this->goods_id)){
                $result['state'] = 1;
                $result['message'] = "添加成功";
            }else{
                $result['message'] = "添加数据失败";
            }
        }else{
            $result['message'] = "商品已存在";
        }

        return $result;
    }

    /**
     * 商品编辑方法
     * @result array $result 结果返回
     */
    public function editGoods(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //对各种参数进行检验
        $check_list = array( //待检测的参数列表
            "goods_name","goods_ext_name","goods_attr_id","goods_price",
            "goods_point", "goods_can_price","goods_can_point",
            "goods_describe","goods_belong_id","goods_image","goods_weight",
        );
        $wrong = 0;
        foreach($check_list as $param){
            $checkout_result = array();
            $checkout_result = $this->checkParam($param);
            if($checkout_result['state'] != 1){
                $result['message'] = $checkout_result['message'];
                $wrong = 1;
                break;
            }
        }
        //没有错误就开始添加逻辑
        if($wrong == 0){
            $edit_result = array();
            $edit_result = $this->editGoodsDo();
            if($edit_result['state'] == 1){
                $result['state'] = 1;
                $result['message'] = "商品编辑成功";
            }else{
                $result['message'] = $edit_result['message'];
            }
        }

        return $result;
    }

    /**
     * 商品编辑执行逻辑
     * @return array $result 结果返回
     */
    public function editGoodsDo(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //重复性检查 (同个归属人 正常商品中 同商品名 与 扩展名)
        $goods = $where = array();
        $where['name'] = $this->goods_name;
        $where['ext_name'] = $this->goods_ext_name;
        $where['id'] = array("neq",$this->goods_id);
        $where['belong_id'] = $this->goods_belong_id;
        $where['state'] = C("STATE_GOODS_NORMAL");
        $goods = M($this->goods_table)->where($where)->find();

        if(empty($goods)){

            $where = array();
            $where['id'] = $this->goods_id;

            //基础参数组装
            $edit = array();
            $edit['belong_id'] = $this->goods_belong_id;
            $edit['name'] = $this->goods_name;
            $edit['ext_name'] = $this->goods_ext_name;
            $edit['attr_id'] = $this->goods_attr_id;
            $edit['price'] = $this->goods_price;
            $edit['point'] = $this->goods_point;
            $edit['can_price'] = $this->goods_can_price;
            $edit['can_point'] = $this->goods_can_point;
            $edit['describe'] = $this->goods_describe;
            $edit['is_shop'] = C("STATE_GOODS_UNSHELVE"); //更新商品会让商品变为下架状态
            $edit['is_recommend'] = empty($this->goods_is_recommend)?0:1;
            $edit['weight'] = $this->goods_weight;
            if(!empty($this->goods_image)){
                $edit['goods_image'] = $this->goods_image;
            }
            $edit['updatetime'] = time();
            if(M($this->goods_table)->where($where)->save($edit)){
                $result['state'] = 1;
                $result['message'] = "编辑成功";
            }else{
                $result['message'] = "编辑数据失败";
            }
        }else{
            $result['message'] = "同名商品已存在";
        }

        return $result;
    }

    /**
     * 商品参数检验（顺带过滤）
     * @param string $str 参数标示
     * @return array $result 结果返回
     *
     * 此方法可能会改变 goods_id、goods_belong_id等公共属性的值
     *
     */
    public function checkParam($str = ""){
        $result = ['state'=>0,'message'=>'未知错误'];

        //对各种参数进行检验
        switch($str){
            case 'goods_id': //商品id检查
                $param = check_int($this->goods_id);
                if($param <= 0){
                    $result['message'] = "商品id错误";
                }else{
                    $this->goods_id = $param;
                    $result['state'] = 1;
                }
                break;
            case 'goods_belong_id': //商品所属检查
                $param = check_int($this->goods_belong_id);
                //没有商品所属，商品将归属于默认管理员
                if($param <= 0){
                    $param = C("ADMIN_DEFAULT_USER_ID");
                }
                //用户的存在检验
                $user_info = $where = array();
                $where['id'] = $param;
                $user_info = M($this->user_table)->where($where)->find();
                if(!empty($user_info)){
                    $this->goods_belong_id = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "指定的所属用户不存在";
                }
                break;
            case 'goods_name': //商品名称检查
                $param = check_str($this->goods_name);
                if (!empty($param)){
                    $this->goods_name = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "商品名称不能为空";
                }
                break;
            case 'goods_ext_name': //商品扩展名称检查
                $param = check_str($this->goods_ext_name);
                if (!empty($param)){
                    $this->goods_ext_name = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "商品扩展名不能为空";
                }
                break;
            case 'goods_attr_id': //商品属性id检查
                $param = check_int($this->goods_attr_id);
                if (!empty($param) && $param > 0){
                    $this->goods_attr_id = $param;
                }else{
                    $this->goods_attr_id = 0;
                }
                //直接返回 可以为空
                $result['state'] = 1;
                break;
            case 'goods_price': //商品价格检查
                //四舍五入 保留 2位小数
                $param = round($this->goods_price,2);
                if ($param <= 0){
                    $param = 0;
                }
                $this->goods_price = $param;
                $result['state'] = 1;
                break;
            case 'goods_point': //商品所需积分检查
                $param = check_int($this->goods_point);
                if ($param <= 0){
                    $param = 0;
                }
                $this->goods_point = $param;
                $result['state'] = 1;
                break;
            case 'goods_can_price': //商品是否可用price结算检查
                $param = check_int($this->goods_can_price);
                if ($param != 1){
                    $param = 0;
                }
                $result['state'] = 1;
                $this->goods_can_price = $param;
                break;
            case 'goods_can_point': //商品是否可用point结算检查
                $param = check_int($this->goods_can_point);
                if ($param != 1){
                    $param = 0;
                }
                $result['state'] = 1;
                $this->goods_can_point = $param;
                break;
            case 'goods_describe': //商品描述检查
                $param = check_str($this->goods_describe);
                if (!empty($param)){
                    $this->goods_describe = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "商品描述不能为空";
                }
                break;
            case 'goods_image': //商品图片检查
                $param = check_str($this->goods_image);
                if (!empty($param)){
                    $this->goods_image = $param;
                }else{
                    $this->goods_image = "";
                }
                //直接返回 可以为空
                $result['state'] = 1;
                break;
            case 'goods_weight': //商品权重检查
                $param = check_int($this->goods_weight);
                if (!empty($param) && $this->goods_weight>0){
                    $this->goods_weight = $param;
                }else{
                    $this->goods_weight = 0;
                }
                //直接返回 可以为空
                $result['state'] = 1;
                break;
            default : //未定义的检查类型
                $result['message'] = "未定义的检查类型";
                break;

        }

        if($result['state'] == 1){
            $result['message'] = "验证成功";
        }

        return $result;
    }

    /**
     * 商品详情获取
     * @return array $result 商品结果集返回
     */
    public function getGoodsInfo(){
        $result = ['state'=>0,'message'=>'未知错误'];

        $check_result = array();
        $check_result = $this->checkParam("goods_id");
        if($check_result['state'] == 1){
            $goods_id = $this->goods_id;
            $goods_info = $where = array();
            $where['id'] = $this->goods_id;
            $goods_info = M($this->goods_table)->where($where)->find();
            if(!empty($goods_info['id'])){
                //重组数据返回
                $result['state'] = 1;
                $result['result'] = $goods_info;
                $result['message'] = "获取成功";
            }else{
                $result['message'] = "未能获得商品信息";
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 商品上架
     * @return array $result 结果返回
     */
    public function shelveGoods(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //先尝试获取详情
        $info = array();
        $info = $this->getGoodsInfo();
        if($info['state'] == 1){
            $goods_result = $info['result'];
            if(!empty($goods_result['id'])){

                //上架检查
                if(empty($goods_result['name'])){
                    $result['message'] = "商品名 不能为空";
                    return $result;
                }
                if(empty($goods_result['can_price']) && empty($goods_result['can_point'])){
                    $result['message'] = "必须要有一种 结算状态";
                    return $result;
                }
                if(!empty($goods_result['can_price']) && empty($goods_result['price'])){
                    $result['message'] = "商品 结算金额 不能为空";
                    return $result;
                }
                if(!empty($goods_result['can_point']) && empty($goods_result['point'])){
                    $result['message'] = "商品 结算积分 不能为空";
                    return $result;
                }

                $where = $save = array();
                $where['id'] = $this->goods_id;
                $save['is_shop'] = C("STATE_GOODS_SHELVE");
                $save['shelve_time'] = $save['updatetime'] = time();
                if(M($this->goods_table)->where($where)->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "上架成功";
                }else{
                    $result['message'] = "上架失败";
                }
            }else{
                $result['message'] = "获取 商品信息 失败";
            }
        }else{
            $result['message'] = $info['message'];
        }

        return $result;
    }

    /**
     * 商品下架
     * @return array $result 结果返回
     */
    public function unshelveGoods(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //先尝试获取详情
        $info = array();
        $info = $this->getGoodsInfo();
        if($info['state'] == 1){
            $goods_result = $info['result'];
            if(!empty($goods_result['id'])){
                $where = $save = array();
                $where['id'] = $this->goods_id;
                $save['is_shop'] = C("STATE_GOODS_UNSHELVE");
                $save['updatetime'] = time();
                if(M($this->goods_table)->where($where)->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "下架成功";
                }else{
                    $result['message'] = "下架失败";
                }
            }else{
                $result['message'] = "获取商品信息失败";
            }
        }else{
            $result['message'] = $info['message'];
        }

        return $result;
    }

    /**
     * 删除商品
     * @return array $result 结果返回
     */
    public function deleteGoods(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //先尝试获取详情
        $info = array();
        $info = $this->getGoodsInfo();
        if($info['state'] == 1){
            $goods_result = $info['result'];
            if(!empty($goods_result['id'])){
                $where = $save = array();
                $where['id'] = $this->goods_id;
                $save['state'] = C("STATE_GOODS_DELETE");
                $save['updatetime'] = time();
                if(M($this->goods_table)->where($where)->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "删除成功";
                }else{
                    $result['message'] = "删除失败";
                }
            }else{
                $result['message'] = "获取商品信息失败";
            }
        }else{
            $result['message'] = $info['message'];
        }

        return $result;
    }

    /**
     * 获取商品库存信息
     * @return array $result 结果返回
     */
    public function getGoodsStock(){
        $result = ['state'=>0,'message'=>'未知错误','stock_num'=>0,'stock_unit'=>''];

        $check_result = [];
        $check_result = $this->checkParam('goods_id');

        if($check_result['state'] == 1){
            $data = $where = [];
            $where['goods_id'] = $this->goods_id;
            $data = M($this->goods_stock_table)->where($where)->find();
            if(!empty($data['id'])){
                $result['state'] = 1;
                $result['message'] = '获取成功';
                $result['stock_num'] = $data['stock'];
                $result['stock_unit'] = $data['stock_unit'];
            }else{
                $update_result = [];
                //生成库存数据
                $update_result = $this->updateGoodsStock();
                if($update_result['state'] == 1){
                    $result['state'] = 1;
                    $result['message'] = '获取成功';
                    $result['stock_num'] = $update_result['result_stock'];
                    $result['stock_unit'] = $update_result['result_unit'];
                }else{
                    $result['message'] = $update_result['message'];
                }
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }

    /**
     * 修改库存信息
     * @param int $type 修改方式 (1、减少 2、增加)
     * @param int $num 库存数量
     * @param string $unit 单位
     * @return array $result 结果返回 (附带当前库存信息)
     */
    public function updateGoodsStock($type = 1,$num = 0,$unit = ""){
        $result = ['state'=>0,'message'=>'未知错误','is_lock'=>0,'low_stock'=>0,'result_stock'=>0,'result_unit'=>''];

        $check_result = [];
        $check_result = $this->checkParam('goods_id');

        if($check_result['state'] == 1){
            $data = $where = [];
            $where['goods_id'] = $this->goods_id;
            $data = M($this->goods_stock_table)->where($where)->find();
            //没有库存数据就生成一条新的记录
            if(empty($data['id'])){
                $add = [];
                $add['goods_id'] = $this->goods_id;
                $add['stock_unit'] = check_str($unit);
                $add['inputtime'] = $add['updatetime'] = time();
                if(M($this->goods_stock_table)->add($add)){
                    //新数据添加成功就继续逻辑
                    $result['result_stock'] = 0; //记下最终库存结果
                    $result['result_unit'] = '';
                }else{
                    $result['message'] = "添加数据失败";
                    return $result;
                }
            }else{
                $result['result_unit'] = $data['stock_unit'];
            }

            //开始数据改变逻辑
            $type = check_int($type);
            $num = check_int($num);
            $unit = check_str($unit);
            if(!empty($num) || !empty($unit)){
                //首先查看文件锁
                if(check_file_lock("goods_".$this->goods_id.".lock","goods")){
                    $result['message'] = "商品正在锁定状态，无法处理";
                    $result['is_lock'] = 1;
                    return $result;
                }
                //加锁
                set_file_lock("goods_".$this->goods_id.".lock","goods");

                //再次获取数据
                $stock_info = $where = [];
                $where['goods_id'] = $this->goods_id;
                $stock_info = M($this->goods_stock_table)->where($where)->find();
                //数量获取
                $stock_num = check_int($stock_info['stock']);

                //数据处理
                $save = $where = [];
                $where['goods_id'] = $this->goods_id;
                if(!empty($unit)){
                    $save['stock_unit'] = $unit;
                }
                switch($type){
                    case 1:
                        if(check_int($stock_num-$num) < 0){
                            delete_file_lock("goods_".$this->goods_id.".lock","goods");
                            $result['message'] = "库存不足，修改失败";
                            $result['low_stock'] = 1;
                            return $result;
                        }
                        $save['stock'] = ['exp','stock-'.$num];
                        break;
                    case 2:
                        $save['stock'] = ['exp','stock+'.$num];
                        break;
                    default :
                        delete_file_lock("goods_".$this->goods_id.".lock","goods");
                        $result['message'] = "变更类型错误";
                        return $result;
                        break;
                }
                $save['updatetime'] = time();

                if(M($this->goods_stock_table)->where($where)->save($save)){

                    //再次获取数据
                    $stock_info = $where = [];
                    $where['goods_id'] = $this->goods_id;
                    $stock_info = M($this->goods_stock_table)->where($where)->find();

                    //库存记录
                    $add = [
                        "goods_id" => $this->goods_id,
                        "change_stock" => ($type==1?(-$num):($type==2?$num:0)),
                        "result_stock" => ($type==1?($stock_num-$num):($type==2?($stock_num+$num):0)),
                        "real_stock" => $stock_info['stock'],
                        "inputtime" => time(),
                    ];
                    M($this->goods_stock_log_table)->add($add);

                    //add_operation_log("商品id为：".$this->goods_id."的商品，修改库存 数量 ".($type==1?"-":($type==2?"+":""))." ".$num." 、单位 ".$unit." 成功。\r\n当前应该剩余库存：".($type==1?($stock_num-$num):($type==2?($stock_num+$num):"未知"))." 实际剩余库存：".$stock_info['stock'],C("GOODS_STOCK_CHANGE_FOLDER_NAME"));

                    $result['state'] = 1;
                    $result['result_stock'] = $stock_info['stock'];
                    $result['result_unit'] = $stock_info['stock_unit'];
                    $result['message'] = "修改库存成功";
                }else{
                    $result['message'] = "修改库存失败";
                }

                //解锁文件
                delete_file_lock("goods_".$this->goods_id.".lock","goods");
            }else{
                //没有数量变化直接返回
                $result['state'] = 1;
                $result['message'] = "无数量变化";
            }
        }else{
            $result['message'] = $check_result['message'];
        }

        return $result;
    }


    /**
     * 获取商品库存记录列表
     * @param array $where 搜索条件
     * @param int $page 页码
     * @param int $num 单页数量
     * @return array $list 结果返回
     */
    public function getGoodsStockLogList($where = [],$page = 1,$num = 20){
        $result = [
            "list" => [],
            "count" => 0,
        ];

        //基础参数

        $limit = ($page-1)*$num.",".$num;

        $stock_log_list = M($this->goods_stock_log_table." as stock_log")
            ->field([
                "stock_log.id","stock_log.change_stock","stock_log.result_stock",
                "stock_log.real_stock","stock_log.inputtime","goods.name","goods.ext_name",
            ])
            ->join("left join ".C("DB_PREFIX").$this->goods_table." as goods on goods.id = stock_log.goods_id")
            ->where($where)
            ->limit($limit)
            ->order("stock_log.id DESC")
            ->select();

        $result['list'] = $stock_log_list;

        //数量获取
        $count = M($this->goods_stock_log_table." as stock_log")
            ->join("left join ".C("DB_PREFIX").$this->goods_table." as goods on goods.id = stock_log.goods_id")
            ->where($where)
            ->count();
        $result['count'] = empty($count) ? 0 : $count;

        return $result;
    }

    /**
     * 商品销量统计
     * @return int $goods_id 商品id
     * @return array $result 结果返回
     */
    public function statisticsSale(){
        $result = ["state"=>0,"message"=>"未知错误"];

        //首先拿到本月 与 上个月的 成功订单
        $start_time = strtotime(date("Y-m-01 00:00:00",strtotime("-1 month"))); //上个月开始时间
        $end_time = strtotime(date("Y-m-01 00:00:00",strtotime("+1 month"))) - 1; //本月结束时间

        //首先拿到这个商品的全部已完成订单
        $order_list = M($this->order_goods_table." as order_goods")
            ->field([
                "order_goods.goods_id","order_goods.goods_num","order_goods.pay_type",
                "order_goods.price","orders.user_id","orders.inputtime"
            ])
            ->join("left join ".C("DB_PREFIX").$this->order_table." as orders on orders.id = order_goods.order_id")
            ->where([
                "orders.inputtime" => [
                    ["egt",$start_time],
                    ["elt",$end_time],
                ],
                "orders.state" => C("STATE_ORDER_SUCCESS"),
                "orders.is_delete" => 0,
            ])->select();

        M()->startTrans();

        //开始循环处理数据
        $statistics_data = [];
        //天数循环
        for($day = $start_time;$day < $end_time;$day += 24*60*60){
            $statistics_data[$day] = [];
            $user_list = [];
            foreach($order_list as $info){
                if(empty($statistics_data[$day][$info['goods_id']])){
                    $statistics_data[$day][$info['goods_id']] = [
                        "sale_num" => 0,
                        "sale_price" => 0,
                        "sale_user" => 0,
                    ];
                }

                if($info['inputtime'] > $day && $info['inputtime'] < ($day+24*60*60)){
                    $statistics_data[$day][$info['goods_id']]['sale_num'] += $info['goods_num'];
                    if($info['pay_type'] == C("PAY_TYPE_CART_MONEY")){
                        $statistics_data[$day][$info['goods_id']]['sale_price'] += ($info['price'] * $info['goods_num']);
                    }
                    $user_list[$info['goods_id']][$info['user_id']] = 1;
                }
            }
            //最后统计用户
            foreach($user_list as $key => $val){
                $statistics_data[$day][$key]['sale_user'] = count($val);
            }
        }

        //开始每日统计
        if(!empty($statistics_data)){
            foreach($statistics_data as $key => $val){
                foreach($val as $goods_key => $goods_val){
                    $statistics_id = 0;
                    $statistics_info = M($this->statistics_sale_day_table)
                        ->where(["goods_id"=>$goods_key,"record_time"=>$key])
                        ->find();
                    if(empty($statistics_info['id'])){
                        //数据添加
                        $add = [
                            "goods_id" => $goods_key,
                            "record_time" => $key,
                        ];
                        $statistics_id = M($this->statistics_sale_day_table)->add($add);
                    }else{
                        $statistics_id = $statistics_info['id'];
                    }
                    if(!empty($statistics_id)){
                        if(!((isset($statistics_info['sale_num']) && $statistics_info['sale_num'] == $goods_val['sale_num']) ||
                            (isset($statistics_info['sale_price']) && $statistics_info['sale_price'] == $goods_val['sale_price']) ||
                            (isset($statistics_info['sale_user']) && $statistics_info['sale_user'] == $goods_val['sale_user']))){
                            $save = [
                                "sale_num" => $goods_val['sale_num'],
                                "sale_price" => $goods_val['sale_price'],
                                "sale_user" => $goods_val['sale_user'],
                            ];
                            M($this->statistics_sale_day_table)->where(['id'=>$statistics_id])->save($save);
                        }
                    }
                }
            }
        }

        //开始总统计
        $goods_list = [];
        //拿到这段时间里的商品
        foreach($order_list as $info){
            $goods_list[$info['goods_id']] = 1;
        }

        foreach($goods_list as $key => $val){
            $goods_id = intval($key);
            if(!empty($goods_id)){
                $statistics_id = 0;
                $statistics_info = M($this->statistics_sale_table)->where(["goods_id"=>$goods_id])->find();
                if(!empty($statistics_info['id'])){
                    $statistics_id = $statistics_info['id'];
                }else{
                    //不存在就先添加
                    $add = [
                        "goods_id" => $goods_id,
                        "statistics_time" => time(),
                    ];
                    $statistics_id = M($this->statistics_sale_table)->add($add);
                }

                if(!empty($statistics_id)){
                    //首先拿到这个商品的全部已完成订单
                    $order_list = M($this->order_goods_table." as order_goods")
                        ->field([
                            "order_goods.goods_num","order_goods.pay_type",
                            "order_goods.price","orders.user_id",
                        ])
                        ->join("left join ".C("DB_PREFIX").$this->order_table." as orders on orders.id = order_goods.order_id")
                        ->where([
                            "order_goods.goods_id" => $goods_id,
                            "orders.state" => C("STATE_ORDER_SUCCESS"),
                            "orders.is_delete" => 0,
                        ])->select();

                    if(!empty($order_list)){
                        $sale_num = $sale_price = $sale_user = 0;
                        $user_list = [];
                        foreach($order_list as $info){
                            $sale_num += $info['goods_num'];
                            if($info['pay_type'] == C("PAY_TYPE_CART_MONEY")){
                                $sale_price += ($info['price'] * $info['goods_num']);
                            }
                            $user_list[$info['user_id']] = 1;
                        }
                        $sale_user = count($user_list);

                        //更新销量表
                        $save = [
                            "sale_num" => $sale_num,
                            "sale_price" => $sale_price,
                            "sale_user" => $sale_user,
                            "statistics_time" => time(),
                        ];
                        M($this->statistics_sale_table)->where(["goods_id"=>$goods_id])->save($save);

                    }

                }
            }
        }

        M()->commit();
        $result['state'] = 1;
        $result['message'] = '统计结束';

        return $result;
    }


    /**
     * 获取统计数据
     * @param int $level 搜索级别 1 年列表 、2 月列表 、3 日列表
     * @param string $time 时间搜索值 根据 $level 参数变化
     * @return array $result 统计结果返回
     */
    public function getStatisticsData($level = 1,$time = ""){
        $result = [];
        $result['level'] = $level;
        $result['statistics'] = [];
        switch($level){
            case 1: //年列表统计($time参数无效)
                //以2016年为起点的3年
                $result['statistics'] = [
                    "2016" => [
                        "sale_num" => 0,
                        "sale_price" => 0,
                    ],"2017" => [
                        "sale_num" => 0,
                        "sale_price" => 0,
                    ], "2018" => [
                        "sale_num" => 0,
                        "sale_price" => 0,
                    ],
                ];
                //获取统计表中的全部数据
                $temp = [];
                $temp = M($this->statistics_sale_day_table)
                    ->order("record_time ASC")
                    ->select();
                //开始统计
                foreach($temp as $val){
                    $temp_time = date("Y",$val['record_time']);
                    if(empty($result['statistics'][$temp_time])){
                        $result['statistics'][$temp_time] = [
                            "sale_num" => 0,
                            "sale_price" => 0,
                        ];
                    }
                    $result['statistics'][$temp_time]["sale_num"] += $val['sale_num'];
                    $result['statistics'][$temp_time]["sale_price"] += $val['sale_price'];
                }
                break;
            case 2: //月列表统计($time参数表示年份)
                //默认年份是今年
                if(empty($time) || !is_date($time."-01-01")){
                    $time = date("Y",time());
                }

                $result['year'] = $time;

                //填充月份
                for($i=1;$i<=12;$i++){
                    $month_str = $i < 10 ? "0".$i : $i;
                    $result['statistics']["month_".$month_str] = [
                        "sale_num" => 0,
                        "sale_price" => 0,
                        'month' => $month_str,
                    ];
                }

                $temp = $where = [];
                $where['record_time'][] = ['egt',strtotime($time."-01-01 00:00:00")];
                $where['record_time'][] = ['lt',strtotime(($time+1)."-01-01 00:00:00")];
                $temp = M($this->statistics_sale_day_table)
                    ->where($where)
                    ->order("record_time ASC")
                    ->select();
                //开始统计
                foreach($temp as $val){
                    $temp_time = "month_".date("m",$val['record_time']);
                    if(empty($result['statistics'][$temp_time])){
                        $result['statistics'][$temp_time] = [
                            "sale_num" => 0,
                            "sale_price" => 0,
                            'month' => date("m",$val['record_time'])
                        ];
                    }
                    $result['statistics'][$temp_time]["sale_num"] += $val['sale_num'];
                    $result['statistics'][$temp_time]["sale_price"] += $val['sale_price'];
                }

                break;
            case 3: //日列表统计($time参数表示月份)
                //默认月份是今年这个月
                if(empty($time) || !is_date($time."-01")){
                    $time = date("Y-m",time());
                }

                $result['year'] = date("Y",strtotime($time));
                $result['month'] = date("m",strtotime($time));

                //填充天数
                $day = date("t",strtotime($time."-01"));
                for($i=1;$i<=$day;$i++){
                    $day_str = $i < 10 ? "0".$i : $i;
                    $result['statistics']["day_".$day_str] = [
                        "sale_num" => 0,
                        "sale_price" => 0,
                        "day" => $day_str,
                    ];
                }

                $temp = $where = [];
                $where['record_time'][] = ['egt',strtotime($time."-01 00:00:00")];
                $where['record_time'][] = ['lt',(strtotime($time."-01 00:00:00")+($day*24*60*60))];
                $temp = M($this->statistics_sale_day_table)
                    ->where($where)
                    ->order("record_time ASC")
                    ->select();

                //开始统计
                foreach($temp as $val){
                    $temp_time = "day_".date("d",$val['record_time']);
                    if(empty($result['statistics'][$temp_time])){
                        $result['statistics'][$temp_time] = [
                            "sale_num" => 0,
                            "sale_price" => 0,
                            "day" => date("d",$val['record_time']),
                        ];
                    }

                    $result['statistics'][$temp_time]["sale_num"] += $val['sale_num'];
                    $result['statistics'][$temp_time]["sale_price"] += $val['sale_price'];
                }
                break;
        }

        ksort($result['statistics']);

        return $result;
    }

    /**
     * 获取统计数据详情列表
     * @param int $level 搜索级别 1 年列表 、2 月列表 、3 日列表
     * @param string $time 时间搜索值 根据 $level 参数变化
     * @return array $result 统计结果返回
     */
    public function getStatisticsDataList($level = 1,$time = ""){
        $result = [];
        $result['statistics'] = [];
        $result['level'] = $level;
        $result['time'] = "";
        switch($level){
            case 1: //年列表统计($time参数表年份)

                //默认年份是今年
                if(empty($time) || !is_date($time."-01-01")){
                    $time = date("Y",time());
                }

                $result['time'] = $time."年";

                $start_time = strtotime(date("Y-01-01 00:00:00",strtotime($time)));
                $end_time = strtotime(date("Y-01-01 00:00:00",strtotime("+1 year"))) - 1;

                $where = [];
                $where['sale.record_time'][] = ['egt',$start_time];
                $where['sale.record_time'][] = ['lt',$end_time];

                //获取统计表中的全部数据
                $temp = [];
                $temp = M($this->statistics_sale_day_table." as sale")
                    ->field(["sale.goods_id","sale.sale_num","sale.sale_price","sale.sale_user","goods.name","goods.ext_name"])
                    ->join("left join ".C("DB_PREFIX").$this->goods_table." as goods on goods.id = sale.goods_id")
                    ->where($where)
                    ->select();

                //开始统计
                foreach($temp as $val){
                    if(empty($result['statistics'][$val['goods_id']])){
                        $result['statistics'][$val['goods_id']] = [
                            "goods_id" => $val['goods_id'],
                            "goods_name" => $val['name'],
                            "goods_name_str" => cut_str($val['name'],10),
                            "goods_ext_name" => $val['ext_name'],
                            "goods_ext_name_str" => cut_str($val['ext_name'],10),
                            "sale_num" => 0,
                            "sale_price" => 0,
                            "sale_user" => 0,
                        ];
                    }
                    $result['statistics'][$val['goods_id']]["sale_num"] += $val['sale_num'];
                    $result['statistics'][$val['goods_id']]["sale_price"] += $val['sale_price'];
                    $result['statistics'][$val['goods_id']]["sale_user"] += $val['sale_user'];
                }
                break;
            case 2: //月列表统计($time参数表示月份)
                //默认月份是今年这个月
                if(empty($time) || !is_date($time."-01")){
                    $time = date("Y-m",time());
                }

                $result['time'] = date("Y年m月",strtotime($time));

                $start_time = strtotime(date("Y-m-01 00:00:00",strtotime($time)));
                $end_time = strtotime(date("Y-m-01 00:00:00",strtotime("+1 month"))) - 1;

                $where = [];
                $where['sale.record_time'][] = ['egt',$start_time];
                $where['sale.record_time'][] = ['lt',$end_time];

                //获取统计表中的全部数据
                $temp = [];
                $temp = M($this->statistics_sale_day_table." as sale")
                    ->field(["sale.goods_id","sale.sale_num","sale.sale_price","sale.sale_user","goods.name","goods.ext_name"])
                    ->join("left join ".C("DB_PREFIX").$this->goods_table." as goods on goods.id = sale.goods_id")
                    ->where($where)
                    ->select();

                //开始统计
                foreach($temp as $val){
                    if(empty($result['statistics'][$val['goods_id']])){
                        $result['statistics'][$val['goods_id']] = [
                            "goods_id" => $val['goods_id'],
                            "goods_name" => $val['name'],
                            "goods_name_str" => cut_str($val['name'],10),
                            "goods_ext_name" => $val['ext_name'],
                            "goods_ext_name_str" => cut_str($val['ext_name'],10),
                            "sale_num" => 0,
                            "sale_price" => 0,
                            "sale_user" => 0,
                        ];
                    }
                    $result['statistics'][$val['goods_id']]["sale_num"] += $val['sale_num'];
                    $result['statistics'][$val['goods_id']]["sale_price"] += $val['sale_price'];
                    $result['statistics'][$val['goods_id']]["sale_user"] += $val['sale_user'];
                }

                break;
            case 3: //日列表统计($time参数表示日)
                //默认月份是今年这个月这天
                if(empty($time) || !is_date($time)){
                    $time = date("Y-m-d",time());
                }

                $result['time'] = date("Y年m月d日",strtotime($time));

                $start_time = strtotime(date("Y-m-d 00:00:00",strtotime($time)));
                $end_time = strtotime(date("Y-m-d 00:00:00",strtotime("+1 day"))) - 1;

                $where = [];
                $where['sale.record_time'][] = ['egt',$start_time];
                $where['sale.record_time'][] = ['lt',$end_time];

                //获取统计表中的全部数据
                $temp = [];
                $temp = M($this->statistics_sale_day_table." as sale")
                    ->field(["sale.goods_id","sale.sale_num","sale.sale_price","sale.sale_user","goods.name","goods.ext_name"])
                    ->join("left join ".C("DB_PREFIX").$this->goods_table." as goods on goods.id = sale.goods_id")
                    ->where($where)
                    ->select();

                //开始统计
                foreach($temp as $val){
                    if(empty($result['statistics'][$val['goods_id']])){
                        $result['statistics'][$val['goods_id']] = [
                            "goods_id" => $val['goods_id'],
                            "goods_name" => $val['name'],
                            "goods_name_str" => cut_str($val['name'],10),
                            "goods_ext_name" => $val['ext_name'],
                            "goods_ext_name_str" => cut_str($val['ext_name'],10),
                            "sale_num" => 0,
                            "sale_price" => 0,
                            "sale_user" => 0,
                        ];
                    }
                    $result['statistics'][$val['goods_id']]["sale_num"] += $val['sale_num'];
                    $result['statistics'][$val['goods_id']]["sale_price"] += $val['sale_price'];
                    $result['statistics'][$val['goods_id']]["sale_user"] += $val['sale_user'];
                }
                break;
        }

        $sort_list = [];
        foreach($result['statistics'] as $info){
            $sort_list[] = $info['sale_num'];
        }
        array_multisort($sort_list,SORT_DESC,$result['statistics']);

        return $result;
    }

}
