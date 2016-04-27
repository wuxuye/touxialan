<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ($home_head_title); ?></title>

    <link type="text/css" rel="stylesheet" href="<?php echo C('_HOME_CSS_');?>/public.css" />

    <script type="text/javascript" src="<?php echo C('_HOME_JS_');?>/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="<?php echo C('_HOME_JS_');?>/public.js"></script>

</head>
<body>
<script type="text/javascript" src="<?php echo C('_HOME_JS_');?>/userLogin.js"></script>
<form method="post" id="user_login_form">
    <div>手机：<input type="text" name="mobile" id="user_login_mobile" /></div>
    <div>密码：<input type="password" name="password" /></div>
    <div>验证码：<input type="text" name="verify" /></div>
    <div><img src="/Home/User/showVerify" title="点击更换" onclick="user_login_update_verify(this)" /></div>
    <div><button type="button" onclick="user_login_form_submit()">提交</button></div>
</form>
</body>
</html>