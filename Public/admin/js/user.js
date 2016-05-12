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