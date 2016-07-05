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