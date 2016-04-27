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

<div class="admin_box attr_list_box">
    <a href="/Admin/Attr/addAttr/parent_id/<?php echo ($parent_id); ?>">添加属性</a>
    <table class="admin_table_list">
        <tr>
            <th>属性id</th>
            <th>父级属性</th>
            <th>属性名</th>
            <th>添加时间</th>
            <th>操作</th>
        </tr>
        <?php if(is_array($list)): foreach($list as $key=>$val): ?><tr>
                <td><?php echo ($val["id"]); ?></td>
                <td title="<?php echo ($val["parent_name"]); ?>">
                    <?php if(!empty($val["parent_name"])): ?><a href="/Admin/Attr/attrList/parent_id/<?php echo ($val["p_id"]); ?>"><?php echo ($val["parent_name"]); ?></a>
                    <?php else: ?>
                        根<?php endif; ?>
                </td>
                <td title="<?php echo ($val["attr_name"]); ?>">
                    <?php if($val["child_num"] > 0): ?><a href="/Admin/Attr/attrList/parent_id/<?php echo ($val["id"]); ?>"><?php echo ($val["attr_name"]); ?></a>
                    <?php else: ?>
                        <?php echo ($val["attr_name"]); endif; ?>
                </td>
                <td><?php echo (date("Y-m-d H:i:s",$val["inputtime"])); ?></td>
                <td>
                    <a href="/Admin/Attr/editAttr/id/<?php echo ($val["id"]); ?>" >编辑</a>
                    &nbsp;|&nbsp;
                    <a href="javascript:;" onclick="deleteAttr(<?php echo ($val["id"]); ?>,this)">删除</a>
                </td>
            </tr><?php endforeach; endif; ?>
    </table>
</div>

</body>
</html>