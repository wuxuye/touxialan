//用户注册页相关JS

//手机检查
function user_login_check_mobile(){
    var mobile = $("input#user_login_mobile").val();
    if(mobile.length>0){
        if(public_check_mobile(mobile)){
            return 1;
        }else{
            alert("手机号格式错误");
        }
    }else{
        alert("请填写手机号");
    }
    return 0;
}

//验证码更换
function user_login_update_verify(obj){
    $(obj).attr("src","/Home/User/showVerify/v/"+Math.random());
}

//表单提交
function user_login_form_submit(){
    if(user_login_check_mobile()){
        $("form#user_login_form").submit();
    }
}