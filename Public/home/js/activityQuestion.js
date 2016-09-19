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
        $("div.activity_question_box form input[type='radio']").hide();
        $("div.activity_question_box form button").hide();

        html = "你选择 "+history_answer+"，";
        if(is_right == 1){
            html += "回答正确";
        }else{
            html += "回答错误，明天再来";
        }
    }else if(is_active != 1){
        $("div.activity_question_box form input[type='radio']").hide();
        $("div.activity_question_box form button").hide();

        html = "不在活动时间段内";
    }else{
        html = "请选择一个答案";
    }
    $("div.activity_question_box div.question_info_select_tip").html(html);
}

//用户提交答题信息
function question_submit_answer(){
    var user_select = parseInt($("div.activity_question_box form input[type='radio'][name='user_select']:checked").val());
    if(user_select && user_select > 0 && user_select < 5){
        $("div.activity_question_box form").submit();
    }else{
        public_fill_alert("请先选择一个答案");
    }
}
