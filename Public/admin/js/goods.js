//商品JS

//上架商品
function shelveGoods(goods_id){
    if(confirm("确定上架这个商品？")){
        goods_id = parseInt(goods_id);
        if(!isNaN(goods_id) && goods_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxShelveGoods',
                type:'POST',
                dataType:'JSON',
                data:'goods_id='+goods_id,
                success:function(msg){
                    if(msg.state==1){
                        //将这个表单提交
                        $(".goods_list_box #admin_page_form").submit();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("商品id错误");
        }
    }
}

//下架商品
function unShelveGoods(goods_id){
    if(confirm("确定下架这个商品？")){
        goods_id = parseInt(goods_id);
        if(!isNaN(goods_id) && goods_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxUnshelveGoods',
                type:'POST',
                dataType:'JSON',
                data:'goods_id='+goods_id,
                success:function(msg){
                    if(msg.state==1){
                        //将这个表单提交
                        $(".goods_list_box #admin_page_form").submit();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("商品id错误");
        }
    }
}

//删除商品
function deleteGoods(goods_id){
    if(confirm("确定删除这个商品？")){
        goods_id = parseInt(goods_id);
        if(!isNaN(goods_id) && goods_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxDeleteGoods',
                type:'POST',
                dataType:'JSON',
                data:'goods_id='+goods_id,
                success:function(msg){
                    if(msg.state==1){
                        //将这个表单提交
                        $(".goods_list_box #admin_page_form").submit();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("商品id错误");
        }
    }
}

