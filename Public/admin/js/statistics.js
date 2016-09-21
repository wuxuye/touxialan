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

//资金统计数据获取初始化方法
function getFundStatisticsReady(){
    var level = $(".public_statistics_box #statistics_level").val();
    var time = $(".public_statistics_box #statistics_time").val();
    $(".public_statistics_box .statistics_box_head").empty();
    $(".public_statistics_box .statistics_box_content").empty();
    getFundStatisticsData(level,time);
}

//资金统计数据获取
function getFundStatisticsData(level,time){

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

                        temp += '<div class="time_box' + no_num + '" title="点击查看 '+k+'年 的月列表" onclick="getFundStatisticsData(2,'+k+')" >' +
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
                    $(".public_statistics_box .statistics_box_head").html("<span class='operation' onclick='getFundStatisticsData(1,0)'>返回年列表</span><span>"+data.year+"年 月报表</span>");
                    $.each(data.statistics,function(k,v){

                        var no_num = "";
                        if(v.profit == 0 && v.income == 0 && v.expenses == 0 && v.withdraw == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box' + no_num + '" title="点击查看 '+ v.month+'月 的日列表" onclick="getFundStatisticsData(3,\''+data.year+'-'+ v.month+'\')" >' +
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
                    var head = "<span class='operation' onclick='getFundStatisticsData(2,\""+data.year+"\")'>返回月列表</span>";
                    //上个月
                    var last_month = parseInt(data.month) - 1;
                    if(last_month > 0){
                        if(last_month < 10){
                            last_month = "0"+last_month;
                        }
                        head += "<span class='operation' onclick='getFundStatisticsData(3,\""+data.year+"-"+last_month+"\")'>上个月</span>";
                    }
                    head += "<span>"+data.year+"年"+data.month+"月 日报表</span>";
                    //下个月
                    var next_month = parseInt(data.month) + 1;
                    if(next_month > 0 && next_month < 13){
                        if(next_month < 10){
                            next_month = "0"+next_month;
                        }
                        head += "<span class='operation' onclick='getFundStatisticsData(3,\""+data.year+"-"+next_month+"\")'>下个月</span>";
                    }
                    $(".public_statistics_box .statistics_box_head").html(head);
                    $.each(data.statistics,function(k,v){

                        var no_num = "";
                        if(v.profit == 0 && v.income == 0 && v.expenses == 0 && v.withdraw == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box' + no_num + '">' +
                                    '<div class="time_box_head">'+data.month+'月'+ v.day +'日</div>' +
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


//订单统计数据获取初始化方法
function getOrderStatisticsReady(){
    var level = $(".public_statistics_box #statistics_level").val();
    var time = $(".public_statistics_box #statistics_time").val();
    $(".public_statistics_box .statistics_box_head").empty();
    $(".public_statistics_box .statistics_box_content").empty();
    getOrderStatisticsData(level,time);
}

//订单统计数据获取
function getOrderStatisticsData(level,time){

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
                        if(v.all == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box big_box' + no_num + '" title="点击查看 '+k+'年 的月列表" onclick="getOrderStatisticsData(2,'+k+')" >' +
                                    '<div class="time_box_head">'+k+'年</div>' +
                                    '<div class="time_box_content' + no_num + '">' +
                                        '总订单：' + v.all + '<br/>' +
                                        '完成订单：<span class="'+ (v.success>0?'green_color':'') +'">' + v.success + '</span><br/>' +
                                        '关闭订单：<span class="'+ (v.close>0?'red_color':'') +'">' + v.close + '</span><br/>' +
                                        '退款订单：<span class="'+ (v.refund>0?'red_color':'') +'">' + v.refund + '</span><br/>' +
                                        '订单总金额：' + v.pay_price + '<br/>' +
                                        '完成订单金额：' + v.success_price + '<br/>' +
                                        '完成订单积分：' + v.success_point +
                                    '</div>' +
                                '</div>';
                    });
                }else if(data.level == 2){ //月列表
                    $(".public_statistics_box .statistics_box_head").html("<span class='operation' onclick='getOrderStatisticsData(1,0)'>返回年列表</span><span>"+data.year+"年 月报表</span>");
                    $.each(data.statistics,function(k,v){

                        var no_num = "";
                        if(v.all == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box big_box' + no_num + '" title="点击查看 '+ v.month+'月 的日列表" onclick="getOrderStatisticsData(3,\''+data.year+'-'+ v.month+'\')" >' +
                                    '<div class="time_box_head">'+ v.month+'月</div>' +
                                    '<div class="time_box_content">' +
                                        '总订单：' + v.all + '<br/>' +
                                        '完成订单：<span class="'+ (v.success>0?'green_color':'') +'">' + v.success + '</span><br/>' +
                                        '关闭订单：<span class="'+ (v.close>0?'red_color':'') +'">' + v.close + '</span><br/>' +
                                        '退款订单：<span class="'+ (v.refund>0?'red_color':'') +'">' + v.refund + '</span><br/>' +
                                        '订单总金额：' + v.pay_price + '<br/>' +
                                        '完成订单金额：' + v.success_price + '<br/>' +
                                        '完成订单积分：' + v.success_point +
                                    '</div>' +
                                '</div>';
                    });
                }else if(data.level == 3){ //日列表
                    var head = "<span class='operation' onclick='getOrderStatisticsData(2,\""+data.year+"\")'>返回月列表</span>";
                    //上个月
                    var last_month = parseInt(data.month) - 1;
                    if(last_month > 0){
                        if(last_month < 10){
                            last_month = "0"+last_month;
                        }
                        head += "<span class='operation' onclick='getOrderStatisticsData(3,\""+data.year+"-"+last_month+"\")'>上个月</span>";
                    }
                    head += "<span>"+data.year+"年"+data.month+"月 日报表</span>";
                    //下个月
                    var next_month = parseInt(data.month) + 1;
                    if(next_month > 0 && next_month < 13){
                        if(next_month < 10){
                            next_month = "0"+next_month;
                        }
                        head += "<span class='operation' onclick='getOrderStatisticsData(3,\""+data.year+"-"+next_month+"\")'>下个月</span>";
                    }
                    $(".public_statistics_box .statistics_box_head").html(head);
                    $.each(data.statistics,function(k,v){

                        var no_num = "";
                        if(v.all == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box big_box' + no_num + '">' +
                                    '<div class="time_box_head">'+data.month+'月'+ v.day +'日</div>' +
                                    '<div class="time_box_content">' +
                                        '总订单：' + v.all + '<br/>' +
                                        '完成订单：<span class="'+ (v.success>0?'green_color':'') +'">' + v.success + '</span><br/>' +
                                        '关闭订单：<span class="'+ (v.close>0?'red_color':'') +'">' + v.close + '</span><br/>' +
                                        '退款订单：<span class="'+ (v.refund>0?'red_color':'') +'">' + v.refund + '</span><br/>' +
                                        '订单总金额：' + v.pay_price + '<br/>' +
                                        '完成订单金额：' + v.success_price + '<br/>' +
                                        '完成订单积分：' + v.success_point +
                                    '</div>' +
                                '</div>';
                    });
                }
                $(".public_statistics_box .statistics_box_content").html(temp);

                //赋值总体统计
                $(".public_statistics_info_box #all_order").html(msg.data.all.all);
                $(".public_statistics_info_box #success_order").html(msg.data.all.success);
                $(".public_statistics_info_box #close_order").html(msg.data.all.close);
                $(".public_statistics_info_box #refund_order").html(msg.data.all.refund);
                $(".public_statistics_info_box #order_all_price").html(msg.data.all.pay_price);
                $(".public_statistics_info_box #success_order_all_price").html(msg.data.all.success_price);
                $(".public_statistics_info_box #success_order_all_point").html(msg.data.all.success_point);

            }else{
                $(".public_statistics_box .statistics_box_head").html(old_html);
                alert(msg.message);
            }
        }
    })

}


//商品销量统计
var wait_goods_sale_statistics = 0;
function updateGoodsSaleStatistics(){
    if(wait_goods_sale_statistics == 0){
        if(confirm("确定要开始统计商品销量？")){
            wait_goods_sale_statistics = 1;
            $.ajax({
                url:'/Admin/Ajax/ajaxUpdateGoodsSaleStatistics',
                type:'POST',
                dataType:'JSON',
                success:function(msg){
                    alert(msg.message);
                    wait_goods_sale_statistics = 0;
                    if(msg.state == 1){
                        window.location.reload();
                    }
                },
                error:function(e){
                    wait_goods_sale_statistics = 0;
                    alert("操作错误");
                }
            })
        }
    }else{
        alert("销量正在统计，请稍后...");
    }
}

//销量统计数据获取初始化方法
function getSaleStatisticsReady(){
    var level = $(".public_statistics_box #statistics_level").val();
    var time = $(".public_statistics_box #statistics_time").val();
    $(".public_statistics_box .statistics_box_head").empty();
    $(".public_statistics_box .statistics_box_content").empty();
    getSaleStatisticsData(level,time);
}

//销量统计数据获取
function getSaleStatisticsData(level,time){

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
                        if(v.sale_num == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box op_box ' + no_num + '" title="点击查看 '+k+'年 的月列表" >' +
                                    '<div class="time_box_head">'+k+'年</div>' +
                                    '<div class="time_box_content' + no_num + '">' +
                                        '总销量：' + v.sale_num + '<br/>' +
                                        '销售金额：<span class="'+ (v.sale_price>0?'green_color':'') +'">' + v.sale_price + '</span>' +
                                    '</div>' +
                                    '<div class="time_box_footer"><a href="javascript:;" onclick="getSaleStatisticsData(2,'+k+')" >查看月</a>&nbsp;&nbsp;<a href="javascript:;" onclick="getSaleStatisticsDataList(1,'+k+')" >详细</a></div>' +
                                '</div>';
                    });
                }else if(data.level == 2){ //月列表
                    $(".public_statistics_box .statistics_box_head").html("<span class='operation' onclick='getSaleStatisticsData(1,0)'>返回年列表</span><span>"+data.year+"年 月报表</span>");
                    $.each(data.statistics,function(k,v){

                        var no_num = "";
                        if(v.sale_num == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box op_box ' + no_num + '" title="点击查看 '+ v.month+'月 的日列表"  >' +
                                    '<div class="time_box_head">'+ v.month+'月</div>' +
                                    '<div class="time_box_content">' +
                                        '总销量：' + v.sale_num + '<br/>' +
                                        '销售金额：<span class="'+ (v.sale_price>0?'green_color':'') +'">' + v.sale_price + '</span>' +
                                    '</div>' +
                                    '<div class="time_box_footer"><a href="javascript:;" onclick="getSaleStatisticsData(3,\''+data.year+'-'+ v.month+'\')" >查看日</a>&nbsp;&nbsp;<a href="javascript:;" onclick="getSaleStatisticsDataList(2,\''+data.year+'-'+ v.month+'\')" >详细</a></div>' +
                                '</div>';
                    });
                }else if(data.level == 3){ //日列表
                    var head = "<span class='operation' onclick='getSaleStatisticsData(2,\""+data.year+"\")'>返回月列表</span>";
                    //上个月
                    var last_month = parseInt(data.month) - 1;
                    if(last_month > 0){
                        if(last_month < 10){
                            last_month = "0"+last_month;
                        }
                        head += "<span class='operation' onclick='getSaleStatisticsData(3,\""+data.year+"-"+last_month+"\")'>上个月</span>";
                    }
                    head += "<span>"+data.year+"年"+data.month+"月 日报表</span>";
                    //下个月
                    var next_month = parseInt(data.month) + 1;
                    if(next_month > 0 && next_month < 13){
                        if(next_month < 10){
                            next_month = "0"+next_month;
                        }
                        head += "<span class='operation' onclick='getSaleStatisticsData(3,\""+data.year+"-"+next_month+"\")'>下个月</span>";
                    }
                    $(".public_statistics_box .statistics_box_head").html(head);
                    $.each(data.statistics,function(k,v){

                        var no_num = "";
                        if(v.sale_num == 0){
                            no_num = " no_num ";
                        }

                        temp += '<div class="time_box op_box ' + no_num + '">' +
                                    '<div class="time_box_head">'+data.month+'月'+ v.day +'日</div>' +
                                    '<div class="time_box_content">' +
                                        '总销量：' + v.sale_num + '<br/>' +
                                        '销售金额：<span class="'+ (v.sale_price>0?'green_color':'') +'">' + v.sale_price + '</span>' +
                                    '</div>' +
                                    '<div class="time_box_footer"><a href="javascript:;" onclick="getSaleStatisticsDataList(3,\''+data.year+'-'+ data.month +'-'+ v.day+'\')" >详细</a></div>' +
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

//获取销量统计详情列表
function getSaleStatisticsDataList(level,time){
    $(".public_statistics_footer_list_box").html("数据获取ing...");
    $(".public_statistics_footer_list_title").html("");
    $.ajax({
        url:'/Admin/Ajax/ajaxGetSaleStatisticsDataList',
        type:'POST',
        dataType:'JSON',
        data:'level='+level+'&time='+time,
        success:function(msg){
            if(msg.state==1){
                $(".public_statistics_footer_list_box").empty();
                var data = msg.data;
                var temp = '<table class="table"><tr><th>商品id</th><th>商品名称</th><th>商品扩展名</th><th>商品销量</th><th>销售总额</th><th>合计客流量</th></tr>';
                $(".public_statistics_footer_list_title").html(data.time+" 商品销量统计列表");
                if(data.level == 1){ //年列表
                    $.each(data.statistics,function(k,v){
                        temp += '<tr>' +
                                    '<td>' + v.goods_id + '</td> ' +
                                    '<td title="' + v.goods_name + '">' + v.goods_name_str + '</td>' +
                                    '<td title="' + v.goods_ext_name + '">' + v.goods_ext_name_str + '</td>' +
                                    '<td>' + v.sale_num + '</td>' +
                                    '<td>' + v.sale_price + '</td>' +
                                    '<td>' + v.sale_user + '</td>' +
                                '</tr>';
                    });
                }else if(data.level == 2){ //月列表
                    $.each(data.statistics,function(k,v){
                        temp += '<tr>' +
                                    '<td>' + v.goods_id + '</td> ' +
                                    '<td title="' + v.goods_name + '">' + v.goods_name_str + '</td>' +
                                    '<td title="' + v.goods_ext_name + '">' + v.goods_ext_name_str + '</td>' +
                                    '<td>' + v.sale_num + '</td>' +
                                    '<td>' + v.sale_price + '</td>' +
                                    '<td>' + v.sale_user + '</td>' +
                                '</tr>';
                    });
                }else if(data.level == 3) { //日列表
                    $.each(data.statistics, function (k, v) {
                        temp += '<tr>' +
                            '<td>' + v.goods_id + '</td> ' +
                            '<td title="' + v.goods_name + '">' + v.goods_name_str + '</td>' +
                            '<td title="' + v.goods_ext_name + '">' + v.goods_ext_name_str + '</td>' +
                            '<td>' + v.sale_num + '</td>' +
                            '<td>' + v.sale_price + '</td>' +
                            '<td>' + v.sale_user + '</td>' +
                            '</tr>';
                    });
                }

                $(".public_statistics_footer_list_box").html(temp);
            }else{
                $(".public_statistics_footer_list_box").html("");
                alert(msg.message);
            }
        }
    });
}

