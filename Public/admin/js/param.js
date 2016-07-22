//参数管理JS

//添加一栏属性显示
function addShowAttr(){
    var html = '';
}

//根据属性id获取属性名称
function changeAttrName(obj,attr_id){
    $.ajax({
        url:'/Admin/Ajax/ajaxGetAttrNameById',
        type:'POST',
        dataType:'JSON',
        data:'attr_id='+attr_id,
        success:function(msg){
            if(msg.state==1){
                //改变后面的文字显示
                $(obj).parent().find(".attr_show_name").html(msg.name);
            }else{
                alert(msg.message);
            }
        }
    })
}

//上移数据
function moveUp(obj){

}

//下移数据
function moveDown(obj){

}
