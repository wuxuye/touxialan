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

<div class="admin_box edit_goods_box">
    <form method="post" enctype="multipart/form-data" >
        商品名称：<input type="text" name="goods_name" value="<?php echo ($info["goods_name"]); ?>" />
        商品扩展名：<input type="text" name="goods_ext" value="<?php echo ($info["goods_ext_name"]); ?>" />
        商品标记：<input type="text" name="goods_tab" value="<?php echo (tab_dispose($info["goods_tab"])); ?>" />
        所属分类：<span id="attr_list"></span>
        <input type="hidden" id="attr_parent_id" name="goods_attr_id" value="<?php echo ($info["goods_attr_id"]); ?>" />
        商品单价：<input type="text" name="goods_price" value="<?php echo ($info["goods_price"]); ?>" />
        商品描述：<textarea name="goods_describe"><?php echo ($info["goods_describe"]); ?></textarea>
        商品所属：<input type="text" name="goods_belong" value="<?php echo ($info["goods_belong_id"]); ?>" />
        商品图片：<input type="file" name="goods_image" />
        <?php if(!empty($info["goods_image"])): ?><a href="<?php echo C('WEB_DOMAIN');?>/<?php echo ($info["goods_image"]); ?>" target="_blank" >
                <img src="/<?php echo ($info["goods_image"]); ?>" width="50" height="50" />
            </a>
        <?php else: ?>
            没有上传商品图片<?php endif; ?>
        <button type="submit">提交</button>
    </form>
</div>

<script>
    //页面载入时调用
    var goods_attr_id = $("#attr_parent_id").val();
    //用这个父级id初始化当前父级
    getAttrList(goods_attr_id);
</script>

</body>
</html>