//公告JS

//置顶公告
function topNotice(notice_id){
    if(confirm("确定置顶这篇文章？")){
        notice_id = parseInt(notice_id);
        if(!isNaN(notice_id) && notice_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxTopNotice',
                type:'POST',
                dataType:'JSON',
                data:'id='+notice_id,
                success:function(msg){
                    if(msg.state==1){
                        //将这个表单提交
                        $(".notice_list_box #admin_page_form").submit();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("公告id错误");
        }
    }
}

//取消置顶
function cancelTop(notice_id){
    if(confirm("确定取消这篇文章的置顶？")){
        notice_id = parseInt(notice_id);
        if(!isNaN(notice_id) && notice_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxCancelTop',
                type:'POST',
                dataType:'JSON',
                data:'id='+notice_id,
                success:function(msg){
                    if(msg.state==1){
                        //将这个表单提交
                        $(".notice_list_box #admin_page_form").submit();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("公告id错误");
        }
    }
}

//删除公告
function deleteNotice(notice_id){
    if(confirm("确定删除这篇文章？")){
        notice_id = parseInt(notice_id);
        if(!isNaN(notice_id) && notice_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxDeleteNotice',
                type:'POST',
                dataType:'JSON',
                data:'id='+notice_id,
                success:function(msg){
                    if(msg.state==1){
                        //将这个表单提交
                        $(".notice_list_box #admin_page_form").submit();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("公告id错误");
        }
    }
}
