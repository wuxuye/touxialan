//订单JS

//确认订单
function confirmOrder(order_id){
    if(confirm("确定要确认这张订单？确认后用户的当前绑定手机号就是确认手机号")){
        order_id = parseInt(order_id);
        if(order_id > 0){

        }else{
            alert("错误的订单id");
        }
    }
}