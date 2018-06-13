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
			{field: 'typeid', title: '商品类型'},
			{field: 'name', title: '商品名'},
			{field: 'auto', title: '自动发货'},
			{field: 'qty', title: '数量'},
			{field: 'price', title: '单价'},
			{field: 'ishidden', title: '是否上架'},
		]]
	});


	exports('adminproducts',null)
});