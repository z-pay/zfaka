layui.define(['layer','table', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var form = layui.form;

	form.on('submit(query)', function(data){
		data.field.csrf_token = TOKEN;
		var i = layer.load(2,{shade: [0.5,'#fff']});
		$.ajax({
			url: '/product/query/ajax/',
			type: 'POST',
			dataType: 'json',
			data: data.field,
		})
		.done(function(res) {
			if (res.code == '1') {
				var html = "";
				var list = res.data;
				for (var i = 0, j = list.length; i < j; i++) {
					html += '<tr><td>'+list[i].productname+'</td><td>'+list[i].number+'</td><td>'+list[i].money+'</td><td>'+list[i].addtime+'</td><td>'+list[i].status+'</td></tr>';
				}
				$("#query-table2 tbody").prepend(html);
				table.render({
					elem: '#query-table',
					page: true,
					cellMinWidth:60,
					cols: [[
						{field: 'productname', title: '订单名称'},
						{field: 'number', title: '数量'},
						{field: 'money', title: '金额'},
						{field: 'addtime', title: '下单时间', width:160, templet: '#addtime',align:'center'},
						{field: 'stauts', width:100, title: '状态', align:'center'}
					]]
				});
				layer.msg(res.msg,{icon:1,time:5000});
			} else {
				layer.msg(res.msg,{icon:2,time:5000});
			}
		})
		.fail(function() {
			layer.msg('服务器连接失败，请联系管理员',{icon:2,time:5000});
		})
		.always(function() {
			layer.close(i);
		});

		return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
	});
	
	exports('productquery',null)
});