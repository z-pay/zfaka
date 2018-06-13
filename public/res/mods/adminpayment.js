layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


	table.render({
		elem: '#payment',
		url: '/admin/payment/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'payment', title: '支付渠道', minWidth:160},
			{field: 'alias', title: '别名', minWidth:160},
			{field: 'app_id', title: 'APPID'},
			{field: 'active', title: '是否激活', width:200, templet: '#active',align:'center'}
		]]
	});


	exports('adminpayment',null)
});