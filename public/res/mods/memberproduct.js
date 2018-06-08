layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


	table.render({
		elem: '#order',
		url: '/member/product/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'productname', title: '订单名称'},
			{field: 'price', title: '单价'},
			{field: 'number', title: '数量', width:40},
			{field: 'money', title: '金额'},
			{field: 'status', title: '状态'},
			{field: 'addtime', title: '下单时间', width:200, templet: '#addtime',align:'center'}
		]]
	});


	exports('log',null)
});