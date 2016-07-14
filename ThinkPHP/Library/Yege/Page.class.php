<?php
namespace Yege;

/**
 * 简单的分页类
 * 要配合page.css、page.js与页面上一个id为admin_page_form的表单
 *
 * 方法提供
 * show        显示方法，根据公共参数，组装出分页html
 */

class Page{

    //提供于外面赋值或读取的相关参数
    public $now_page = 1; //当前页

    private $all_count = 0; //总数量
    private $show_num = 20; //一页显示数量
    private $all_page = 0; //总页数

    public function __construct($now_page = 1,$all_count = 0,$show_num = 20){
        header("Content-Type: text/html; charset=utf-8");
        $this->now_page = check_int($now_page);
        $this->all_count = check_int($all_count);
        $this->show_num = check_int($show_num);
        if($this->all_count > 0){
            //排满的页数
            $temp_page = check_int($this->all_count/$this->show_num);
            $this->all_page = $temp_page;
            //多出来的数量
            $full_page = check_int($this->all_count%$this->show_num);
            if($full_page > 0){
                $this->all_page ++;
            }
        }

    }

    /**
     * 显示方法，根据公共参数，组装出分页html
     * @return string $result 结果返回
     */
    public function show(){
        $result = "";

        if($this->all_page > 0){

            //检查当前页
            if($this->now_page <= 0){
                $this->now_page = 1;
            }
            if($this->now_page > $this->all_page){
                $this->now_page = $this->all_page;
            }

            //中间的数据
            $middle = "&nbsp;当前页&nbsp;<input type='text' class='now_page' value='".$this->now_page."' max_page='".$this->all_page."' />&nbsp;/&nbsp;总页数：&nbsp;".$this->all_page."&nbsp;<a class='page_submit' href='javascript:;'>提交</a>&nbsp;";

            $last_page = $next_page ="";
            //上一页
            if($this->now_page > 1){
                $last_page = "&nbsp;<a href='javascript:;' class='last_page' page_number='".($this->now_page - 1)."' >上一页</a>&nbsp;";
            }
            //下一页
            if($this->now_page < $this->all_page){
                $next_page = "&nbsp;<a href='javascript:;' class='next_page' page_number='".($this->now_page + 1)."' >下一页</a>&nbsp;";
            }

            $result = "<div class='page'>".$last_page.$middle.$next_page."</div>";

        }

        return $result;
    }

}
