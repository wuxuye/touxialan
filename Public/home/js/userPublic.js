//用户相关公共js


//==========用户注册==========
//手机检查
function user_register_check_mobile(){
    var mobile = $("input#user_register_mobile").val();
    if(mobile.length>0){
        if(public_check_mobile(mobile)){
            return 1;
        }else{
            public_fill_alert("手机号格式错误");
        }
    }else{
        public_fill_alert("请填写手机号");
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
                if(msg.state==1){
                    public_fill_alert(msg.message,"注册成功","success");
                    //跳去首页
                    window.location.href = "/";
                }else{
                    public_fill_alert(msg.message);
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
            public_fill_alert("手机号格式错误");
        }
    }else{
        public_fill_alert("请填写手机号");
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
                if(msg.state==1){
                    public_fill_alert(msg.message,"登录成功","success");
                    if(msg.back_url){
                        window.location.href = msg.back_url;
                    }else{
                        //跳去首页
                        window.location.href = "/";
                    }
                }else{
                    public_fill_alert(msg.message);
                    public_update_verify();
                }
            }
        });
    }
}

//==========用户订单中心==========
//为table的某行改变下划线
function user_order_list_active_table(obj){
    $(obj).not(".order_table_title").find("td").addClass("active");
}

//去掉table中所有的active
function user_order_list_remove_active(){
    $(".user_center_box .user_center_right .user_center_order_box .order_table td").removeClass("active");
}

//删除指定订单
function user_order_list_delete_order(order_id,obj){
    if(confirm("确定要删除这张订单？")){
        $.ajax({
            url:'/Home/Ajax/ajaxUserDeleteOrder',
            type:'POST',
            dataType:'JSON',
            data:'order_id='+order_id,
            success:function(msg){
                if(msg.state==1){
                    $(obj).parent().parent().fadeOut("fast",function(){
                        $(obj).parent().parent().remove();
                    });
                }else{
                    public_fill_alert(msg.message);
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
            if(msg.state==1){
                public_fill_alert(msg.message,"修改密码成功");
                //跳去登录页
                window.location.href = "/Home/User/userLogin";
            }else{
                public_fill_alert(msg.message,"修改密码失败");
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
            if(msg.state==1){
                public_fill_alert(msg.message,"重置成功","success");
                //跳去登录页
                window.location.href = "/Home/User/userLogin";
            }else{
                public_fill_alert(msg.message);
            }
        }
    });
}

//==========收货地址相关==========
//操作地址
function save_receipt_address_form_submit(){
    $.ajax({
        url:'/Home/Ajax/ajaxUserCenterSaveReceiptAddress',
        type:'POST',
        dataType:'JSON',
        data:$("#save_receipt_address_form").serialize(),
        success:function(msg){
            if(msg.state==1){
                public_fill_alert(msg.message,"操作成功");
                //跳去列表页
                window.location.href = "/Home/UserCenter/userReceiptAddressList";
            }else{
                public_fill_alert(msg.message,"操作收货地址失败");
            }
        }
    });
}

//设置默认收货地址
function set_receipt_address_is_default($id){
    if(confirm("确定要将这条收货地址信息，改变为默认收货地址？")) {
        $.ajax({
            url: '/Home/Ajax/ajaxUserCenterSetDefaultReceiptAddress',
            type: 'POST',
            dataType: 'JSON',
            data: 'id=' + $id,
            success: function (msg) {
                if (msg.state == 1) {
                    //刷新页面
                    window.location.reload();
                } else {
                    public_fill_alert(msg.message);
                }
            }
        });
    }
}

//删除指定收货地址
function delete_receipt_address($id){
    if(confirm("确定要删除这条收货地址信息？")){
        $.ajax({
            url:'/Home/Ajax/ajaxUserCenterDeleteReceiptAddress',
            type:'POST',
            dataType:'JSON',
            data:'id='+$id,
            success:function(msg){
                if(msg.state==1){
                    //刷新页面
                    window.location.reload();
                }else{
                    public_fill_alert(msg.message);
                }
            }
        });
    }
}