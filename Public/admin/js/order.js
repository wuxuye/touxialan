//订单JS

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