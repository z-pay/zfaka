
layui.define(['layer', 'table', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var form = layui.form;


	table.render({
		elem: '#question',
		url: '/demo/table/user/',
		page: true,
		cols: [[
			{field: 'title', title: 'ID', width:80, sort: true, fixed: 'left'},
			{field: 'addtime', title: '用户名', width:80}
		]]
	});



	exports('help',null)
});