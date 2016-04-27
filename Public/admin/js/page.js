//分页JS
$(function(){
    //输入页码
    $("div.page a.page_submit").click(function(){
        var now_page = $("div.page input.now_page").val();
        var max_page = $("div.page input.now_page").attr("max_page");
        now_page = parseInt(now_page);
        max_page = parseInt(max_page);
        if(isNaN(now_page) || now_page <= 0){
            now_page = 1;
        }else if(now_page > max_page){
            now_page = max_page;
        }

        $("#admin_page_form input[name='search_now_page']").val(now_page);
        $("#admin_page_form").submit();
    });
    //上一页
    $("div.page a.last_page").click(function(){
        var now_page = $("#admin_page_form input[name='search_now_page']").val();
        $("#admin_page_form input[name='search_now_page']").val(parseInt(now_page)-parseInt(1));
        $("#admin_page_form").submit();
    });
    //下一页
    $("div.page a.next_page").click(function(){
        var now_page = $("#admin_page_form input[name='search_now_page']").val();
        $("#admin_page_form input[name='search_now_page']").val(parseInt(now_page)+parseInt(1));
        $("#admin_page_form").submit();
    });
});

