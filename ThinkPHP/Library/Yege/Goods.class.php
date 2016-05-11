<?php
namespace Yege;

/**
 * 商品类
 *
 * 方法提供
 * addGoods         商品添加
 * addGoodsDo（私有）商品添加执行逻辑（此方法会改变 goods_id 的值）
 * editGoods        商品编辑方法
 * editGoodsDo      商品编辑执行逻辑
 * checkParam       参数检验（此方法可能会改变 goods_id、goods_belong_id、goods_name、goods_ext_name、goods_price与goods_describe的值）
 * getGoodsInfo     商品详情获取（此方法会改变 goods_info 的值）
 * shelveGoods      商品上架
 * unshelveGoods    商品下架
 * deleteGoods      删除商品
 */

class Goods{

    //提供于外面赋值或读取的相关参数
    public $goods_id = 0; //商品id
    public $goods_belong_id = 0; //属归id
    public $goods_name = ""; //商品名称
    public $goods_ext_name = ""; //商品扩展名
    public $goods_attr_id = 0; //商品属性id
    public $goods_price = 0; //商品单价
    public $goods_describe = ""; //商品描述
    public $goods_image = ""; //商品图片

    private $goods_info = array(); //商品详细信息
    private $goods_table = ""; //相关商品表
    private $user_table = ""; //相关用户表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->goods_table = C("TABLE_NAME_GOODS");
        $this->user_table = C("TABLE_NAME_USER");
    }

    /**
     * 商品添加方法
     * @return array $result 结果返回
     */
    public function addGoods(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        //对各种参数进行检验
        $check_list = array( //待检测的参数列表
            "goods_name","goods_ext_name","goods_attr_id","goods_price","goods_describe","goods_belong_id","goods_image",
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
            $add_result = array();
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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

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
            $add['describe'] = $this->goods_describe;
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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        //对各种参数进行检验
        $check_list = array( //待检测的参数列表
            "goods_name","goods_ext_name","goods_attr_id","goods_price","goods_describe","goods_belong_id","goods_image",
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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

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
            $edit['describe'] = $this->goods_describe;
            $edit['is_shop'] = C("STATE_GOODS_UNSHELVE"); //更新商品会让商品变为下架状态
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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        //对各种参数进行检验
        switch($str){
            case 'goods_id': //商品id检查
                $param = intval($this->goods_id);
                if($param <= 0){
                    $result['message'] = "商品id错误";
                }else{
                    $this->goods_id = $param;
                    $result['state'] = 1;
                }
                break;
            case 'goods_belong_id': //商品所属检查
                $param = intval($this->goods_belong_id);
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
                $param = strip_tags(trim($this->goods_name));
                if (!empty($param)){
                    $this->goods_name = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "商品名称不能为空";
                }
                break;
            case 'goods_ext_name': //商品扩展名称检查
                $param = strip_tags(trim($this->goods_ext_name));
                if (!empty($param)){
                    $this->goods_ext_name = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "商品扩展名不能为空";
                }
                break;
            case 'goods_attr_id': //商品属性id检查
                $param = intval($this->goods_attr_id);
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
                if (is_numeric($param) && !empty($param)){
                    $this->goods_price = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "请填写正确的商品价格";
                }
                break;
            case 'goods_describe': //商品描述检查
                $param = strip_tags(trim($this->goods_describe));
                if (!empty($param)){
                    $this->goods_describe = $param;
                    $result['state'] = 1;
                }else{
                    $result['message'] = "商品描述不能为空";
                }
                break;
            case 'goods_image': //商品图片检查
                $param = trim($this->goods_image);
                if (!empty($param)){
                    $this->goods_image = $param;
                }else{
                    $this->goods_image = "";
                }
                //直接返回 可以为空
                $result['state'] = 1;
                break;
            default : //未定义的检查类型
                $result['message'] = "未定义的检查类型";
                break;

        }

        return $result;
    }

    /**
     * 商品详情获取
     * @return array $result 商品结果集返回
     */
    public function getGoodsInfo(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $check_result = array();
        $check_result = $this->checkParam("goods_id");
        if($check_result['state'] == 1){
            $goods_id = $this->goods_id;
            $goods_info = $where = array();
            $where['id'] = $this->goods_id;
            $goods_info = M($this->goods_table)->where($where)->find();
            if(!empty($goods_info['id'])){
                //重组数据返回
                $return_info = array();
                $return_info['goods_id'] = $goods_info['id'];
                $return_info['goods_belong_id'] = $goods_info['belong_id'];
                $return_info['goods_name'] = $goods_info['name'];
                $return_info['goods_ext_name'] = $goods_info['ext_name'];
                $return_info['goods_attr_id'] = $goods_info['attr_id'];
                $return_info['goods_price'] = $goods_info['price'];
                $return_info['goods_describe'] = $goods_info['describe'];
                $return_info['goods_state'] = $goods_info['state'];
                $return_info['goods_is_shop'] = $goods_info['is_shop'];
                $return_info['goods_image'] = $goods_info['goods_image'];
                $result['state'] = 1;
                $result['result'] = $return_info;
                $result['message'] = "获取成功";

                $this->goods_info = $goods_info;
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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        //先尝试获取详情
        $info = array();
        $info = $this->getGoodsInfo();
        if($info['state'] == 1){
            if(!empty($this->goods_info['id'])){
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
                $result['message'] = "获取商品信息失败";
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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        //先尝试获取详情
        $info = array();
        $info = $this->getGoodsInfo();
        if($info['state'] == 1){
            if(!empty($this->goods_info['id'])){
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
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        //先尝试获取详情
        $info = array();
        $info = $this->getGoodsInfo();
        if($info['state'] == 1){
            if(!empty($this->goods_info['id'])){
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


}
