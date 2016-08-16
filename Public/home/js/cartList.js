//清单列表JS

//初始化
function initializationCartList(){
    //3类选择框都是未选择
    $(".cart_list .cart_content_box .cart_goods_title .cart_goods_title_select .cart_goods_top_select_input").prop("checked",false);
    $(".cart_list .cart_content_box .cart_goods_info .cart_goods_select .cart_goods_select_input").prop("checked",false);
    $(".cart_list .cart_content_box .cart_goods_footer .cart_goods_footer_select .cart_goods_footer_select_input").prop("checked",false);

    //禁用等判断
    disabledSelect();

    //全选商品
    $(".cart_list .cart_content_box .cart_goods_title .cart_goods_title_select .cart_goods_top_select_input").prop("checked",true);
    allGoodsSelect($(".cart_list .cart_content_box .cart_goods_title .cart_goods_title_select .cart_goods_top_select_input"));

    //价格选择
    goodsPayType();
}

//商品选择
//标签禁用 与 数量库存判断
function disabledSelect(){
    var cart_goods_info_list = $(".cart_list .cart_content_box .cart_goods_info");
    $.each(cart_goods_info_list,function(k,v){
        if($(v).attr("is_stock") == 1){
            //数量与库存的判断
            var input_num = parseInt($(v).find(".cart_goods_num input.hidden_goods_num").val());
            var max_stock = parseInt($(v).find(".cart_goods_num").attr("max_stock"));
            if(!(input_num > 0)){
                input_num = 1;
                $(v).find(".cart_goods_num input.hidden_goods_num").val(1);
            }
            $(v).find(".cart_goods_num .cart_goods_num_show").html(input_num);
            if(max_stock < input_num){
                $(v).find(".cart_goods_tip").html("库存不足");
                //禁用掉选择框
                $(v).find(".cart_goods_select .cart_goods_select_input").prop("disabled",true);
            }else{
                $(v).find(".cart_goods_tip").html("");
                //开启选择框
                $(v).find(".cart_goods_select .cart_goods_select_input").prop("disabled",false);
            }
        }else{
            //禁用掉选择框
            $(v).find(".cart_goods_select .cart_goods_select_input").prop("disabled",true);
        }
    });
}
//全选商品
function allGoodsSelect(obj){
    var cart_goods_select_input = $(".cart_list .cart_content_box .cart_goods_info .cart_goods_select .cart_goods_select_input");
    if($(obj).prop("checked")==true){
        //全选所有没被禁用的选择框
        $.each(cart_goods_select_input,function(k,v){
            if($(v).prop("disabled")==false){
                $(v).prop("checked",true);
            }else{
                //取消掉禁用的勾选
                $(v).prop("checked",false);
            }
        });
        //上下两个全选框变为选择
        $(".cart_list .cart_content_box .cart_goods_title .cart_goods_title_select .cart_goods_top_select_input").prop("checked",true);
        $(".cart_list .cart_content_box .cart_goods_footer .cart_goods_footer_select .cart_goods_footer_select_input").prop("checked",true);
    }else{
        //清空选择
        cart_goods_select_input.prop("checked",false);
        //上下两个全选框取消选择
        $(".cart_list .cart_content_box .cart_goods_title .cart_goods_title_select .cart_goods_top_select_input").prop("checked",false);
        $(".cart_list .cart_content_box .cart_goods_footer .cart_goods_footer_select .cart_goods_footer_select_input").prop("checked",false);
    }
}

//商品数量加减
//增加
function addGoodsNum(obj){
    var input_obj = $(obj).parent().find("input.hidden_goods_num");
    var input_num = parseInt(input_obj.val());
    var max_num = parseInt($(obj).parent().attr("max_stock"));
    if(input_obj && input_num > 0 && input_num < max_num){
        input_num++;
        input_obj.val(input_num);
        disabledSelect();
    }else if(input_num == max_num){
        $(obj).parent().parent().find(".cart_goods_tip").html("已到达最大上限");
    }
}
//减少
function decreaseGoodsNum(obj){
    var input_obj = $(obj).parent().find("input.hidden_goods_num");
    var input_num = parseInt(input_obj.val());
    if(input_obj && input_num > 1){
        input_num--;
        input_obj.val(input_num);
        disabledSelect();
    }
}


//支付方式
//初始化页面支付方式
function goodsPayType(){
    //先取消掉所有的付款方式选择
    var cart_goods_price_select_checkbox_list = $(".cart_list .cart_content_box .cart_goods_info .cart_goods_price_select .cart_goods_price_select_checkbox");
    cart_goods_price_select_checkbox_list.prop("checked",false);
    //默认选中现金付
    $.each(cart_goods_price_select_checkbox_list,function(k,v){
        if($(v).attr("pay_type") == 1){
            $(v).prop("checked",true);
        }
    });
}

//选择付款方式
function selectPayType(obj){
    var pay_type = $(obj).attr("pay_type");
    var cart_goods_price_select_checkbox = $(obj).parent().find(".cart_goods_price_select_checkbox");
    $.each(cart_goods_price_select_checkbox,function(k,v){
        if($(v).attr("pay_type") == pay_type){
            $(v).prop("checked",true);
        }else{
            $(v).prop("checked",false);
        }
    });
}

//删除清单商品
function deleteCartGoods(obj){
    if(confirm("确定要删除清单中的这个商品？")){
        var cart_obj = $(obj).parent().parent();
        var cart_id = cart_obj.attr("cart_id");
        $.ajax({
            url:'/Home/Ajax/ajaxDeleteCartGoods',
            type:'POST',
            dataType:'JSON',
            data:"cart_id="+cart_id,
            success:function(msg){
                if(msg.state==1){
                    cart_obj.fadeOut("fast",function(){
                        cart_obj.remove();
                    });
                }else{
                    alert(msg.message);
                }
            }
        });
    }
}

//清空清单列表
function deleteAllCartGoods(){
    if(confirm("确定要删除清单中全部的商品数据？")){
        $.ajax({
            url:'/Home/Ajax/ajaxDeleteAllCartGoods',
            type:'POST',
            dataType:'JSON',
            success:function(msg){
                if(msg.state==1){
                    location.reload();
                }else{
                    alert(msg.message);
                }
            }
        });
    }
}


