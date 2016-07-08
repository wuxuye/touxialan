//规则JS

//删除商品
function deleteRule(rule_id){
    if(confirm("确定删除这个规则？")){
        rule_id = parseInt(rule_id);
        if(!isNaN(rule_id) && rule_id > 0){
            $.ajax({
                url:'/Admin/Ajax/ajaxDeleteWebRule',
                type:'POST',
                dataType:'JSON',
                data:'rule_id='+rule_id,
                success:function(msg){
                    if(msg.state==1){
                        //将这个表单提交
                        $(".rule_list_box #admin_page_form").submit();
                    }else{
                        alert(msg.message);
                    }
                }
            })
        }else{
            alert("规则id错误");
        }
    }
}

