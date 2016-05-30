//用户相关公共js


//==========用户注册==========
//手机检查
function user_register_check_mobile(){
    var mobile = $("input#user_register_mobile").val();
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

//表单提交
function user_register_form_submit(){
    if(user_register_check_mobile()){
        $.ajax({
            url:'/Home/Ajax/ajaxUserRegister',
            type:'POST',
            dataType:'JSON',
            data:$("#user_register_form").serialize(),
            success:function(msg){
                alert(msg.message);
                if(msg.state==1){
                    //跳去首页
                    window.location.href = "/";
                }else{
                    public_update_verify();
                }
            }
        });
    }
}


//==========用户登录==========
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

//表单提交
function user_login_form_submit(){
    if(user_login_check_mobile()){
        $.ajax({
            url:'/Home/Ajax/ajaxUserLogin',
            type:'POST',
            dataType:'JSON',
            data:$("#user_login_form").serialize(),
            success:function(msg){
                alert(msg.message);
                if(msg.state==1){
                    if(msg.back_url){
                        window.location.href = msg.back_url;
                    }else{
                        //跳去首页
                        window.location.href = "/";
                    }
                }else{
                    public_update_verify();
                }
            }
        });
    }
}


//==========用户修改密码==========
//表单提交
function user_edit_password_form_submit(){
    $.ajax({
        url:'/Home/Ajax/ajaxUserEditPassword',
        type:'POST',
        dataType:'JSON',
        data:$("#user_edit_password_form").serialize(),
        success:function(msg){
            alert(msg.message);
            if(msg.state==1){
                //跳去登录页
                window.location.href = "/Home/User/userLogin";
            }else{
                public_update_verify();
            }
        }
    });
}

//==========用户重置密码==========
//表单提交
function user_reset_password_form_submit(){
    $.ajax({
        url:'/Home/Ajax/ajaxUserResetPassword',
        type:'POST',
        dataType:'JSON',
        data:$("#user_reset_password_form").serialize(),
        success:function(msg){
            alert(msg.message);
            if(msg.state==1){
                //跳去登录页
                window.location.href = "/Home/User/userLogin";
            }
        }
    });
}
