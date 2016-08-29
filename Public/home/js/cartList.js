//清单列表JS

//初始化
function initializationCartList(){
    //3类选择框都是未选择
    $(".cart_list .cart_content_box .cart_goods_title .cart_goods_title_select .cart_goods_top_select_input").prop("checked",false);
    $(".cart_list .cart_content_box .cart_goods_info .cart_goods_select .cart_goods_select_input").prop("checked",false);
    $(".cart_list .cart_content_box .cart_goods_footer .cart_goods_footer_select .cart_goods_footer_select_input").prop("checked",false);

    //禁用等判断
    disabledSelect();

    //价格选择
    goodsPayType();

    //全选商品
    $(".cart_list .cart_content_box .cart_goods_title .cart_goods_title_select .cart_goods_top_select_input").prop("checked",true);
    allGoodsSelect($(".cart_list .cart_content_box .cart_goods_title .cart_goods_title_select .cart_goods_top_select_input"));

    //恢复按钮
    recoverOrderButton();

}

//商品选择
//标签禁用 与 数量库存判断
function disabledSelect(){
    var cart_goods_info_list = $(".cart_list .cart_content_box .cart_goods_info");
    $.each(cart_goods_info_list,function(k,v){
        if($(v).attr("goods_can_select") == 1){
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

    statisticsCartGoods();
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
        statisticsCartGoods();
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
        statisticsCartGoods();
    }
}


//支付方式
//初始化页面支付方式
function goodsPayType(){
    //先取消掉所有的付款方式选择
    var cart_goods_price_select_checkbox_list = $(".cart_list .cart_content_box .cart_goods_info .cart_goods_price_select .cart_goods_price_select_checkbox");
    cart_goods_price_select_checkbox_list.prop("checked",false);

    //选中隐藏域中所指示的付款方式
    $.each(cart_goods_price_select_checkbox_list,function(k,v){
        var hidden_pay_type = $(v).parent().find("input.hidden_pay_type").val();
        if(hidden_pay_type != 1 && hidden_pay_type !=2 && $(v).attr("pay_type") == 1){
            $(v).prop("checked",true);
        }else if($(v).attr("pay_type") == hidden_pay_type){
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
    statisticsCartGoods();
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
                        statisticsCartGoods();
                    });
                }else{
                    public_fill_alert(msg.message);
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
                    public_fill_alert(msg.message);
                }
            }
        });
    }
}


//统计
function statisticsCartGoods(){
    var cart_goods_info_list = $(".cart_list .cart_content_box .cart_goods_info");

    var type_num = 0; //总商品种类
    var total_money = 0; //总金额
    var total_point = 0; //总积分

    $.each(cart_goods_info_list,function(k,v){
        if($(v).find(".cart_goods_select .cart_goods_select_input").prop("checked") == true){
            //被选中就增加种类
            type_num++;
            var cart_goods_price_obj = $(v).find(".cart_goods_price");
            var cart_goods_num_obj = $(v).find(".cart_goods_num");
            var goods_num = parseInt(cart_goods_num_obj.find(".cart_goods_num_show").html());
            if(cart_goods_price_obj.hasClass("cart_goods_price_select")){
                //可选支付方式
                var cart_goods_price_select_checkbox_obj = cart_goods_price_obj.find(".cart_goods_price_select_checkbox:checked");
                if(cart_goods_price_select_checkbox_obj.attr("pay_type") == 1){
                    total_money += parseFloat(cart_goods_price_select_checkbox_obj.attr("data_num"))*goods_num;
                }else if(cart_goods_price_select_checkbox_obj.attr("pay_type") == 2){
                    total_point += parseInt(cart_goods_price_select_checkbox_obj.attr("data_num"))*goods_num;
                }else{
                    public_fill_alert("请先正确勾选商品的支付方式。");
                    return false;
                }
            }else{
                //不可选支付方式
                var span_obj = cart_goods_price_obj.find("span");
                if(span_obj.attr("pay_type") == 1){
                    total_money += parseFloat(span_obj.attr("data_num"))*goods_num;
                }else if(span_obj.attr("pay_type") == 2){
                    total_point += parseInt(span_obj.attr("data_num"))*goods_num;
                }else{
                    public_fill_alert("清单数据错误，请刷新页面后重试。");
                    return false;
                }
            }
        }
    });

    var cart_goods_footer = $(".cart_list .cart_content_box .cart_goods_footer");
    cart_goods_footer.find(".cart_goods_footer_goods_num").html(type_num);
    cart_goods_footer.find(".cart_goods_footer_goods_price").html(total_money);
    cart_goods_footer.find(".cart_goods_footer_goods_point").html(total_point);

}

//生成订单
function createOrder(){
    if(confirm("确定要生成订单？")){

        //禁用按钮
        disabledOrderButton();

        //最基础的积分判断
        var point = parseInt($(".cart_list .cart_content_box .cart_goods_footer .cart_goods_footer_goods_point").html());
        var user_point = parseInt($(".cart_list .cart_content_box .cart_goods_footer .cart_goods_footer_user_point").html());

        if(user_point < point){
            public_fill_alert("用户积分不足，请重新选择支付方式","积分不足");
            recoverOrderButton();
        }else{

            //拿到配送时间与地址
            var cart_send_week = $(".cart_list .cart_content_box .cart_time_and_address .cart_time_and_address_table #cart_send_week").val();
            var cart_send_time = $(".cart_list .cart_content_box .cart_time_and_address .cart_time_and_address_table #cart_send_time").val();
            var cart_send_address = $(".cart_list .cart_content_box .cart_time_and_address .cart_time_and_address_table #cart_send_address").val();

            if(!(cart_send_week && cart_send_time && cart_send_address!='')){
                public_fill_alert("请先正确选择和填写<br>配送时间段 与 配送地址","配送时间与地址");
                recoverOrderButton();
            }else{
                //根据页面的数据生成一串订单码
                var code = "";
                var cart_goods_info_list = $(".cart_list .cart_content_box .cart_goods_info");
                $.each(cart_goods_info_list,function(k,v){
                    if($(v).find(".cart_goods_select .cart_goods_select_input").prop("checked") == true){
                        var cart_goods_price_obj = $(v).find(".cart_goods_price");
                        var cart_goods_num_obj = $(v).find(".cart_goods_num");

                        var cart_id = $(v).attr("cart_id");
                        var goods_num = parseInt(cart_goods_num_obj.find(".cart_goods_num_show").html());
                        var pay_type = 1;//默认支付方式

                        if(cart_goods_price_obj.hasClass("cart_goods_price_select")){
                            //可选支付方式
                            var cart_goods_price_select_checkbox_obj = cart_goods_price_obj.find(".cart_goods_price_select_checkbox:checked");
                            if(cart_goods_price_select_checkbox_obj.attr("pay_type") == 1){
                                pay_type = 1;
                            }else if(cart_goods_price_select_checkbox_obj.attr("pay_type") == 2){
                                pay_type = 2;
                            }
                        }else{
                            //不可选支付方式
                            var span_obj = cart_goods_price_obj.find("span");
                            if(span_obj.attr("pay_type") == 1){
                                pay_type = 1;
                            }else if(span_obj.attr("pay_type") == 2){
                                pay_type = 2;
                            }
                        }

                        //数据连接
                        code += "d"+cart_id+"n"+goods_num+"t"+pay_type;
                    }
                });

                if(code != ""){
                    $.ajax({
                        url:'/Home/Ajax/ajaxCreateOrder',
                        type:'POST',
                        dataType:'JSON',
                        data:"code="+code+'&cart_send_week='+cart_send_week+'&cart_send_time='+cart_send_time+'&cart_send_address='+cart_send_address,
                        success:function(msg){
                            if(msg.state==1){
                                //跳转去订单确认页
                                window.location.href = "/Home/Order/orderInfo/order_id/"+msg.order_id;
                            }else{
                                public_fill_alert(msg.message,msg.tip_title);
                            }
                            recoverOrderButton();
                        },
                        error:function(e){
                            public_fill_alert("系统繁忙，请稍后再试");
                            recoverOrderButton();
                        }
                    });
                }else{
                    public_fill_alert("请先选择需要购买的商品");
                    recoverOrderButton();
                }
            }
        }
    }
}

//恢复生成订单按钮
function recoverOrderButton(){
    var submit_button = $(".cart_list .cart_content_box .cart_goods_footer .cart_goods_footer_operation .cart_goods_footer_operation_button");
    submit_button.attr("disabled",false);
    submit_button.removeClass("disable_button");
    submit_button.html("生成订单");
}

//禁用生成订单按钮
function disabledOrderButton(){
    var submit_button = $(".cart_list .cart_content_box .cart_goods_footer .cart_goods_footer_operation .cart_goods_footer_operation_button");
    submit_button.attr("disabled",true);
    submit_button.addClass("disable_button");
    submit_button.html("处理ing");
}

