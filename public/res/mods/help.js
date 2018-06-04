
layui.define(['layer', 'table', 'element'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var element = layui.element;


	table.render({
		elem: '#question',
		url: '/help/index/ajax',
		page: true,
		cols: [[
			{field: 'title', title: '常见问题'},
			{field: 'addtime', title: '发布时间', width:160, templet: '#addtime',align:'center'},
			{fixed: 'right', title: '详情', width:100, align:'center', templet: function(d){return 'ID：'+ d.id +'，标题：<span style="color: #c00;">'+ d.title +'</span>'}}
		]]
	});



	exports('help',null)
});