﻿layui.define(['layer', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	
	$('.layui-btn').on('click', function(event) {
		event.preventDefault();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/product/order/payajax",
            data: { "csrf_token": TOKEN },
            success: function(data) {
                if (data.code == 1) {
                    location.href = location.protocol + "//" +data.data ;
                } else {
                    layer.msg(data.msg,{icon:2,time:5000});
                }
                return;
            }
        });
	});

	exports('productpay',null)
});