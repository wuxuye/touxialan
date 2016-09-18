//活动JS

//删除公告
function deleteActivity(activity_id){
    if(confirm("确定删除这个活动？")){
        activity_id = parseInt(activity_id);
        if(!isNaN(activity_id) && activity_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxDeleteActivity',
                type:'POST',
                dataType:'JSON',
                data:'id='+activity_id,
                success:function(msg){
                    if(msg.state==1){
                        //将这个表单提交
                        $(".activity_list_box #admin_page_form").submit();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("活动id错误");
        }
    }
}
