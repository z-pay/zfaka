layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


	table.render({
		elem: '#order',
		url: '/admin/order/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'orderid', title: '订单号', minWidth:160},
			{field: 'email', title: '邮箱', minWidth:160},
			{field: 'productname', title: '商品', minWidth:160},
			{field: 'number', title: '数量'},
			{field: 'paymoney', title: '支付金额'},
			{field: 'addtime', title: '下单时间', width:200, templet: '#addtime',align:'center'},
			{field: 'paytime', title: '支付时间', width:200, templet: '#paytime',align:'center'}
		]]
	});


	exports('adminorder',null)
});