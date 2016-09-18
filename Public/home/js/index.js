//首页相关JS

//拿到公告列表
function loadIndexNotice(){
    var list_obj = $(".public_content_info .index_box .index_notice_box .notice_info_list");
    showTip("正在获取公告ing...");
    $.ajax({
        url:'/Home/Ajax/ajaxGetIndexNotice',
        type:'POST',
        dataType:'JSON',
        success:function(msg){
            if(msg.state==1){
                hiddenTip();

                var html = "";
                $.each(msg.list,function(k,v){
                    html += '<div class="notice_info '+ (v.is_top == 1 ? 'active' : '') +'" notice_id="' + v.id + '" ><div class="left_content"><a href="javascript:;">' + v.title_str + '</a></div><div class="right_time">' + v.time_str + '</div></div>'
                });

                if(html == ""){
                    showTip("木有公告...");
                }else{
                    list_obj.empty();
                    list_obj.html(html);
                    hiddenTip();
                }

            }else{
                showTip("公告获取失败");
            }
        },
        error:function(e){
            showTip("公告获取失败");
        }
    })
}

function showTip(message){
    var tip_obj = $(".public_content_info .index_box .index_notice_box .notice_info_tip");
    tip_obj.empty();
    tip_obj.html(message);
    tip_obj.removeClass("public_hidden");
}

function hiddenTip(){
    var tip_obj = $(".public_content_info .index_box .index_notice_box .notice_info_tip");
    tip_obj.empty();
    tip_obj.addClass("public_hidden");
}