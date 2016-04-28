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

<link type="text/css" rel="stylesheet" href="<?php echo C('_ADMIN_CSS_');?>/tag.css" />

<script type="text/javascript" src="<?php echo C('_ADMIN_JS_');?>/attr.js"></script>
<script type="text/javascript" src="<?php echo C('_ADMIN_JS_');?>/tag.js"></script>

<div class="admin_box add_goods_box">
    <form method="post" enctype="multipart/form-data" >
        商品名称：<input type="text" name="goods_name" /><br>
        商品扩展名：<input type="text" name="goods_ext" /><br>
        所属分类：<span id="attr_list"></span>
        <input type="hidden" id="attr_parent_id" name="goods_attr_id" value="0" /><br>
        标签：
        <div id="tags_list">
            <div class="tag_list_top_message"></div>
            <div class="tag_list_top_box">
                <div class="first_button" onclick="editShowTags()">编辑标签</div>
                <div class="next_button" onclick="editHideTags()">隐藏标签</div>
            </div>
            <div class="tag_list_foot_box">
                <?php if(is_array($tags_list)): foreach($tags_list as $key=>$val): ?><div class="tag_box" onclick="selectTags(this)" tag_id="<?php echo ($val["id"]); ?>" ><?php echo ($val["tag_name"]); ?></div><?php endforeach; endif; ?>
            </div>
            <input type="hidden" id="now_tags_list" />
        </div>
        <br>
        商品单价：<input type="text" name="goods_price" /><br>
        商品描述：<textarea name="goods_describe"></textarea><br>
        商品所属：<input type="text" name="goods_belong" /><br>
        商品图片：<input type="file" name="goods_image" /><br>
        <button type="submit">提交</button>
    </form>
</div>

<script>
    //页面载入时调用
    $("#attr_parent_id").val(0);
    //用这个父级id初始化当前父级
    getAttrList(0);

    //初始化标签
    readyTags(0);

</script>

</body>
</html>