layui.define(['layer', 'table', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var form = layui.form;
	var pid = $('#pid').val();
		
	table.render({
		elem: '#table',
		url: '/'+ADMIN_DIR+'/products/ajaxpifa',
		page: true,
		cellMinWidth:60,
		where: {"pid": pid},
		cols: [[
			{field: 'id', title: 'ID', width:80},
			{field: 'qty', title: '数量',width:80},
			{field: 'discount', title: '折扣',width:80},
			{field: 'tag', title: '备注'},
		]]
	});
	exports('adminproductspifa',null)
});