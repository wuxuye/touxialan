//活动 - 每日答题 JS

//改变问题状态
function updateQuestionState(id,state){
    if(confirm("确定要改变这条题目数据的状态信息？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxUpdateQuestionState',
            type:'POST',
            dataType:'JSON',
            data:'question_id='+id+'&state='+state,
            success:function(msg){
                if(msg.state==1){
                    //将这个表单提交
                    $(".question_list_box #admin_page_form").submit();
                }else{
                    alert(msg.message);
                }
            }
        })
    }
}

//标记次日发布
function isNextPublish(id){
    if(confirm("只会有一道题能被标记为次日发布，确定要这样操作？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxIsNextPublish',
            type:'POST',
            dataType:'JSON',
            data:'question_id='+id,
            success:function(msg){
                if(msg.state==1){
                    //将这个表单提交
                    $(".question_list_box #admin_page_form").submit();
                }else{
                    alert(msg.message);
                }
            }
        })
    }
}

//正确答案
function selectIsRight(obj){
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

//删除图片
function deleteQuestionImage(id){
    if(confirm("确定要删除这个问题的图片信息？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxDeleteQuestionImage',
            type:'POST',
            dataType:'JSON',
            data:'question_id='+id,
            success:function(msg){
                if(msg.state==1){
                    //刷新页面
                    window.location.reload();
                }else{
                    alert(msg.message);
                }
            }
        })
    }
}

//问题统计数据获取初始化方法
function getStatisticsReady(){
    var level = $("#statistics_level").val();
    var time = $("#statistics_time").val();
    $(".statistics_box .statistics_box_head").empty();
    $(".statistics_box .statistics_box_content").empty();
    getStatisticsData(level,time);
}

//问题统计数据获取
function getStatisticsData(level,time){
    var old_html = $(".statistics_box .statistics_box_head").html();
    $(".statistics_box .statistics_box_head").html("数据获取ing...");
    $.ajax({
        url:'/Admin/Ajax/ajaxGetStatisticsData',
        type:'POST',
        dataType:'JSON',
        data:'level='+level+'&time='+time,
        success:function(msg){
            if(msg.state==1){
                $(".statistics_box .statistics_box_content").empty();
                var data = msg.data;
                var temp = "";
                if(data.level == 1){ //年列表
                    $(".statistics_box .statistics_box_head").html("年份列表");
                    $.each(data.statistics,function(k,v){
                        var no_num_class = "";
                        if(parseInt(v) < 1){
                            no_num_class = "no_num";
                        }
                        temp += '<div class="time_box '+no_num_class+'" title="答题总次数：'+v+'，点击查看 '+k+'年 的月列表" onclick="getStatisticsData(2,'+k+')" ><div class="time_box_head">'+k+'年</div><div class="time_box_content"><span class="small">答题次数：</span><span class="big">'+v+'</span></div></div>';
                    });
                }else if(data.level == 2){ //月列表
                    $(".statistics_box .statistics_box_head").html("<span class='operation' onclick='getStatisticsData(1,0)'>返回年列表</span><span>"+data.year+"年 月报表</span>");
                    $.each(data.statistics,function(k,v){
                        var no_num_class = "";
                        if(parseInt(v.num) < 1){
                            no_num_class = "no_num";
                        }
                        temp += '<div class="time_box '+no_num_class+'" title="答题总次数：'+ v.num +'，点击查看 '+ v.month+'月 的日列表" onclick="getStatisticsData(3,\''+data.year+'-'+ v.month+'\')" ><div class="time_box_head">'+ v.month+'月</div><div class="time_box_content"><span class="small">答题次数：</span><span class="big">'+ v.num+'</span></div></div>';
                    });
                }else if(data.level == 3){ //日列表
                    var head = "<span class='operation' onclick='getStatisticsData(2,\""+data.year+"\")'>返回月列表</span>";
                    //上个月
                    var last_month = parseInt(data.month) - 1;
                    if(last_month > 0){
                        if(last_month < 10){
                            last_month = "0"+last_month;
                        }
                        head += "<span class='operation' onclick='getStatisticsData(3,\""+data.year+"-"+last_month+"\")'>上个月</span>";
                    }
                    head += "<span>"+data.year+"年"+data.month+"月 日报表</span>";
                    //下个月
                    var next_month = parseInt(data.month) + 1;
                    if(next_month > 0 && next_month < 13){
                        if(next_month < 10){
                            next_month = "0"+next_month;
                        }
                        head += "<span class='operation' onclick='getStatisticsData(3,\""+data.year+"-"+next_month+"\")'>下个月</span>";
                    }
                    $(".statistics_box .statistics_box_head").html(head);
                    $.each(data.statistics,function(k,v){
                        var question_info = "";
                        if(parseInt(v.question_id) > 0){
                            question_info += "\n\r题目id：" + parseInt(v.question_id);
                            question_info += "\n\r题目：" + v.question_info.question_content;
                            $.each(v.question_info.option_info_result.option,function(qk,qv){
                                var select_num = 0;
                                if(v.answer_statistics[qk] && v.answer_statistics[qk]['count'] && parseInt(v.answer_statistics[qk]['count']) > 0){
                                    select_num = parseInt(v.answer_statistics[qk]['count']);
                                }
                                question_info += "\n\r("+select_num+"人)";
                                question_info += "选项" + qk;
                                if(parseInt(qk) == parseInt(v.question_info.option_info_result.is_right)){
                                    question_info += "(正确)";
                                }
                                question_info += "：" + qv;
                            });
                        }
                        var no_num_class = "";
                        if(parseInt(v.count) < 1){
                            no_num_class = "no_num";
                            if(parseInt(v.question_id) > 0){
                                no_num_class += " no_num_has_question";
                            }
                        }else{
                            no_num_class = "has_num";
                        }
                        temp += '<div class="time_box '+no_num_class+'" title="参与人数：'+ v.count + question_info +'" ><div class="time_box_head">'+data.month+'月'+ v.day +'</div><div class="time_box_content"><span class="small">参与人数：</span><span class="big">'+ v.count +'</span></div></div>';
                    });
                }
                $(".statistics_box .statistics_box_content").html(temp);
            }else{
                $(".statistics_box .statistics_box_head").html(old_html);
                alert(msg.message);
            }
        }
    })

}
