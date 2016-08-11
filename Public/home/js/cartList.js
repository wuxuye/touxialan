//清单列表JS


//商品数量加减
//增加
function addGoodsNum(obj){
    var input_obj = $(obj).parent().find("input.hidden_goods_num");
    var input_num = parseInt(input_obj.val());
    var max_num = parseInt($(obj).parent().attr("max_stock"));
    if(input_obj && input_num > 0 && input_num < max_num){
        input_num++;
        input_obj.val(input_num);
        showGoodsNum();
    }
}
//减少
function decreaseGoodsNum(obj){
    var input_obj = $(obj).parent().find("input.hidden_goods_num");
    var input_num = parseInt(input_obj.val());
    if(input_obj && input_num > 1){
        input_num--;
        input_obj.val(input_num);
        showGoodsNum();
    }
}
//全局数量检查
function showGoodsNum(){
    var cart_goods_num_list = $(".cart_list .cart_content_box .cart_goods_info .cart_goods_num");
    $.each(cart_goods_num_list,function(k,v){
        var input_obj = $(v).find("input.hidden_goods_num");
        var input_num = parseInt(input_obj.val());
        var max_num = parseInt($(v).attr("max_stock"));
        if(!(input_num > 0)){
            input_num = 1;
            $(v).find("input.hidden_goods_num").val(1);
        }
        $(v).find(".cart_goods_num_show").html(input_num);
        if(max_num < input_num && $(v).parent().attr("is_stock") == 1){
            $(v).parent().find(".cart_goods_tip").html("库存不足");
        }else{
            $(v).parent().find(".cart_goods_tip").html("");
        }
    });
}


