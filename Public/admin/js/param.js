//参数管理JS

//添加一栏属性显示
function addShowAttr(){
    var html = '<div class="attr_show">栏目名：<input type="text" class="attr_show_title" name="attr_show_title[]" />属性值：<input type="text" class="attr_show_id" name="attr_show_id[]" onchange="changeAttrName(this,this.value)"  />(<span class="attr_show_name">选择属性</span>)<a href="javascript:;" onclick="moveUp(this)">上移</a><a href="javascript:;" onclick="moveDown(this)">下移</a></div>';
    $("#column_list").append(html);
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
    $this_div = $(obj).parent();
    $this_div_html = $this_div.html();
    $this_div_title_value = $this_div.find("input[name='attr_show_title[]']").val();
    $this_div_id_value = $this_div.find("input[name='attr_show_id[]']").val();
    $prev_div = $this_div.prev();
    if($prev_div.length>0){
        $prev_div_html = $prev_div.html();
        $prev_div_title_value = $prev_div.find("input[name='attr_show_title[]']").val();
        $prev_div_id_value = $prev_div.find("input[name='attr_show_id[]']").val();
        //交换内容与值
        $this_div.html($prev_div_html);
        $this_div.find("input[name='attr_show_title[]']").val($prev_div_title_value);
        $this_div.find("input[name='attr_show_id[]']").val($prev_div_id_value);
        $prev_div.html($this_div_html);
        $prev_div.find("input[name='attr_show_title[]']").val($this_div_title_value);
        $prev_div.find("input[name='attr_show_id[]']").val($this_div_id_value);

    }
}

//下移数据
function moveDown(obj){
    $this_div = $(obj).parent();
    $this_div_html = $this_div.html();
    $this_div_title_value = $this_div.find("input[name='attr_show_title[]']").val();
    $this_div_id_value = $this_div.find("input[name='attr_show_id[]']").val();
    $next_div = $this_div.next();
    if($next_div.length>0){
        $next_div_html = $next_div.html();
        $next_div_title_value = $next_div.find("input[name='attr_show_title[]']").val();
        $next_div_id_value = $next_div.find("input[name='attr_show_id[]']").val();
        //交换内容与值
        $this_div.html($next_div_html);
        $this_div.find("input[name='attr_show_title[]']").val($next_div_title_value);
        $this_div.find("input[name='attr_show_id[]']").val($next_div_id_value);
        $next_div.html($this_div_html);
        $next_div.find("input[name='attr_show_title[]']").val($this_div_title_value);
        $next_div.find("input[name='attr_show_id[]']").val($this_div_id_value);
    }
}
