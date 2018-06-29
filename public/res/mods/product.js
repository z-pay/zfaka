layui.define(['layer', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var form = layui.form;

	form.on('select(typeid)', function(data){
		if (data.value == 0) return;
		$.ajax({
			url: '/product/get/proudctlist',
			type: 'POST',
			dataType: 'json',
			data: {'tid': data.value,'csrf_token':TOKEN},
			beforeSend: function () {
			},
			success: function (res) {
				if (res.code == '1') {
					var html = "";
					var list = res.data.products;
					for (var i = 0, j = list.length; i < j; i++) {
						html += '<option value='+list[i].id+'>'+list[i].name+'</option>';
					}
					$('#productlist').html("<option value=\"0\">请选择</option>" + html);
					$('#price').val('');
					$('#qty').val('');
					$('#prodcut_description').html('');
					$("#buy").attr("disabled","true");
					form.render('select');
				} else {
					$("#buy").attr("disabled","true");
					form.render('select');
					layer.msg(res.msg,{icon:2,time:5000});
				}
			}

		});
	});

	form.on('select(productlist)', function(data){
		if (data.value == 0) return;
		$.ajax({
			url: '/product/get/proudctinfo',
			type: 'POST',
			dataType: 'json',
			data: {'pid': data.value,'csrf_token':TOKEN},
			beforeSend: function () {
			},
			success: function (res) {
				if (res.code == '1') {
					var product = res.data.product;
					var html =""
					$('#price').val(product.price);
					if(product.stockcontrol>0){
						if(product.qty>0){
							$('#qty').val(product.qty);
							$("#buy").removeAttr("disabled");
						}else{
							$('#qty').val("库存不足");
							$("#buy").attr("disabled","true");
						}
					}else{
						$('#qty').val("不限量");
						$("#buy").removeAttr("disabled");
					}
					
					if(product.auto>0){
						var str = '<span class="layui-badge layui-bg-green">自动发货</span>';
					}else{
						var str = '<span class="layui-badge layui-bg-black">手工发货</span>';
					}
					
					html = str + product.description;
					$('#prodcut_description').html(html);
					form.render();
				} else {
					layer.msg(res.msg,{icon:2,time:5000});
				}
			}
		});

	});

	form.on('submit(buy)', function(data){
		data.field.csrf_token = TOKEN;
		var i = layer.load(2,{shade: [0.5,'#fff']});
		$.ajax({
			url: '/product/order/buy/',
			type: 'POST',
			dataType: 'json',
			data: data.field,
		})
		.done(function(res) {
			if (res.code == '1') {
				var oid = res.data.oid;
				if(oid.length>0){
					location.href = '/product/order/pay/?oid='+res.data.oid;
				}else{
					layer.msg("订单异常",{icon:2,time:5000});
				}
			} else {
				layer.msg(res.msg,{icon:2,time:5000});
			}
		})
		.fail(function() {
			layer.msg('服务器连接失败，请联系管理员',{icon:2,time:5000});
		})
		.always(function() {
			layer.close(i);
		});

		return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
	});

	//左右框高度
	var leftHeight = parseInt($('#prodcut_num').height());
	var rightHeight = parseInt($('#prodcut_description').height());
	if (leftHeight > rightHeight) {
		$('#prodcut_description').height(leftHeight);
	} else {
		$('#prodcut_num').height(rightHeight);
	}
	exports('product',null)
});