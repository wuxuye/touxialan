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

<div class="admin_box edit_attr_box">

    <div class="edit_attr_input_div">
        <form method="post">
            属性名称：<input type="text" name="attr_name" value="<?php echo ($info["attr_name"]); ?>" />
            <button type="submit">提交</button>
        </form>
    </div>

</div>

</body>
</html>