layui.define(['layer', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var oid = $("#oid").val();
	var t = '';
	var myTimer;
	var queryRadio = 1;
	console.log("注意：本页js用的比较多，请小心谨慎!");
	$('.orderpaymethod').on('click', function(event) {
		event.preventDefault();
		var paymethod = $(this).attr("data-type");
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/product/order/payajax",
            data: { "csrf_token": TOKEN,'paymethod':paymethod,'oid':oid },
            success: function(res) {
                if (res.code == 1) {
					queryRadio = 1;
					if(res.data.overtime>0){
						timer(res.data.overtime,paymethod);
						var html = '<h1 class="mod-title"><span class="ico_log ico-'+paymethod+'"></span></h1><div class="mod-content" style="text-align: center;"><img id="pay_qrcode_'+paymethod+'" src="'+res.data.qr+'" alt="'+res.data.payname+'" width="230" height="230">';
						html +='<div id="time-item_'+paymethod+'" class="time-item"><strong id="hour_show_'+paymethod+'"><s id="h"></s>0时</strong><strong id="minute_show_'+paymethod+'"><s></s>05分</strong><strong id="second_show_'+paymethod+'"><s></s>00秒</strong>';
						html +='<p>请使用手机'+res.data.payname+'扫一扫</p><p>扫描二维码完成支付</p></div></div>';
					}else{
						var html = '<h1 class="mod-title"><span class="ico_log ico-'+paymethod+'"></span></h1><div class="mod-content" style="text-align: center;"><img id="pay_qrcode" src="'+res.data.qr+'" alt="'+res.data.payname+'" width="230" height="230">';
						html +='<div id="time-item" class="time-item"><p>请使用手机'+res.data.payname+'扫一扫</p><p>扫描二维码完成支付</p></div></div>';
					}
					layer.open({
						type: 1
						,title: false
						,offset: 'auto'
						,id: 'layerPayone' //防止重复弹出
						,content: html
						,btn: '关闭'
						,btnAlign: 'c' //按钮居中
						,shade: 0.8 //不显示遮罩
						,yes: function(){
							layer.closeAll();
							queryRadio = 0;
						}
						,cancel: function(){ 
						   queryRadio = 0;
						} 
					});
					queryPay();
                } else {
					layer.msg(res.msg,{icon:2,time:5000});
                }
                return;
            }
        });
	});

    // 检查是否支付完成
    function queryPay() {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/product/query/pay",
            timeout: 10000, //ajax请求超时时间10s
            data: {"csrf_token": TOKEN,'oid':oid}, //post数据
            success: function (res, textStatus) {
                //从服务器得到数据，显示数据并继续查询
				clearTimeout(t);
                if (res.code>1) {
					if(queryRadio>0){
						t=setTimeout(queryPay, 3000);
					}
                } else {
					layer.closeAll();
					location.href = '/product/query/?orderid='+res.data.orderid;
                }
            },
            //Ajax请求超时，继续查询
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "timeout") {
                    t=setTimeout(queryPay, 3000);
                } else { //异常
                    t=setTimeout(queryPay, 3000);
                }
            }
        });
		//return true;
    }
	
	function timer(intDiff,paymethod) {
		var i = 0;
		myTimer = window.setInterval(function () {
			i++;
			var day = 0,
				hour = 0,
				minute = 0,
				second = 0;//时间默认值
			if (intDiff > 0) {
				day = Math.floor(intDiff / (60 * 60 * 24));
				hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
				minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
				second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
			}
			if (minute <= 9) minute = '0' + minute;
			if (second <= 9) second = '0' + second;
			$('#hour_show_'+paymethod).html('<s id="h"></s>' + hour + '时');
			$('#minute_show_'+paymethod).html('<s></s>' + minute + '分');
			$('#second_show_'+paymethod).html('<s></s>' + second + '秒');
			if (hour <= 0 && minute <= 0 && second <= 0) {
				$('#pay_qrcode_'+paymethod).attr("src", '/res/images/pay/overtime.jpg');
				$('#pay_qrcode_'+paymethod).attr("alt", '二维码失效');
				$('#time-item_'+paymethod).html("");
				clearInterval(myTimer);
				queryRadio = 0;
			}
			intDiff--;
		}, 1000);
	}
	exports('productpay',null)
});