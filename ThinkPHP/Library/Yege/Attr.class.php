<?php
namespace Yege;

/**
 * 属性类
 *
 * 方法提供
 * addAttr              属性添加方法（此方法会改变 attr_id）
 * editAttr             编辑属性方法
 * deleteAttr           删除属性方法
 * getInfo              根据属性id获取基本信息
 * getAttrListById      根据属性id获取列表形式的相关层级数据
 * getBelongById        根据属性id获取其所属属性
 * getContainById       根据属性id获取其包含属性
 * getChildList(私有)   获取全部的子集，组成列表形式返回
 * getAttrTree          属性树状图
 * findTreeChild(私有)  找子集
 */

class Attr{

    //提供于外面赋值或读取的相关参数
    public $attr_id = 0; //属性id
    public $attr_parent_id = 0; //父级id
    public $attr_name = ""; //属性名称

    private $attr_table = ""; //相关属性表
    private $tree_attr_result = []; //树状结构用数据集合

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->attr_table = C("TABLE_NAME_ATTR");
    }

    /**
     * 属性添加方法（此方法会改变 attr_id）
     * @result array $result 结果返回
     */
    public function addAttr(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        //若存在父级id 就开始对父级的属性进行判断
        $attr_parent_id = intval($this->attr_parent_id);
        if(!empty($attr_parent_id)){
            //获取属性信息
            $attr_parent_info = array();
            $attr_parent_info = $this->getInfo($attr_parent_id);
            if($attr_parent_info['state'] != 1){
                $result['message'] = "父级属性获取失败：".$attr_parent_info['message'];
                return $result;
            }
        }else{
            $attr_parent_id = 0;
        }

        $attr_name = check_str($this->attr_name);
        if(!empty($attr_name)){
            //重复性检查
            $temp_info = $where = array();
            $where['attr_name'] = $attr_name;
            $where['parent_id'] = $attr_parent_id;
            $where['state'] = C('STATE_ATTR_NORMAL');
            $temp_info = M($this->attr_table)->where($where)->find();
            if(empty($temp_info)){
                //添加逻辑
                $add = array();
                $add['parent_id'] = $attr_parent_id;
                $add['attr_name'] = $attr_name;
                $add['inputtime'] = $add['updatetime'] = time();
                $this->attr_id = M($this->attr_table)->add($add);
                if(!empty($this->attr_id)){
                    $result['state'] = 1;
                    $result['message'] = "属性添加成功";
                }else{
                    $result['message'] = "属性添加失败";
                }
            }else{
                $result['message'] = "在同级属性中同名属性已存在";
            }
        }else{
            $result['message'] = "属性名不能为空";
        }

        return $result;
    }

    /**
     * 编辑属性方法
     * @result array $result 结果返回
     */
    public function editAttr(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $attr_id = intval($this->attr_id);
        $attr_name = check_str($this->attr_name);
        //首先尝试获取详细信息
        $attr_info = array();
        $attr_info = $this->getInfo($attr_id);
        if($attr_info['state'] == 1){
            //重复性查询
            $temp = $where = array();
            $where['id'] = array("neq",$attr_id);
            $where['parent_id'] = $attr_info['result']['parent_id'];
            $where['state'] = C("STATE_ATTR_NORMAL");
            $where['attr_name'] = $attr_name;
            $temp = M($this->attr_table)->where($where)->find();
            if(empty($temp)){
                //编辑逻辑
                $save = $where = array();
                $where['id'] = $attr_id;
                $save['attr_name'] = $attr_name;
                $save['updatetime'] = time();
                if(M($this->attr_table)->where($where)->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "编辑成功";
                }else{
                    $result['message'] = "编辑失败";
                }
            }else{
                $result['message'] = "同级下有重名的属性存在";
            }
        }else{
            $result['message'] = $attr_info['message'];
        }

        return $result;
    }

    /**
     * 删除属性方法
     * @result array $result 结果返回
     */
    public function deleteAttr(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";
        $attr_id  = intval($this->attr_id);
        //尝试获取详情信息
        $attr_info = array();
        $attr_info = $this->getInfo($attr_id);
        if($attr_info['state'] == 1){
            //修改数据
            $save = $where = array();
            $where['id'] = $attr_id;
            $save['state'] = C("STATE_ATTR_DELETE");
            $save['updattime'] = time();
            if(M($this->attr_table)->where($where)->save($save)){

                $result['is_empty'] = 0;
                //检查这一级下还是否存在正常参数
                $list = $where = array();
                $where['parent_id'] = $attr_info['result']['parent_id'];
                $where['state'] = C('STATE_ATTR_NORMAL');
                $list = M($this->attr_table)->where($where)->count();
                if(empty($list)){
                    $result['is_empty'] = 1;
                }

                $result['state'] = 1;
                $result['message'] = "删除成功";
            }else{
                $result['message'] = "删除失败";
            }
        }else{
            $result['message'] = $attr_info['message'];
        }
        return $result;
    }

    /**
     * 根据属性id获取基本信息（此方法会改变 attr_info）
     * @param int $attr_id 属性id
     * @result array $result 结果返回
     */
    public function getInfo($attr_id = 0){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";
        $attr_id  = intval($attr_id);
        if(!empty($attr_id) && $attr_id > 0){
            //基础数据获取
            $info = $where = array();
            $where['id'] = $attr_id;
            $info = M($this->attr_table)->where($where)->find();
            if(!empty($info)){
                $result['state'] = 1;
                $result['result'] = $info;
                $result['message'] = "获取成功";
            }else{
                $result['message'] = "未能获取属性信息";
            }
        }else{
            $result['message'] = "属性id错误";
        }
        return $result;
    }

    /**
     * 根据属性id获取列表形式的相关层级数据
     * @result array $result 结果返回
     */
    public function getAttrListById(){
        $result = [];
        $result['state'] = 0;
        $result['message'] = "未知错误";

        $attr_id = intval($this->attr_id);
        if($attr_id >= 0){
            $result['list'] = [];

            //直接返回根级目录
            if($attr_id == 0){
                $temp = $where = [];
                $where['parent_id'] = 0;
                $where['state'] = C("STATE_ATTR_NORMAL");
                $temp = M($this->attr_table)->where($where)->order("id ASC")->select();

                //直接返回
                if(!empty($temp)){
                    $result['list'][] = $temp;
                    $result['state'] = 1;
                    $result['message'] = "数据获取成功";
                }else{
                    $result['message'] = "未能获取属性列表，当前信息将会被添加至根目录";
                }

                return $result;
            }

            //检查属性
            $attr_info = $this->getInfo($attr_id);
            if(!empty($attr_info['result']['id']) && $attr_info['result']['state'] == C('STATE_ATTR_NORMAL')){

                $now_attr = [];
                $now_attr = $attr_info['result'];
                $result['last_id'] = $now_attr['id']; //最后一个选中的id
                //直到拿到根
                while(1){
                    //拿到所有同级
                    $temp = $where = array();
                    $where['parent_id'] = $now_attr['parent_id'];
                    $where['state'] = C("STATE_ATTR_NORMAL");
                    $temp = M($this->attr_table)->where($where)->order("id ASC")->select();
                    if(!empty($temp)){
                        //标记选中
                        foreach($temp as $key => $val){
                            $temp[$key]['select'] = 0;
                            if($val['id'] == $now_attr['id']){
                                $temp[$key]['select'] = 1;
                            }
                        }
                        //讲这一级加到数组最前面
                        array_unshift($result['list'],$temp);
                        if(!empty($now_attr['parent_id'])){
                            //用这个父级id获取上一级
                            $where = array();
                            $where['id'] = $now_attr['parent_id'];
                            $where['state'] = C("STATE_ATTR_NORMAL");
                            $now_attr = [];
                            $now_attr = M($this->attr_table)->where($where)->find();
                            if(!empty($now_attr)){
                                //继续逻辑
                            }else{
                                //上级信息未获取就跳出
                                break;
                            }
                        }else{
                            //没有上级了就跳出
                            break;
                        }
                    }else{
                        //没有同级就跳出
                        break;
                    }
                }

                //若最后选中的id不为0 就获取后面的一级
                if(!empty($result['last_id'])){
                    $temp = $where = array();
                    $where['parent_id'] = $result['last_id'];
                    $where['state'] = C("STATE_ATTR_NORMAL");
                    $temp = M($this->attr_table)->where($where)->select();
                    if(!empty($temp)){
                        $result['list'][] = $temp;
                    }
                }

                $result['state'] = 1;
                $result['message'] = "数据获取成功";

            }else{
                $result['message'] = "不正确的属性信息";
            }
        }else{
            $result['message'] = "属性id错误";
        }

        return $result;
    }

    /**
     * 根据属性id获取其所属属性
     * @result array $result 结果返回
     */
    public function getBelongById(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";
        $attr_id = intval($this->attr_id);
        if($attr_id > 0){
            //尝试获取基础信息
            $info = array();
            $info = $this->getInfo($attr_id);
            if($info['state'] == 1){
                //开始逐个向上获取父级
                $parent_result = $now_attr = array();
                $now_attr = $info['result'];
                while(1){
                    //拿到父级id
                    $parent_id = $now_attr['parent_id'];
                    if(!empty($parent_id)){
                        $temp_attr = array();
                        $temp_attr = $this->getInfo($parent_id);
                        if($temp_attr['state'] == 1){
                            $now_attr = [];
                            $now_attr = $temp_attr['result'];
                            $parent_result[] = $now_attr;
                        }else{
                            break;
                        }
                    }else{
                        break;
                    }
                }
                $result['state'] = 1;
                $result['result'] = $parent_result;
                $result['message'] = "获取成功";
            }else{
                $result['message'] = $info['message'];
            }
        }else{
            $result['message'] = "属性id错误";
        }
        return $result;
    }

    /**
     * 根据属性id获取其包含属性
     * @result array $result 结果返回
     */
    public function getContainById(){
        $result = array();
        $result['state'] = 0;
        $result['message'] = "未知错误";
        $attr_id = intval($this->attr_id);
        if($attr_id > 0){
            //尝试获取基础信息
            $info = array();
            $info = $this->getInfo($attr_id);
            if($info['state'] == 1){
                //开始逐个向下获取子级
                $child_result = $now_attr = [];
                $now_attr = $info['result'];
                $child_result = $this->getChildList($now_attr['id']);

                $result['state'] = 1;
                $result['result'] = $child_result;
                $result['message'] = "获取成功";
            }else{
                $result['message'] = $info['message'];
            }
        }else{
            $result['message'] = "属性id错误";
        }
        return $result;
    }

    /**
     * 获取全部的子集，组成列表形式返回
     * @result array $result 结果返回
     */
    private function getChildList($attr_id = 0){
        $result = array();

        //首先获取自己
        $info = array();
        $info = $this->getInfo($attr_id);
        if($info['state'] == 1){

            //再逐个获取下级
            $child = $where = array();
            $where['parent_id'] = $attr_id;
            $where['state'] = C("STATE_ATTR_NORMAL");
            $child = M($this->attr_table)->where($where)->select();
            foreach($child as $key => $val){
                $temp_result = array();
                $temp_result['attr_id'] = $val['id'];
                $temp_result['attr_parent_id'] = $val['parent_id'];
                $temp_result['attr_name'] = $val['attr_name'];
                $result[] = $temp_result;
                //调自己拿到再下面一层的东西
                $temp = array();
                $temp = $this->getChildList($val['id']);
                foreach($temp as $k => $v){
                    $result[] = $v;
                }
            }
        }

        return $result;
    }

    /**
     * 属性树状图
     * @return array $result 结果返回
     */
    public function getAttrTree(){
        $result = [];

        //获取所有正常属性
        $list = $where = [];
        $where['state'] = C("STATE_ATTR_NORMAL");
        $list = M($this->attr_table)->where($where)->select();

        //整理数组
        $this->tree_attr_result = [];
        foreach($list as $attr){
            $this->tree_attr_result[$attr['id']] = $attr;
        }

        //搭建树状图
        foreach($this->tree_attr_result as $key => $val){
            //先拿到几个根（父级id为0）
            if($val['parent_id'] == 0){
                $result[$key] = [
                    'id' => $key,
                    'attr_name' => $val['attr_name'],
                    'parent_id' => $val['parent_id'],
                    'child' => [],
                ];
                unset($this->tree_attr_result[$key]);
            }
        }

        if(!empty($result)){
            //排序数组
            ksort($result);

            //开始逐个取得子集
            foreach($result as $key => $val){
                $result[$key]['child'] = $this->findTreeChild($val['id'],$this->tree_attr_result);
            }
        }

        return $result;
    }

    /**
     * 找子集
     * @param int $parent_id 父级id
     * @param array $array 剩余结果集
     * @return array $result 结果返回
     */
    private function findTreeChild($parent_id = 0,$array = []){
        $result = [];

        //在剩余结果集中找到以 $child_id 为根的数据
        foreach($array as $key => $val){
            if($val['parent_id'] == $parent_id){
                $result[$key] = [
                    'id' => $key,
                    'attr_name' => $val['attr_name'],
                    'parent_id' => $val['parent_id'],
                    'child' => [],
                ];
                unset($array[$key]);
            }
        }

        //将结果存在全局的数据集合里
        $this->tree_attr_result = $array;

        if(!empty($result)){
            //排序数组
            ksort($result);

            //开始逐个取得子集
            foreach($result as $key => $val){
                $result[$key]['child'] = $this->findTreeChild($val['id'],$this->tree_attr_result);
            }
        }

        return $result;
    }

}
