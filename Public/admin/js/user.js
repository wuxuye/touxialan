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