layui.define(['layer', 'table', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var table = layui.table;
	var form = layui.form;

	form.verify({
		passwd: [/^[\S]{6,16}$/,'密码必须6到16位，除空格外的任意字符'],
		qq:function(value){
			if (value != ''&& !/^[1-9][0-9]{5,10}$/.test(value)) {
				return '请输入正确的QQ号码';
			}
		}
	});

	form.on('submit(agent)', function(data){

		data.field.csrf_token = TOKEN;
		var i = layer.load(2,{shade: [0.5,'#fff']});
		$.ajax({
			url: '/agent/user/addajax',
			type: 'post',
			dataType: 'json',
			data: data.field,
		})
		.done(function(res) {
			if (res.code == '1') {
				location.pathname = '/member'
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


	table.render({
		elem: '#userlist',
		url: '/agent/user/ajax',
		page: true,
		cellMinWidth:60,
		cols: [[
			{field: 'id', title: 'ID'},
			{field: 'email', title: '邮箱', minWidth:160},
			{field: 'nickname', title: '昵称'},
			{field: 'mobilephone', title: '手机号码', width:120,align:'center'},
			{field: 'qq', title: 'QQ', width:120, align:'center'},
			{field: 'groupid', title: '分组'},
			{field: 'money', title: '余额'},
			{field: 'integral', title: '积分'},
			{field: 'createtime', title: '注册时间', width:160, templet: '#createtime',align:'center'}
		]]
	});


	exports('agent',null)
});