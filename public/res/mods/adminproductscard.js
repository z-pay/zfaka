layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


	table.render({
		elem: '#productscard',
		url: '/admin/productscard/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'pid', title: '商品名'},
			{field: 'oid', title: '订单id'},
			{field: 'card', title: '卡密'},
			{field: 'addtime', title: '使用时间', width:200, templet: '#addtime',align:'center'},
		]]
	});


	exports('adminproductscard',null)
});