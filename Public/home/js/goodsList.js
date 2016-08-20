//商品列表相关JS
function toAnchor(attr_id){
    location.href = '#attr_'+attr_id;
}

//商品添加至购物车
function addCart(obj){
    var can_cart = $(obj).attr("can_cart");
    var has_cart = $(obj).attr("has_cart");
    if(can_cart == 1 && has_cart == 0){
        if(confirm("要将这个商品添加至您的清单？")){
            var goods_id = $(obj).attr("goods_id");
            $.ajax({
                url:'/Home/Ajax/ajaxAddGoodsToUserCart',
                type:'POST',
                dataType:'JSON',
                data:'goods_id='+goods_id,
                success:function(msg){
                    if(msg.state==1){
                        $(obj).addClass("goods_green_border");
                        $(obj).attr("has_cart",1);
                    }else{
                        public_fill_alert(msg.message);
                    }
                },
                error:function(e){
                    public_fill_alert("数据处理失败，请刷新页面后重试");
                }
            })
        }
    }
}
