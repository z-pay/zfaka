layui.define(['layer', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var form = layui.form;

	form.verify({
		passwd: [/^[\S]{6,12}$/,'密码必须6到12位，且不能出现空格'],
		vercode: [/^[0-9a-zA-Z]{4}$/,'图形验证码错误']
	});
	form.on('submit(login)', function(data){
		console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
		console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
		console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}

		layer.alert(JSON.stringify(data.field), {
			title: '最终的提交信息'
		})

		return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
	});

	exports('login',null)
});