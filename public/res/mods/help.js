
layui.define(['layer', 'table', 'util'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var util = layui.util;


	table.render({
		elem: '#question',
		url: '/help/index/ajax',
		page: true,
		cols: [[
			{field: 'title', title: '常见问题'},
			{field: 'addtime', title: '发布时间', width:160, templet: '#addtime'}
		]]
	});



	exports('help',null)
});