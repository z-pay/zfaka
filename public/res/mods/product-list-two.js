layui.define(['layer','jquery','laytpl','element','flow'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var laytpl = layui.laytpl;
	var element = layui.element;
	var flow = layui.flow;
	
	function getProduct(p)
	{ 
		var i = layer.load(2,{shade: [0.5,'#fff']});
		$.ajax({
			url: '/product/get/?page='+p,
			type: 'POST',
			dataType: 'json',
			data: {"tid": 0},
		})
		.done(function(res) {
			if (res.code == '0') {
				var getTpl = product_list_two_tpl.innerHTML;
				//var view = document.getElementById('product-list-two-view');
				laytpl(getTpl).render(res, function(html){
					$("#product-list-two-view").append(html);
					//view.append = html;
				});
				element.render('product-list-two-view');
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
	};
	
	//首页广告弹窗
	var layerad = $("#layerad").html(); 
	if(layerad.length>0){
		layer.open({
			type: 1
			,title: false
			,closeBtn: false
			,area: '300px;'
			,shade: 0.8
			,id: 'zlkbAD'
			,btn: [ '关闭']
			,btnAlign: 'c'
			,moveType: 1 //拖拽模式，0或者1
			,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">'+layerad+'</div>'
		});
	}
	
	//getProduct(1);
	
	//流媒体
	flow.load({
		elem: '#product-list-two-view'
		,done: function(page, next){
			getProduct(page);
			next('', page < 6);  
		}
	});
	
	exports('product-list-two',null)
});