layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;


	table.render({
		elem: '#emailqueue',
		url: '/'+ADMIN_DIR+'/emailqueue/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'email', title: '收件人', minWidth:160},
			{field: 'subject', title: '主题', minWidth:160},
			{field: 'addtime', title: '添加时间', width:200, templet: '#addtime',align:'center'},
			{field: 'sendtime', title: '发送时间', width:200, templet: '#sendtime',align:'center'}
		]]
	});


	exports('adminemailqueue',null)
});