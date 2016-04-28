//标签JS

//删除标签
function deleteTag(tag_id){
    if(confirm("确定删除这个标签？")){
        tag_id = parseInt(tag_id);
        if(!isNaN(tag_id) && tag_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxDeleteTag',
                type:'POST',
                dataType:'JSON',
                data:'tag_id='+tag_id,
                success:function(msg){
                    if(msg.state==1){
                        //将这个表单提交
                        $(".tag_list_box #admin_page_form").submit();
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

