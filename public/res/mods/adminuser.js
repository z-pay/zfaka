layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


	table.render({
		elem: '#user',
		url: '/admin/user/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'email', title: '邮箱', minWidth:160},
			{field: 'qq', title: 'QQ', minWidth:160},
			{field: 'createtime', title: '注册时间', width:200, templet: '#createtime',align:'center'}
		]]
	});


	exports('adminuser',null)
});