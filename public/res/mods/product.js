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
			data: {tid: $("#typeid").val()},
			beforeSend: function () {
			},
			success: function (result) {
				if (result.code == '1') {
					var html = "";
					var data = result.data.products;
					for (var i = 0, j = data.length; i < j; i++) {
						html += '<option value='+data[i].id+'>'+data[i].name+'</option>';
					}
					$('#productlist').html("<option value=\"0\">请选择商品</option>" + html);
				} else {
					$('#productlist').html("<option value=\"0\">该分类下没有商品</option>");
				}
			}

		});

	});

	$("#productlist").change(function () {
		if ($("#productlist").val() == 0) return;
		$.ajax({
			url: '/product/get/proudctinfo',
			type: 'POST',
			dataType: 'json',
			data: {pid: $("#productlist").val()},
			beforeSend: function () {
			},
			success: function (result) {
				if (result.code == '1') {
					var data = result.data.product;
					$('#price').val(data.price);
					$('#qty').val(data.qty);
					$('#prodcut_description').html(data.description);
				} else {
					
				}
			}
		});

	});
	
	
	exports('product',null)
});