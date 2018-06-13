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
			{field: 'name', title: '商品名'},
			{field: 'card', title: '卡密'},
			{field: 'addtime', title: '添加时间', width:200, templet: '#addtime',align:'center'},
			{field: 'oid', title: '状态', width:100, templet: '#status',align:'center'}
			{field: 'opt', title: '操作', width:100, templet: '#opt',align:'center'}
		]]
	});


	exports('adminproductscard',null)
});