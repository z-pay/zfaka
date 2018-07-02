layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


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
			{field: 'number', title: '数量',width:40},
			{field: 'status', title: '状态', width:80, templet: '#status',align:'center'},
			{field: 'paymoney', title: '支付金额',width:80},
			{field: 'opt', title: '操作', templet: '#opt',align:'center',fixed: 'right', width: 160,},
		]]
	});


	exports('adminorder',null)
});