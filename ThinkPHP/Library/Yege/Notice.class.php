<?php
namespace Yege;

/**
 * 公告类
 *
 * 方法提供
 * getNoticeList        获取公告列表
 * addNotice            添加公告
 * editNotice           编辑公告
 * topNotice            置顶公告
 * deleteNotice         删除公告
 * getInfo              根据公告id获取基本信息
 */

class Notice{

    //提供于外面赋值或读取的相关参数
    public $notice_id = 0; //公告id
    public $title = ""; //公告标题
    public $message = ""; //公告内容
    public $author = 0; //作者id

    private $notice_info = []; //公告详情

    private $notice_table = ""; //相关公告表
    private $user_table = ""; //相关用户表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->notice_table = C("TABLE_NAME_NOTICE");
        $this->user_table = C("TABLE_NAME_USER");
    }

    /**
     * 获取公告列表
     * @param array $where 搜索条件
     * @param int $page 页码
     * @param int $num 单页数量
     * @return array $list 结果返回
     */
    public function getNoticeList($where = [],$page = 1,$num = 20){
        $result = [
            "list" => [],
            "count" => 0,
        ];

        //基础参数
        $where["is_delete"] = 0;

        $limit = ($page-1)*$num.",".$num;

        $notice_list = M($this->notice_table)
            ->where($where)
            ->limit($limit)
            ->order("is_top DESC,count DESC,inputtime DESC")
            ->select();

        $result['list'] = $notice_list;

        //数量获取
        $count = M($this->notice_table)
            ->where($where)
            ->count();
        $result['count'] = empty($count) ? 0 : $count;

        return $result;
    }

    /**
     * 添加公告
     * @result array $result 结果返回
     */
    public function addNotice(){
        $result = ['state'=>0,'message'=>'未知错误'];

        $title = check_str($this->title);
        $message = $this->message;
        if(!empty($title) && !empty($message)){
            $add = [];
            $add['title'] = $title;
            $add['message'] = $message;
            $add['author'] = $this->author;
            $add['inputtime'] = $add['updatetime'] = time();

            if(M($this->notice_table)->add($add)){
                $result['state'] = 1;
                $result['message'] = "添加成功";
            }else{
                $result['message'] = "公告添加失败";
            }
        }else{
            $result['message'] = "公告标题与内容不能为空";
        }

        return $result;
    }

    /**
     * 编辑公告
     * @result array $result 结果返回
     */
    public function editNotice(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //尝试获取详情信息
        $notice_info = [];
        $notice_info = $this->getInfo();
        if($notice_info['state'] == 1){
            $title = check_str($this->title);
            $message = $this->message;
            if(!empty($title) && !empty($message)){
                $save = $where = [];
                $where['id'] = $this->notice_info['id'];
                $save['title'] = $title;
                $save['message'] = $message;
                $save['author'] = $this->author;
                $save['updatetime'] = time();

                if(M($this->notice_table)->where($where)->save($save)){
                    $result['state'] = 1;
                    $result['message'] = "修改成功";
                }else{
                    $result['message'] = "公告修改失败";
                }
            }else{
                $result['message'] = "公告标题与内容不能为空";
            }
        }else{
            $result['message'] = $notice_info['message'];
        }

        return $result;
    }

    /**
     * 置顶公告
     * @result array $result 结果返回
     */
    public function topNotice(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //尝试获取详情信息
        $notice_info = [];
        $notice_info = $this->getInfo();
        if($notice_info['state'] == 1){
            //修改数据
            $save = $where = [];
            $where['id'] = $this->notice_info['id'];
            $save['is_top'] = 1;
            $save['updatetime'] = time();
            if(M($this->notice_table)->where($where)->save($save)){
                $result['state'] = 1;
                $result['message'] = "置顶成功";
            }else{
                $result['message'] = "置顶失败";
            }
        }else{
            $result['message'] = $notice_info['message'];
        }
        return $result;
    }

    /**
     * 取消置顶
     * @result array $result 结果返回
     */
    public function cancelNotice(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //尝试获取详情信息
        $notice_info = [];
        $notice_info = $this->getInfo();
        if($notice_info['state'] == 1){
            //修改数据
            $save = $where = [];
            $where['id'] = $this->notice_info['id'];
            $save['is_top'] = 0;
            $save['updatetime'] = time();
            if(M($this->notice_table)->where($where)->save($save)){
                $result['state'] = 1;
                $result['message'] = "取消置顶成功";
            }else{
                $result['message'] = "取消置顶失败";
            }
        }else{
            $result['message'] = $notice_info['message'];
        }
        return $result;
    }

    /**
     * 删除公告
     * @result array $result 结果返回
     */
    public function deleteNotice(){
        $result = ['state'=>0,'message'=>'未知错误'];

        //尝试获取详情信息
        $notice_info = [];
        $notice_info = $this->getInfo();
        if($notice_info['state'] == 1){
            //修改数据
            $save = $where = [];
            $where['id'] = $this->notice_info['id'];
            $save['is_delete'] = 1;
            $save['updatetime'] = time();
            if(M($this->notice_table)->where($where)->save($save)){
                $result['state'] = 1;
                $result['message'] = "删除成功";
            }else{
                $result['message'] = "删除失败";
            }
        }else{
            $result['message'] = $notice_info['message'];
        }
        return $result;
    }

    /**
     * 根据公告id获取基本信息
     * @param int $notice_id 标签id
     * @result array $result 结果返回
     */
    public function getInfo(){
        $result = ['state'=>0,'result'=>[],'message'=>'未知错误'];

        $notice_id  = check_int($this->notice_id);
        if(!empty($notice_id) && $notice_id > 0){
            //基础数据获取
            $info = $where = [];
            $where['notice.id'] = $notice_id;
            $info = M($this->notice_table." as notice")
                ->field([
                    "notice.*","users.username","users.nick_name","users.mobile",
                ])
                ->join("left join ".C("DB_PREFIX").$this->user_table." as users on users.id = notice.author")
                ->where($where)->find();
            if(!empty($info)){
                $result['state'] = 1;
                $result['result'] = $info;
                $result['message'] = "获取成功";
                $this->notice_info = $info;
            }else{
                $result['message'] = "未能获取公告信息";
            }
        }else{
            $result['message'] = "公告id错误";
        }
        return $result;
    }

    /**
     * 公告浏览量记录
     */
    public function updateNoticeCount(){

        //浏览ip获取
        $notice_ip = cookie("notice_ip");
        $now_ip = get_real_ip();
        if($now_ip != $notice_ip){
            $where = [
                "id" => $this->notice_id,
            ];
            if(M($this->notice_table)->where($where)->save(["count"=>["exp","count+1"]])){
                cookie("notice_ip",$now_ip,3600);
            }
        }

    }


}
