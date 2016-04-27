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

<script type="text/javascript" src="<?php echo C('_ADMIN_JS_');?>/page.js"></script>

<a href="/Admin/Goods/addGoods">添加商品</a>
<table class="admin_table_list">
    <tr>
        <th>商品id</th>
        <th>商品归属</th>
        <th>商品名称</th>
        <th>商品扩展名</th>
        <th>商品标记</th>
        <th>商品单价</th>
        <th>商品描述</th>
        <th>商品状态</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php if(is_array($list)): foreach($list as $key=>$val): ?><tr>
            <td><?php echo ($val["id"]); ?></td>
            <td title="<?php echo ($val["belong_id"]); ?>"><?php echo ($val["belong_str"]); ?></td>
            <td title="<?php echo ($val["name"]); ?>"><?php echo (cut_str($val["name"],8)); ?></td>
            <td title="<?php echo ($val["ext_name"]); ?>"><?php echo (cut_str($val["ext_name"],10)); ?></td>
            <td title="<?php echo ($val["tab_str"]); ?>"><?php echo (cut_str($val["tab_str"],10)); ?></td>
            <td><?php echo ($val["price"]); ?></td>
            <td title="<?php echo ($val["describe"]); ?>"><?php echo (cut_str($val["describe"],15)); ?></td>
            <td><?php echo ($val["is_shop_str"]); ?></td>
            <td><?php echo (date("Y-m-d H:i:s",$val["inputtime"])); ?></td>
            <td>
                <a href="/Admin/Goods/editGoods/id/<?php echo ($val["id"]); ?>" >编辑</a>
                &nbsp;|&nbsp;
                <?php if($val["is_shop"] == 1): ?><a href="/Admin/Goods/unshelveGoods/id/<?php echo ($val["id"]); ?>">下架</a>
                <?php else: ?>
                    <a href="/Admin/Goods/shelveGoods/id/<?php echo ($val["id"]); ?>">上架商品</a><?php endif; ?>
                &nbsp;|&nbsp;
                <a href="/Admin/Goods/deleteGoods/id/<?php echo ($val["id"]); ?>">删除</a>
            </td>
        </tr><?php endforeach; endif; ?>
</table>

<form id="admin_page_form" method="post">
    <input type="hidden" name="search_now_page" value="<?php echo ($dispose["page"]); ?>" />
</form>

<?php echo ($page); ?>
</body>
</html>