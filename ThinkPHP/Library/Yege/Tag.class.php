<?php
namespace Yege;

/**
 * 标签类
 *
 * 方法提供
 * addTag               标签添加方法（此方法会改变 tag_id）
 * editTag              编辑标签方法
 * deleteTag            删除标签方法
 * getInfo              根据标签id获取基本信息（此方法会改变 tag_info）
 * getTagsList          获取标签列表
 * getTagsListByGoodsId 根据商品id获取标签列表
 */

class Tag{

    //提供于外面赋值或读取的相关参数
    public $tag_id = 0; //标签id
    public $tag_name = ""; //标签名称

    private $tag_table = ""; //相关标签表
    private $goods_tag_relate_table = ""; //与商品的关联表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->tag_table = C("TABLE_NAME_TAGS");
        $this->goods_tag_relate_table = C("TABLE_NAME_GOODS_TAG_RELATE");
    }

    /**
     * 标签添加方法（此方法会改变 tag_id）
     * @result array $result 结果返回
     */
    public function addTag(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $tag_name = check_str($this->tag_name);
        if(!empty($tag_name)){
            //重复性检查
            $temp_info = $where = array();
            $where['tag_name'] = $tag_name;
            $where['state'] = C('STATE_TAGS_NORMAL');
            $temp_info = M($this->tag_table)->where($where)->find();
            if(empty($temp_info)){
                //添加逻辑
                $add = array();
                $add['tag_name'] = $tag_name;
                $add['inputtime'] = $add['updatetime'] = time();
                $this->tag_id = M($this->tag_table)->add($add);
                if(!empty($this->tag_id)){
                    $result['state'] = 1;
                    $result['message'] = "标签添加成功";
                }else{
                    $result['message'] = "标签添加失败";
                }
            }else{
                $result['message'] = "已有同名标签存在";
            }
        }else{
            $result['message'] = "标签名不能为空";
        }

        return $result;
    }

    /**
     * 编辑标签方法
     * @result array $result 结果返回
     */
    public function editTag(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $tag_id = check_int($this->tag_id);
        $tag_name = check_str($this->tag_name);
        //首先尝试获取详细信息
        $tag_info = array();
        $tag_info = $this->getInfo($tag_id);
        if($tag_info['state'] == 1){
            //重复性查询
            $temp = $where = array();
            $where['id'] = array("neq",$tag_id);
            $where['state'] = C("STATE_TAGS_NORMAL");
            $where['tag_name'] = $tag_name;
            $temp = M($this->tag_table)->where($where)->find();
            if(empty($temp)){
                //编辑逻辑
                $save = $where = array();
                $where['id'] = $tag_id;
                $save['tag_name'] = $tag_name;
                $save['updatetime'] = time();
                if(M($this->tag_table)->where($where)->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "编辑成功";
                }else{
                    $result['message'] = "编辑失败";
                }
            }else{
                $result['message'] = "已有同名标签存在";
            }
        }else{
            $result['message'] = $tag_info['message'];
        }

        return $result;
    }

    /**
     * 删除标签方法
     * @result array $result 结果返回
     */
    public function deleteTag(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";
        $tag_id  = check_int($this->tag_id);
        //尝试获取详情信息
        $tag_info = array();
        $tag_info = $this->getInfo($tag_id);
        if($tag_info['state'] == 1){
            //修改数据
            $save = $where = array();
            $where['id'] = $tag_id;
            $save['state'] = C("STATE_TAGS_DELETE");
            $save['updatetime'] = time();
            if(M($this->tag_table)->where($where)->save($save)){
                $result['state'] = 1;
                $result['message'] = "删除成功";
            }else{
                $result['message'] = "删除失败";
            }
        }else{
            $result['message'] = $tag_info['message'];
        }
        return $result;
    }

    /**
     * 根据标签id获取基本信息（此方法会改变 tag_info）
     * @param int $tag_id 标签id
     * @result array $result 结果返回
     */
    public function getInfo($tag_id = 0){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";
        $tag_id  = check_int($tag_id);
        if(!empty($tag_id) && $tag_id > 0){
            //基础数据获取
            $info = $where = array();
            $where['id'] = $tag_id;
            $info = M($this->tag_table)->where($where)->find();
            if(!empty($info)){
                $result['state'] = 1;
                $result['result'] = $info;
                $result['message'] = "获取成功";
            }else{
                $result['message'] = "未能获取标签信息";
            }
        }else{
            $result['message'] = "标签id错误";
        }
        return $result;
    }

    /**
     * 获取标签列表
     * @return $list 列表结果返回
     */
    public function getTagsList(){
        $list = $where = array();

        $where['state'] = C('STATE_TAGS_NORMAL');

        $list = M($this->tag_table)
            ->field("id,tag_name")
            ->where($where)
            ->order("tag_name ASC")
            ->select();

        return $list;
    }

    /**
     * 根据商品id获取标签列表
     * @param int $goods_id 商品id
     * @return array $tags_list 标签列表
     */
    public function getTagsListByGoodsId($goods_id = 0){
        $tags_list = $where = array();

        $where['goods_relate.goods_id'] = $goods_id;
        $where['tag.state'] = C('STATE_TAGS_NORMAL');
        $tags_list = M($this->goods_tag_relate_table." as goods_relate")
            ->field("tag.id,tag.tag_name")
            ->join("left join ".C("DB_PREFIX").$this->tag_table." as tag on tag.id = goods_relate.tag_id")
            ->where($where)
            ->select();

        return $tags_list;
    }


}
