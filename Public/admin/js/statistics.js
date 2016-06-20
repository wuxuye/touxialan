//统计JS

//更新统计数据
function updateStatisticsData(){
    if(confirm("确定要 更新统计信息？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxUpdateAttrStatisticsData',
            type:'POST',
            dataType:'JSON',
            success:function(msg){
                alert(msg.message);
                if(msg.state==1){
                    //将这个表单提交
                    location.reload();
                }
            }
        })
    }
}


