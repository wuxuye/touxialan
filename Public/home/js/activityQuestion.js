//活动 - 每日问答 相关JS

//题目展示页数据准备
function question_show_ready(){
    var is_answer = parseInt($("div.activity_question_box #is_answer").val());
    var history_answer = parseInt($("div.activity_question_box #history_answer").val());
    var is_right = parseInt($("div.activity_question_box #is_right").val());
    var is_active = parseInt($("div.activity_question_box #is_active").val());
    var html = "";
    //用户已经做过题目 或 不在时间段内，就隐藏掉相关元素
    if(is_answer == 1){
        var user_select_obj = $("div.activity_question_box div.question_info_select_list").find(".question_info_select_option[option_key='"+history_answer+"']");
        if(user_select_obj){
            html = "你选择 "+history_answer+"，";
            if(is_right == 1){
                html += "回答正确，66666~";
                user_select_obj.addClass("active_right");
            }else{
                html += "回答错误，很遗憾~明天再来嗨吧。";
                user_select_obj.addClass("active_wrong");
            }
            $("div.activity_question_box .question_info_box .question_info_button button").hide();
        }
    }else if(is_active != 1){
        html = "不在活动时间段内";
        $("div.activity_question_box .question_info_box .question_info_button button").hide();
    }else{
        html = "请选择一个答案";
    }
    question_show_tip(html);
}

//用户提交答题信息
var wait = 0;
function question_submit_answer(){
    var user_select = parseInt($("div.activity_question_box .question_info_box input#user_select").val());
    if(user_select && user_select > 0 && user_select < 5){
        if(wait == 0){
            wait = 1;
            question_show_tip("数据提交ing...");
            $.ajax({
                url:'/Home/Ajax/ajaxActivityQuestionUserSelect',
                type:'POST',
                dataType:'JSON',
                data:"user_select="+user_select,
                success:function(msg){
                    if(msg.state==1){
                        public_fill_alert("答案提交成功","","success");
                        window.location.reload();
                    }else{
                        question_show_tip(msg.message);
                    }
                    wait = 0;
                },
                error:function(e){
                    public_fill_alert("系统繁忙，请稍后再试");
                    wait = 0;
                }
            });
        }else{
            question_show_tip("正在处理您的请求，请稍后~");
        }
    }else{
        question_show_tip("请先选择一个答案");
    }
}

//提示信息显示
function question_show_tip(msg){
    $("div.activity_question_box div.question_info_select_tip").empty();
    if(msg.length>0){
        $("div.activity_question_box div.question_info_select_tip").html(msg);
    }
}

//答案点击事件
function question_user_select(obj){
    var is_answer = parseInt($("div.activity_question_box #is_answer").val());
    var is_active = parseInt($("div.activity_question_box #is_active").val());
    //活动时间内 且 用户未作答 这种情况下可选择
    if(is_active == 1 && is_answer == 0){
        var select_key = $(obj).attr("option_key");
        if(select_key && select_key > 0 && select_key < 5){
            $("div.activity_question_box div.question_info_select_list div.question_info_select_option").removeClass("active_right");
            $(obj).addClass("active_right");
            $("div.activity_question_box #user_select").val(select_key);
        }

    }
}
