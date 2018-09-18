layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	

	table.render({
		elem: '#table',
		url: '/product/get',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'name', title: '商品名称'},
			{field: 'price', title: '单价'},
			{field: 'qty', title: '库存', width:80, templet: '#qty',align:'center'},
			{field: 'auto', title: '发货模式', width:100, templet: '#auto',align:'center'},
			{field: 'opt', title: '操作', width:160, templet: '#opt',align:'center'},
		]]
	});

	exports('product-list',null)
});