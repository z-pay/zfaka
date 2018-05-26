layui.define(['layer', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var form = layui.form;

	form.verify({
		passwd: [/^[\S]{6,20}$/,'密码必须6到20位，除空格外的任意字符'],
		vercode: [/^[0-9a-zA-Z]{4}$/,'图形验证码错误']
	});

	form.on('submit(login)', function(data){

		data.field.csrf_token = TOKEN;
		var i = layer.load(1);
		$.ajax({
			url: '/member/login/ajax/',
			type: 'POST',
			dataType: 'json',
			data: data.field,
		})
		.done(function(res) {
			if (res.code == '1') {
				location.pathname = '/member'
			} else {
				layer.msg(res.msg,{icon:1});
			}
		})
		.fail(function() {
			layer.msg('服务器连接失败，请联系管理员',{icon:1});
		})
		.always(function() {
			layer.close(i);
		});

		return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
	});

	exports('login',null)
});