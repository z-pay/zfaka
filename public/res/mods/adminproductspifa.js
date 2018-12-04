layui.define(['layer', 'table', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var form = layui.form;
	var pid = $('#pid').val();
		
	table.render({
		elem: '#table',
		url: '/'+ADMIN_DIR+'/productspifa/ajax',
		page: true,
		cellMinWidth:60,
		where: {"pid": pid},
		cols: [[
			{field: 'qty', title: '数量',width:80},
			{field: 'discount', title: '折扣',width:80},
			{field: 'tag', title: '备注'},
			{field: 'opt', title: '操作', width:120, templet: '#opt',align:'center'},
		]]
	});
	exports('adminproductspifa',null)
});