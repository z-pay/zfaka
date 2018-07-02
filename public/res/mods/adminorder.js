layui.define(['layer', 'table', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var form = layui.form;


	table.render({
		elem: '#table',
		url: '/admin/order/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'orderid', title: '订单号', minWidth:160},
			{field: 'email', title: '邮箱', minWidth:160},
			{field: 'productname', title: '商品', minWidth:160},
			{field: 'number', title: '数量',width:80},
			{field: 'status', title: '状态', width:80, templet: '#status',align:'center'},
			{field: 'paymoney', title: '支付金额',width:80},
			{field: 'opt', title: '操作', templet: '#opt',align:'center',fixed: 'right', width: 160,},
		]]
	});

	form.on('submit(order-pay-button)', function(data){
		data.field.csrf_token = TOKEN;
		var i = layer.load(2,{shade: [0.5,'#fff']});
		$.ajax({
			url: '/admin/order/payajax/',
			type: 'POST',
			dataType: 'json',
			data: data.field,
		})
		.done(function(res) {
			if (res.code == '1') {
				layer.open({
					title: '提示',
					content: '确认支付成功',
					btn: ['确定'],
					yes: function(index, layero){
					    location.href = '/admin/order/view/?id='+data.field.id;
					},
					cancel: function(){ 
					    location.reload();
					}
				});
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
	
	form.on('submit(order-send-button)', function(data){
		data.field.csrf_token = TOKEN;
		var i = layer.load(2,{shade: [0.5,'#fff']});
		$.ajax({
			url: '/admin/order/sendajax/',
			type: 'POST',
			dataType: 'json',
			data: data.field,
		})
		.done(function(res) {
			if (res.code == '1') {
				layer.open({
					title: '提示',
					content: '手工发货成功',
					btn: ['确定'],
					yes: function(index, layero){
					    location.href = '/admin/order/view/?id='+data.field.id;
					},
					cancel: function(){ 
					    location.reload();
					}
				});
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
	exports('adminorder',null)
});