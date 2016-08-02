//商品列表相关JS
function toAnchor(attr_id){
    location.href = '#attr_'+attr_id;
}

//商品添加至购物车
function addCart(obj){
    var can_cart = $(obj).attr("can_cart");
    var has_cart = $(obj).attr("has_cart");
    if(can_cart == 1 && has_cart == 0){

    }
}
