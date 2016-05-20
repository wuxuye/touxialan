//用户JS

//用户添加
function add_user_form_submit(){
    $.ajax({
        url:'/Admin/Ajax/ajaxAddUser',
        type:'POST',
        dataType:'JSON',
        data:$("div.add_user_box form").serialize(),
        success:function(msg){
            $(".add_attr_box #add_wait").val(0);
            if(msg.state==1){
                alert("添加成功");
                //触发获取层级结构事件
                getAttrList(attr_parent_id);
            }else{
                alert(msg.message);
            }
        }
    })
}

//改变用户状态
function change_user_state(user_id,state){
    if(confirm("确定要改变用户的状态？")){
        var remark = $("#change_user_state_remark").val();
        $.ajax({
            url:'/Admin/Ajax/ajaxChangeUserState',
            type:'POST',
            dataType:'JSON',
            data:'user_id='+user_id+'&state='+state+'&remark='+remark,
            success:function(msg){
                alert(msg.message);
                if(msg.state==1){
                    //刷新页面
                    window.location.reload();
                }
            }
        })
    }
}

//改变用户身份
function change_user_identity(user_id,identity){
    if(confirm("确定要改变用户的身份？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxChangeUserIdentity',
            type:'POST',
            dataType:'JSON',
            data:'user_id='+user_id+'&identity='+identity,
            success:function(msg){
                alert(msg.message);
                if(msg.state==1){
                    //刷新页面
                    window.location.reload();
                }
            }
        })
    }
}

//重置用户重置用安全码
function reset_user_reset_code(user_id){
    if(confirm("确定要重置这个安全码？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxResetUserResetCode',
            type:'POST',
            dataType:'JSON',
            data:'user_id='+user_id,
            success:function(msg){
                alert(msg.message);
                if(msg.state==1){
                    //刷新页面
                    window.location.reload();
                }
            }
        })
    }
}

//删除用户消息记录
function delete_user_message(message_id){
    if(confirm("确定要删除这条消息记录？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxDeleteUserMessage',
            type:'POST',
            dataType:'JSON',
            data:'message_id='+message_id,
            success:function(msg){
                alert(msg.message);
                if(msg.state==1){
                    $(".user_message_box #admin_page_form").submit();
                }
            }
        })
    }
}
