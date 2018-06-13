layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


	table.render({
		elem: '#products',
		url: '/admin/products/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'typename', title: '商品类型'},
			{field: 'name', title: '商品名'},
			{field: 'price', title: '单价'},
			{field: 'qty', title: '数量'},
			{field: 'auto', title: '自动发货', width:100, templet: '#auto',align:'center'},
			{field: 'ishidden', title: '是否上架', width:100, templet: '#ishidden',align:'center'},
			{field: 'opt', title: '操作', width:100, templet: '#opt',align:'center'},
		]]
	});


	exports('adminproducts',null)
});