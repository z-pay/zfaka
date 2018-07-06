layui.define(['layer', 'form'], function(exports){
	var $ = layui.jquery;
	var layer = layui.layer;
	var form = layui.form;


	// 检查更新
    function checkUpdate() {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/admin/index/updatecheckajax",
            timeout: 10000, //ajax请求超时时间10s
            data: {"csrf_token": TOKEN,'method':"updatecheck"}, //post数据
            success: function (res, textStatus) {
                //从服务器得到数据，显示数据并继续查询
				if (res.code == 1) {
					var html = '<div class="mod-content" style="text-align: center;"><p>友情提示：有更新啦！</p><p>最新程序安装包：<a href="'+res.data.zip+'">立即下载</a>；</p><p>github站点：<a href="'+res.data.url+'">立即访问</a>；</p></div>';
					layer.open({
						type: 1
						,title: false
						,offset: 'auto'
						,id: 'layerPayone' //防止重复弹出
						,content: html
						,btn: '关闭'
						,btnAlign: 'c' //按钮居中
						,shade: 0.8 //不显示遮罩
						,yes: function(){
							layer.closeAll();
							queryRadio = 0;
						}
						,cancel: function(){ 
							   ueryRadio = 0;
						} 
					});
				}
            },
        });
    }

	checkUpdate();
	exports('adminindex',null)
});