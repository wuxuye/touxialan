<?php
namespace Yege;

/**
 * 基础参数类
 *
 * 方法提供
 *
 */

class Param{

    private $param_table = ""; //全站基础参数配置表

    public function __construct(){
        header("Content-Type: text/html; charset=utf-8");
        $this->param_table = C("TABLE_NAME_PARAM");
    }



}
