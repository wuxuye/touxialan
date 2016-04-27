//公共js

//手机检验 正确返回 1 错误返回 0
function public_check_mobile(mobile){
    if(mobile.match(/^(1(3|4|5|7|8)([0-9]{9}))$/)){
        return 1;
    }else{
        return 0;
    }
}