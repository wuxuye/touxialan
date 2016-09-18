//首页相关JS

//拿到公告列表
function loadIndexNotice(){
    var list_obj = $(".public_content_info .index_box .index_notice_box .notice_info_list");
    showNoticeTip("正在获取公告ing...");
    $.ajax({
        url:'/Home/Ajax/ajaxGetIndexNotice',
        type:'POST',
        dataType:'JSON',
        success:function(msg){
            if(msg.state==1){
                hiddenNoticeTip();

                var html = "";
                $.each(msg.list,function(k,v){
                    html += '<div class="notice_info '+ (v.is_top == 1 ? 'active' : '') +'" notice_id="' + v.id + '" ><div class="left_content"><a href="javascript:;">' + v.title_str + '</a></div><div class="right_time">' + v.time_str + '</div></div>'
                });

                if(html == ""){
                    showNoticeTip("木有公告...");
                }else{
                    list_obj.empty();
                    list_obj.html(html);
                    hiddenNoticeTip();
                }

            }else{
                showNoticeTip("公告获取失败");
            }
        },
        error:function(e){
            showNoticeTip("公告获取失败");
        }
    })
}

function showNoticeTip(message){
    var tip_obj = $(".public_content_info .index_box .index_notice_box .notice_info_tip");
    tip_obj.empty();
    tip_obj.html(message);
    tip_obj.removeClass("public_hidden");
}

function hiddenNoticeTip(){
    var tip_obj = $(".public_content_info .index_box .index_notice_box .notice_info_tip");
    tip_obj.empty();
    tip_obj.addClass("public_hidden");
}


//拿到活动列表
function loadIndexActivity(){
    var list_obj = $(".public_content_info .index_box .index_activity_box .activity_info_list");
    showActivityTip("正在获取活动ing...");
    $.ajax({
        url:'/Home/Ajax/ajaxGetIndexActivity',
        type:'POST',
        dataType:'JSON',
        success:function(msg){
            if(msg.state==1){
                hiddenActivityTip();

                var html = "";
                $.each(msg.list,function(k,v){
                    html += '<div class="activity_info" ><a href="' + v.url + '">' + v.title_str + '</a></div>'
                });

                if(html == ""){
                    showActivityTip("木有活动...");
                }else{
                    list_obj.empty();
                    list_obj.html(html);
                    hiddenActivityTip();
                }

            }else{
                showActivityTip("活动获取失败");
            }
        },
        error:function(e){
            showActivityTip("活动获取失败");
        }
    })
}

function showActivityTip(message){
    var tip_obj = $(".public_content_info .index_box .index_activity_box .activity_info_tip");
    tip_obj.empty();
    tip_obj.html(message);
    tip_obj.removeClass("public_hidden");
}

function hiddenActivityTip(){
    var tip_obj = $(".public_content_info .index_box .index_activity_box .activity_info_tip");
    tip_obj.empty();
    tip_obj.addClass("public_hidden");
}
