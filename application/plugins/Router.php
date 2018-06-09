<?php

class RouterPlugin extends Yaf\Plugin_Abstract
{

    public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
    }

    // 去掉 Module 后的 index
    public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
		if (!$request->isCli()) {
            // 非 CLI 下才执行
            $modules = Yaf\Application::app()->getModules();
            $uri = $request->getRequestUri();
            $uriInfo = explode('/', $uri);
			print_r($uriInfo);
			if(isset($uriInfo[1]) AND strlen($uriInfo[1])>0){
				//通过地址解析出来的module
				echo $module = ucfirst(strtolower($uriInfo[1]));
				if (!in_array($module, $modules)) {
					//处理大小写兼容问题
					if ($request->module) {
						if($request->module==ADMIN){
							$module = strtolower('admin');
							$request->setModuleName(ucfirst($module));
							echo "设置为admin";
						}else{
							$module = strtolower($request->module);
							$request->setModuleName(ucfirst($module));
							echo "设置为admin".$module;
						}
					}
					if ($request->controller) {
						$controller = strtolower($request->controller);
						$request->setControllerName(ucfirst($controller));
					}
					if ($request->action) {
						$action = strtolower($request->action);
						$request->setActionName($action);
					}
				} else {
					//如果自动解析的module与uri的一致
					if($module==$request->module){
						//处理大小写兼容问题
						if ($request->controller) {
							$controller = strtolower($request->controller);
							$request->setControllerName(ucfirst($controller));
						}
						if ($request->action) {
							$action = strtolower($request->action);
							$request->setActionName($action);
						}
					}else{
						//设置module
						$request->setModuleName($module);
						//处理默认controller与action问题
						if (isset($uriInfo[2]) AND strlen($uriInfo[2])>0) {
							if (!preg_match("#html#", $uriInfo[2])){ 
								$request->setControllerName(ucfirst(strtolower($uriInfo[2])));
								if (isset($uriInfo[3]) AND strlen($uriInfo[3])>0) {
									if (!preg_match("#html#", $uriInfo[3])){ 
										$request->setActionName(ucfirst(strtolower($uriInfo[3])));
									}
								} else {
									$action = 'index';
									$request->setActionName($action);
								}
							}
						} else {
							$controller = 'Index';
							$request->setControllerName($controller);
							$action = 'index';
							$request->setActionName($action);
						}
					}
				}
			}
        }
    }
}