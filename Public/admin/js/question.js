//活动 - 每日问答JS

//正确答案
function select_is_right(obj){
    $(obj).parent().removeClass("has-error");
    $(obj).parent().removeClass("has-success");
    $("form.add_question_form .option_info").parent().parent().removeClass("has-success");
    var is_right = $(obj).val();
    is_right = parseInt(is_right);
    if(is_right > 0 && is_right < 5){
        $(obj).parent().addClass("has-success");
        $("form.add_question_form #option_"+is_right).parent().parent().addClass("has-success");
    }else{
        $(obj).parent().addClass("has-error");
    }
}