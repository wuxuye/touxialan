//统计JS

//更新统计数据
function updateStatisticsData(){
    if(confirm("确定要 更新统计信息？")){
        $.ajax({
            url:'/Admin/Ajax/ajaxUpdateAttrStatisticsData',
            type:'POST',
            dataType:'JSON',
            success:function(msg){
                alert(msg.message);
                if(msg.state==1){
                    //将这个表单提交
                    location.reload();
                }
            }
        })
    }
}

//资金统计相关

//更新资金统计
var wait_fund_statistics = 0;
function updateFundStatistics(){
    if(wait_fund_statistics == 0){
        if(confirm("确定要开始统计资金？")){
            wait_fund_statistics = 1;
            $.ajax({
                url:'/Admin/Ajax/ajaxUpdateFundStatistics',
                type:'POST',
                dataType:'JSON',
                success:function(msg){
                    alert(msg.message);
                    wait_fund_statistics = 0;
                    if(msg.state == 1){
                        if(msg.is_wrong==1){
                            alert(msg.wrong_list);
                        }
                        window.location.reload();
                    }
                },
                error:function(e){
                    wait_fund_statistics = 0;
                    alert("操作错误");
                }
            })
        }
    }else{
        alert("资金正在统计，请稍后...");
    }
}


//公共统计相关

//公共统计数据获取初始化方法
function publicGetStatisticsReady(){
    var level = $(".public_statistics_box #statistics_level").val();
    var time = $(".public_statistics_box #statistics_time").val();
    $(".public_statistics_box .statistics_box_head").empty();
    $(".public_statistics_box .statistics_box_content").empty();
    publicGetStatisticsData(level,time);
}

//问题统计数据获取
function publicGetStatisticsData(level,time){

    var type = $(".public_statistics_box #statistics_type").val();

    var old_html = $(".public_statistics_box .statistics_box_head").html();
    $(".public_statistics_box .statistics_box_head").html("数据获取ing...");
    $.ajax({
        url:'/Admin/Ajax/ajaxPublicGetStatisticsData',
        type:'POST',
        dataType:'JSON',
        data:'level='+level+'&time='+time+'&type='+type,
        success:function(msg){
            if(msg.state==1){
                $(".public_statistics_box .statistics_box_content").empty();
                var data = msg.data;
                var temp = "";
                if(data.level == 1){ //年列表
                    $(".public_statistics_box .statistics_box_head").html("年份列表");
                    $.each(data.statistics,function(k,v){

                        var no_num = "";
                        if(v.profit == 0 && v.income == 0 && v.expenses == 0 && v.withdraw == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box' + no_num + '" title="点击查看 '+k+'年 的月列表" onclick="publicGetStatisticsData(2,'+k+')" >' +
                                    '<div class="time_box_head">'+k+'年</div>' +
                                    '<div class="time_box_content' + no_num + '">' +
                                        '利润：<span class="'+ (v.profit>0?'green_color':'red_color') +'">'+v.profit+'</span><br/>' +
                                        '收入：<span class="green_color">'+v.income+'</span><br/>' +
                                        '支出：<span class="red_color">'+v.expenses+'</span><br/>' +
                                        '提现：'+v.withdraw +
                                    '</div>' +
                                '</div>';
                    });
                }else if(data.level == 2){ //月列表
                    $(".public_statistics_box .statistics_box_head").html("<span class='operation' onclick='publicGetStatisticsData(1,0)'>返回年列表</span><span>"+data.year+"年 月报表</span>");
                    $.each(data.statistics,function(k,v){

                        var no_num = "";
                        if(v.profit == 0 && v.income == 0 && v.expenses == 0 && v.withdraw == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box' + no_num + '" title="点击查看 '+ v.month+'月 的日列表" onclick="publicGetStatisticsData(3,\''+data.year+'-'+ v.month+'\')" >' +
                                    '<div class="time_box_head">'+ v.month+'月</div>' +
                                    '<div class="time_box_content">' +
                                        '利润：<span class="'+ (v.profit>0?'green_color':'red_color') +'">'+v.profit+'</span><br/>' +
                                        '收入：<span class="green_color">'+v.income+'</span><br/>' +
                                        '支出：<span class="red_color">'+v.expenses+'</span><br/>' +
                                        '提现：'+v.withdraw +
                                    '</div>' +
                                '</div>';
                    });
                }else if(data.level == 3){ //日列表
                    var head = "<span class='operation' onclick='publicGetStatisticsData(2,\""+data.year+"\")'>返回月列表</span>";
                    //上个月
                    var last_month = parseInt(data.month) - 1;
                    if(last_month > 0){
                        if(last_month < 10){
                            last_month = "0"+last_month;
                        }
                        head += "<span class='operation' onclick='publicGetStatisticsData(3,\""+data.year+"-"+last_month+"\")'>上个月</span>";
                    }
                    head += "<span>"+data.year+"年"+data.month+"月 日报表</span>";
                    //下个月
                    var next_month = parseInt(data.month) + 1;
                    if(next_month > 0 && next_month < 13){
                        if(next_month < 10){
                            next_month = "0"+next_month;
                        }
                        head += "<span class='operation' onclick='publicGetStatisticsData(3,\""+data.year+"-"+next_month+"\")'>下个月</span>";
                    }
                    $(".public_statistics_box .statistics_box_head").html(head);
                    $.each(data.statistics,function(k,v){

                        var no_num = "";
                        if(v.profit == 0 && v.income == 0 && v.expenses == 0 && v.withdraw == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box' + no_num + '">' +
                                    '<div class="time_box_head">'+data.month+'月'+ v.day +'</div>' +
                                    '<div class="time_box_content">' +
                                        '利润：<span class="'+ (v.profit>0?'green_color':'red_color') +'">'+v.profit+'</span><br/>' +
                                        '收入：<span class="green_color">'+v.income+'</span><br/>' +
                                        '支出：<span class="red_color">'+v.expenses+'</span><br/>' +
                                        '提现：'+v.withdraw +
                                    '</div>' +
                                '</div>';
                    });
                }
                $(".public_statistics_box .statistics_box_content").html(temp);
            }else{
                $(".public_statistics_box .statistics_box_head").html(old_html);
                alert(msg.message);
            }
        }
    })

}
