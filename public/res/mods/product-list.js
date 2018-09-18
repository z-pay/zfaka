layui.define(['layer', 'table'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var device = layui.device();
	
	if(device.weixin || device.android || device.ios){
		table.render({
			elem: '#table',
			url: '/product/get',
			page: true,
			cellMinWidth:60,
			cols: [[
				{field: 'name', title: '商品名称',minWidth:120},
				{field: 'price', title: '单价', width:80,},
				{field: 'opt', title: '操作', width:80, templet: '#opt',align:'center',fixed: 'right'},
			]]
		});
	}else{
		table.render({
			elem: '#table',
			url: '/product/get',
			page: true,
			cellMinWidth:60,
			cols: [[
				{field: 'name', title: '商品名称',minWidth:120},
				{field: 'price', title: '单价', width:80,},
				{field: 'qty', title: '库存', width:80, templet: '#qty',align:'center'},
				{field: 'auto', title: '发货模式', width:100, templet: '#auto',align:'center'},
				{field: 'opt', title: '操作', width:120, templet: '#opt',align:'center',fixed: 'right'},
			]]
		});
	}
	exports('product-list',null)
});