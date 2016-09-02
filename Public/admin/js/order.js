//订单JS

/* 订单统一操作页的相关JS */
//筛选框初始化
function initializeSelectDiv(){
    $(".order_unified_operation_box .order_unified_operation_statistics_list .select_div").removeClass("active");
    $(".order_unified_operation_box #select_sort_id").val(0);
    $(".order_unified_operation_box .order_unified_operation_doing_box .order_unified_operation_text_div #operation_id").val("");
}
//筛选框选择
function selectDiv(obj){
    initializeSelectDiv();
    var sort_id = $(obj).attr("sort_id");
    $(".order_unified_operation_box #select_sort_id").val(sort_id);
    if(sort_id > 0){
        autoAddData();
    }
}
//自动填写数据
function autoAddData(){
    var sort_id = $(".order_unified_operation_box #select_sort_id").val();
    var select_div_obj = $(".order_unified_operation_box .order_unified_operation_statistics_list .select_div[sort_id='"+sort_id+"']");
    select_div_obj.addClass("active");
    var id_list = select_div_obj.find("input.hidden_id_list").val();
    if(id_list.length>0){
        $(".order_unified_operation_box .order_unified_operation_doing_box .order_unified_operation_text_div #operation_id").val(id_list);
    }
}
//待发货订单转配送中订单
function sendOrder(){
    var id_list = $(".order_unified_operation_box .order_unified_operation_doing_box .order_unified_operation_text_div #operation_id").val();
    if(id_list.length>0){
        if(confirm("确定要将订单id为： "+id_list+" 的这些订单的订单状态，转变为配送中？")){
            $.ajax({
                url:'/Admin/Ajax/ajaxToDelivery',
                type:'POST',
                dataType:'JSON',
                data:'order_id='+id_list,
                success:function(msg){
                    if(msg.state==1){
                        alert("操作完成！");
                    }else{
                        alert(msg.message);
                    }
                    //刷新这个页面
                    window.location.reload();
                },
                error:function(e){
                    alert("系统繁忙");
                }
            })
        }
    }else{
        alert("请现在上面的操作框中正确填写要操作的订单id！");
    }
}
//配送中订单转已完成订单
function successOrder(){
    var id_list = $(".order_unified_operation_box .order_unified_operation_doing_box .order_unified_operation_text_div #operation_id").val();
    if(id_list.length>0){
        if(confirm("确定要将订单id为： "+id_list+" 的这些订单的订单状态，转变为待结算 或 已完成？")){
            $.ajax({
                url:'/Admin/Ajax/ajaxSuccessOrder',
                type:'POST',
                dataType:'JSON',
                data:'order_id='+id_list,
                success:function(msg){
                    if(msg.state==1){
                        alert("操作完成！");
                    }else{
                        alert(msg.message);
                    }
                    //刷新这个页面
                    window.location.reload();
                },
                error:function(e){
                    alert("系统繁忙");
                }
            })
        }
    }else{
        alert("请现在上面的操作框中正确填写要操作的订单id！");
    }
}

/* 订单详情页的相关JS */
//确认订单
function confirmOrder(order_id){
    if(confirm("确定要确认这张订单？确认后用户的当前绑定手机号就是确认手机号")){
        order_id = parseInt(order_id);
        if(order_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxConfirmOrder',
                type:'POST',
                dataType:'JSON',
                data:'order_id='+order_id,
                success:function(msg){
                    if(msg.state==1){
                        //刷新这个页面
                        window.location.reload();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("错误的订单id");
        }
    }
}
//确认付款
function confirmPay(order_id){
    if(confirm("确定要确认这张订单的付款？待付款订单会被直接确认")){
        order_id = parseInt(order_id);
        if(order_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxConfirmPay',
                type:'POST',
                dataType:'JSON',
                data:'order_id='+order_id,
                success:function(msg){
                    if(msg.state==1){
                        //刷新这个页面
                        window.location.reload();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("错误的订单id");
        }
    }
}