//公共js

//手机检验 正确返回 1 错误返回 0
function public_check_mobile(mobile){
    if(mobile.match(/^(1(3|4|5|7|8)([0-9]{9}))$/)){
        return 1;
    }else{
        return 0;
    }
}

//验证码更换
function public_update_verify(){
    $("img.show_verify").attr("src","/Home/User/showVerify/v/"+Math.random());
}

//退出登录
function public_logout(){
    if(confirm('确定要退出这个用户的登录状态？')){
        window.location.href = '/Home/User/userLogout';
    }
}

//弹出提示框
function public_show_tip_alert(){
    public_show_shade();
    $(".big_box_alert").show();
}

//隐藏提示框
function public_hidden_tip_alert(){
    $(".big_box_alert").hide();
    public_hidden_shade();
}

//显示遮罩层
function public_show_shade(){
    $(".big_box_shade").show();
}

//隐藏遮罩层
function public_hidden_shade(){
    $(".big_box_shade").hide();
}
