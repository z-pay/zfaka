layui.define(['layer', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	
	$('.layui-btn').on('click', function(event) {
		event.preventDefault();
		var paymethod = $(this).attr("data-type");
		var orderid = $("#orderid").val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/product/order/payajax",
            data: { "csrf_token": TOKEN,'paymethod':paymethod,'orderid':orderid },
            success: function(data) {
                if (data.code == 1) {
					layer.open({
						type: 1
						,offset: 'auto'
						,id: 'layerDemoauto' //防止重复弹出
						,content: '<div style="padding: 20px 100px;"><img src="/product/order/showqr/?url='+data.data+'" alt="当面付" width="230" height="230"></div>'
						,btn: '关闭'
						,btnAlign: 'c' //按钮居中
						,shade: 0 //不显示遮罩
						,yes: function(){
						  layer.closeAll();
						}
					});
                } else {
					var url = "https://qr.alipay.com/bax08878zjx7cgoce6qa60ea";
					layer.open({
						type: 1
						,offset: 'auto'
						,id: 'layerDemoauto' //防止重复弹出
						,content: '<div style="padding: 20px 100px;"><img src="/product/order/showqr/?url='+url+'" alt="当面付" width="230" height="230"></div>'
						,btn: '关闭'
						,btnAlign: 'c' //按钮居中
						,shade: 0 //不显示遮罩
						,yes: function(){
						  layer.closeAll();
						}
					});
                    //layer.msg(data.msg,{icon:2,time:5000});
                }
                return;
            }
        });
	});

	exports('productpay',null)
});