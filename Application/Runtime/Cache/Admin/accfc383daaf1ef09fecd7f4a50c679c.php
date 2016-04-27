<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ($admin_head_title); ?></title>

    <link type="text/css" rel="stylesheet" href="<?php echo C('_ADMIN_CSS_');?>/public.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo C('_ADMIN_CSS_');?>/page.css" />

    <script type="text/javascript" src="<?php echo C('_ADMIN_JS_');?>/jquery-1.10.2.min.js"></script>

</head>
<body>

<script type="text/javascript" src="<?php echo C('_ADMIN_JS_');?>/attr.js"></script>

<div class="admin_box add_attr_box">

    <div class="add_attr_parent_div">
        当前父级：<span id="attr_list"></span>
    </div>

    <div class="add_attr_input_div">
        属性名称：<input type="text" name="attr_name" />
        <input type="hidden" id="attr_parent_id" name="attr_parent_id" value="<?php echo ($parent_id); ?>" />
        <input type="hidden" id="add_wait" value="0" />
        <button type="button" onclick="submitToAddAttr()">提交</button>
    </div>

</div>

<script>
    //初始化
    $("#add_wait").val(0);
    //页面载入时调用
    var attr_parent_id = $("#attr_parent_id").val();
    //用这个父级id初始化当前父级
    getAttrList(attr_parent_id);
</script>

</body>
</html>