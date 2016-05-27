//活动 - 每日答题 JS

//改变问题状态
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

//标记次日发布
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

//正确答案
function selectIsRight(obj){
    $(obj).parent().removeClass("has-error");
    $(obj).parent().removeClass("has-success");
    $("form.add_question_form .option_info").parent().parent().removeClass("has-success");
    var is_right = $(obj).val();
    is_right = parseInt(is_right);
    if(is_right > 0 && is_right < 5){
        $(obj).parent().addClass("has-success");
        $("form.add_question_form #option_"+is_right).parent().parent().addClass("has-success");
    }else{
        $(obj).parent().addClass("has-error");
    }
}

//删除图片
function deleteQuestionImage(id){
    if(confirm("确定要删除这个问题的图片信息？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxDeleteQuestionImage',
            type:'POST',
            dataType:'JSON',
            data:'question_id='+id,
            success:function(msg){
                if(msg.state==1){
                    //刷新页面
                    window.location.reload();
                }else{
                    alert(msg.message);
                }
            }
        })
    }
}