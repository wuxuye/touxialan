//标签JS

var wait_add = 0; //等待标签添加

//添加标签表单提交
function addTagFormSubmit(){
    if(wait_add == 0){
        var tag_name = $("#addTagForm input[name='tag_name']").val();
        if(tag_name){
            if(confirm("确定要添加一个名为：" + tag_name + " 的标签？")){
                wait_add = 1;
                $.ajax({
                    url:'/Admin/Ajax/ajaxTagAdd',
                    type:'POST',
                    dataType:'JSON',
                    data:$("#addTagForm").serialize(),
                    success:function(msg){
                        wait_add = 0;
                        alert(msg.message);
                        if(msg.state==1){
                            window.location.href = "/Admin/Tags/tagsList";
                        }
                    }
                });
            }
        }else{
            alert("请输入一个标签名");
        }
    }else{
        alert("有数据正在处理，请等待");
    }
}

var effect_wait = 0; //效果等待
var button_type = 1; //按钮状态 0 隐藏按钮  1 编辑按钮

//删除标签
function deleteTag(tag_id){
    if(confirm("确定删除这个标签？")){
        tag_id = parseInt(tag_id);
        if(!isNaN(tag_id) && tag_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxDeleteTag',
                type:'POST',
                dataType:'JSON',
                data:'tag_id='+tag_id,
                success:function(msg){
                    if(msg.state==1){
                        //将这个表单提交
                        $(".tag_list_box #admin_page_form").submit();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("商品id错误");
        }
    }
}

//标签列表 （此js须配合页面中的 <div id="tags_list"></div> 与 tag.css样式 ）
//初始化标签
function readyTags(goods_id){
    $("#tags_list .tag_list_top_message").empty();
    $("#tags_list .tag_list_top_message").html("正在加载标签数据ing...");
    //获取商品的标签信息
    $.ajax({
        url:'/Admin/Ajax/ajaxGetTagsListByGoodsId',
        type:'POST',
        dataType:'JSON',
        data:'goods_id='+goods_id,
        success:function(msg){
            if(msg.state==1){
                $("#tags_list .tag_list_top_message").empty();
                $("#tags_list .tag_list_top_message").hide();
                //加载完后将顶部操作显示出来
                $("#tags_list .tag_list_top_box").show();
                //为now_tags_list赋值
                $("#now_tags_list").val(msg.tag_id_json);

                //初始化参数
                effect_wait = 0;
                button_type = 1;

                //触发标签显示逻辑
                showTags();
                //触发搜索逻辑
                searchTags($("#search_tag_input"));
            }else{
                $("#tags_list .tag_list_top_message").html("标签数据初始化失败:"+msg.message);
            }
        }
    })
}

//根据现有标签id显示标签
function showTags(){
    //效果等待中
    effect_wait = 1;
    //现在的标签列表获取
    var now_tags_list = $("#now_tags_list").val();
    //数据转换
    var now_tags_list_obj = $.parseJSON(now_tags_list);
    var now_tags_list_array = $.makeArray(now_tags_list_obj);
    //所有隐藏的标签
    var all_hidden_tags_list = $("#tags_list .tag_list_foot_box .tag_box:hidden");
    var num = 0; //计数
    //淡入标签方法
    function fadeInTag(){
        //根据id列表逐个显示隐藏的标签
        if(now_tags_list_array){
            var this_hide_tag = all_hidden_tags_list.eq(num);
            //没tag_id时结束逻辑
            if(!this_hide_tag.attr("tag_id")){
                effect_wait = 0; //复原效果标记
                button_type = 1; //现在是编辑按钮
                return false;
            }
            num++; //计数增加
            var tag_id = parseInt(this_hide_tag.attr("tag_id"));
            //显示存在于id列表中的标签
            if($.inArray(tag_id,now_tags_list_array)!=-1){
                this_hide_tag.addClass("select");
                this_hide_tag.fadeIn(10,function(){fadeInTag()});
            }else{
                fadeInTag();
            }
        }else{
            effect_wait = 0;
        }
    }
    //开始逻辑
    fadeInTag();
}

//编辑显示标签
function editShowTags(){
    if(effect_wait == 0){
        //效果等待中
        effect_wait = 1;
        //所有隐藏的标签
        var all_hidden_tags_list = $("#tags_list .tag_list_foot_box .tag_box:hidden");
        var num = 0; //计数
        //淡入标签方法
        function fadeInTag(){
            var this_hide_tag = all_hidden_tags_list.eq(num);
            //没tag_id时结束逻辑
            if(!this_hide_tag.attr("tag_id")){
                effect_wait = 0; //复原效果标记
                //隐藏显示按钮 显示隐藏按钮
                $("div#tags_list div.tag_list_top_box div.first_button").hide();
                $("div#tags_list div.tag_list_top_box div.next_button").show();
                button_type = 0; //现在是隐藏按钮
                return false;
            }
            num++; //计数增加
            if(!this_hide_tag.hasClass("disable")){
                this_hide_tag.fadeIn(10,function(){
                    fadeInTag()
                });
            }else{
                fadeInTag();
            }
        }
        //开始逻辑
        fadeInTag();
    }
}

//编辑隐藏标签
function editHideTags(){
    if(effect_wait == 0){
        //效果等待中
        effect_wait = 1;
        //现在的标签列表获取
        var now_tags_list = $("#now_tags_list").val();
        //数据转换
        var now_tags_list_obj = $.parseJSON(now_tags_list);
        var now_tags_list_array = $.makeArray(now_tags_list_obj);
        //所有可见的标签
        var all_visible_tags_list = $("#tags_list .tag_list_foot_box .tag_box:visible");
        var num = all_visible_tags_list.length-1; //计数
        //淡出标签方法
        function fadeOutTag(){
            //根据id列表逐个显示隐藏的标签
            if(now_tags_list_array){
                var this_visible_tag = all_visible_tags_list.eq(num);
                //没tag_id时结束逻辑
                if(!this_visible_tag.attr("tag_id")){
                    effect_wait = 0; //复原效果标记
                    //隐藏隐藏按钮 显示显示按钮
                    $("div#tags_list div.tag_list_top_box div.next_button").hide();
                    $("div#tags_list div.tag_list_top_box div.first_button").show();
                    button_type = 1; //现在是显示按钮
                    return false;
                }
                num--; //计数减少
                var tag_id = parseInt(this_visible_tag.attr("tag_id"));
                //显示存在于id列表中的标签
                if($.inArray(tag_id,now_tags_list_array)==-1){

                    this_visible_tag.removeClass("select");
                    this_visible_tag.fadeOut(10,function(){fadeOutTag()});
                }else{
                    fadeOutTag();
                }
            }else{
                effect_wait = 0;
            }
        }
        //开始逻辑
        fadeOutTag();
    }
}

//标签选择
function selectTags(tag_obj){
    if(effect_wait == 0){
        //现在的标签列表获取
        var now_tags_list = $("#now_tags_list").val();
        //数据转换
        var now_tags_list_obj = $.parseJSON(now_tags_list);
        var now_tags_list_array = $.makeArray(now_tags_list_obj);
        var tag_id = parseInt($(tag_obj).attr("tag_id"));
        if($.inArray(tag_id,now_tags_list_array)!=-1){
            //存在就删除
            $(tag_obj).removeClass("select");
            now_tags_list_array.splice($.inArray(tag_id,now_tags_list_array),1);
            //存入新的json字符串
            $("#now_tags_list").val(JSON.stringify(now_tags_list_array));
        }else{
            //不存在就添加
            $(tag_obj).addClass("select");
            now_tags_list_array.push(tag_id);
            //存入新的json字符串
            $("#now_tags_list").val(JSON.stringify(now_tags_list_array));
        }
    }
}

//标签搜索
function searchTags(input){
    var input_value = $(input).val();
    //拿到所有标签
    var all_tags_list = $("#tags_list .tag_list_foot_box .tag_box");
    //为所有不符合条件 且 没被选中的标签添加禁用class
    $.each(all_tags_list,function(k,v){
        if(!$(v).hasClass("select")){
            var this_html = $(v).html();
            if(this_html.indexOf(input_value)>=0 || input_value.length<=0){
                $(v).removeClass("disable");
                //在现在是隐藏按钮的状态下,如果他们是隐藏的就将他们显示
                if(button_type == 0 && $(v).is(":hidden")){
                    $(v).show();
                }
            }else{
                $(v).addClass("disable");
                //如果他们是显示的就将他们隐藏
                if($(v).is(":visible")){
                    $(v).hide();
                }
            }
        }
    });
}

