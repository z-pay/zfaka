layui.define(['layer', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var form = layui.form;

	$("#typeid").change(function () {
		if ($("#typeid").val() == 0) return;
		$.ajax({
			url: '/product/get/proudctlist',
			type: 'POST',
			dataType: 'json',
			data: {typeid: $("#typeid").val()},
			beforeSend: function () {
			},
			success: function (result) {
				if (result.code == '1') {
					var html = "";
					var data = result.data;
					for (var i = 0, j = data.length; i < j; i++) {
						html += '<option value='+data[i].id+'>'+data[i].name+'</option>';
					}
					$('#glist').html("<option value=\"0\">请选择商品</option>" + html);
				} else {
					$('#glist').html("<option value=\"0\">该分类下没有商品</option>");
				}
			}

		});

	});

	exports('product',null)
});