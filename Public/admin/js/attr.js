//属性JS

//根据属性id获取层级
function getAttrList(attr_id){
    attr_id = parseInt(attr_id);
    if(attr_id > 0 || !isNaN(attr_id)){

        //初始化
        $("#attr_list").html("数据获取中ing...");

        $.ajax({
            url:'/Admin/Ajax/ajaxGetAttrList',
            type:'POST',
            dataType:'JSON',
            data:'attr_id='+attr_id,
            success:function(msg){
                if(msg.state==1){
                    var attr_list = msg.attr_list;
                    //用这个属性列表生成select下拉菜单
                    createSelect(attr_list);
                }else{
                    $("#attr_list").html(msg.message);
                    $("#attr_parent_id").val(0);
                }
            }
        })
    }else{
        $("#attr_list").html("属性id错误");
    }
}

//根据列表创建select下拉菜单
function createSelect(list_obj){
    var select_str = "";
    var now_parent_id = 0;
    $.each(list_obj,function(k,v){
        if(v && v.length>0){
            select_str += "<select onchange='changeAttrSelect(this)' >";
            //内容组装
            var option_str = "";
            var last_parent_id = 0;
            $.each(v,function(vk,vv){
                last_parent_id = vv.parent_id;
                option_str += "<option value='"+vv.id+"' ";
                if(vv.select == 1){
                    now_parent_id = vv.id;
                    option_str += " selected='selected' ";
                }
                option_str += ">"+vv.attr_name+"</option>"
            });
            option_str = "<option value='"+last_parent_id+"'>请选择</option>" + option_str;
            select_str = select_str + option_str + "</select>";
        }
    });
    $("#attr_list").html(select_str);
    $("#attr_parent_id").val(now_parent_id);
}

//下拉菜单改变时重新读取新的菜单
function changeAttrSelect(obj){
    var select_value = $(obj).val();
    $("#attr_parent_id").val(select_value);
    getAttrList(select_value);
}

//添加属性提交
function submitToAddAttr(){
    var attr_name = $(".add_attr_box .add_attr_input_div input[name='attr_name']").val();
    var attr_parent_id = parseInt($(".add_attr_box .add_attr_input_div input[name='attr_parent_id']").val());
    var add_wait = $(".add_attr_box .add_attr_input_div #add_wait").val();
    if(add_wait == 0){
        $.ajax({
            url:'/Admin/Ajax/ajaxAddAttr',
            type:'POST',
            dataType:'JSON',
            data:'attr_name='+attr_name+'&attr_parent_id='+attr_parent_id,
            success:function(msg){
                $(".add_attr_box .add_attr_input_div #add_wait").val(0);
                if(msg.state==1){
                    alert("添加成功");
                    //触发获取层级结构事件
                    getAttrList(attr_parent_id);
                }else{
                    alert(msg.message);
                }
            }
        })
    }else{
        alert("数据正在提交，请稍后");
    }
}

//删除属性
function deleteAttr(attr_id,obj){
    if(confirm("确定删除这个属性？")){
        attr_id = parseInt(attr_id);
        if(attr_id > 0 || !isNaN(attr_id)){
            $.ajax({
                url:'/Admin/Ajax/ajaxDeleteAttr',
                type:'POST',
                dataType:'JSON',
                data:'attr_id='+attr_id,
                success:function(msg){
                    if(msg.state==1){
                        //去掉这个属性
                        $(obj).parent().parent().remove();
                        //这个层级下没属性了就跳到上个层级
                        if(msg.is_empty == 1 && msg.parent_parent_id > 0){
                            window.location.href = "/Admin/Attr/attrList/parent_id/"+msg.parent_parent_id;
                        }
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("属性值错误");
        }
    }
}
