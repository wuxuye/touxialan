//活动JS

//每日问答 - 改变问题状态
function updateQuestionState(id,state){
    if(confirm("确定要改变这条题目数据的状态信息？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxUpdateQuestionState',
            type:'POST',
            dataType:'JSON',
            data:'question_id='+id+'&state='+state,
            success:function(msg){
                if(msg.state==1){
                    //将这个表单提交
                    $(".question_list_box #admin_page_form").submit();
                }else{
                    alert(msg.message);
                }
            }
        })
    }
}

//每日问答 - 标记次日发布
function isNextPublish(id){
    if(confirm("只会有一道题能被标记为次日发布，确定要这样操作？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxIsNextPublish',
            type:'POST',
            dataType:'JSON',
            data:'question_id='+id,
            success:function(msg){
                if(msg.state==1){
                    //将这个表单提交
                    $(".question_list_box #admin_page_form").submit();
                }else{
                    alert(msg.message);
                }
            }
        })
    }
}