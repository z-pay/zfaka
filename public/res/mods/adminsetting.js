layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


	table.render({
		elem: '#setting',
		url: '/admin/setting/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'name', title: '参数'},
			{field: 'value', title: '值'},
			{field: 'tag', title: '备注'},
			{field: 'updatetime', title: '更新时间', width:200, templet: '#updatetime',align:'center'}
		]]
	});


	exports('adminsetting',null)
});