<?php
class IndexController extends PcBasicController {

	public function init(){
        parent::init();
	}

	public function indexAction(){
		if(!$this->login OR empty($this->uinfo)){
			$this->redirect("/product/");
		}else{
			$this->redirect("/member/");
		}
		return FALSE;
	}
	
	public function testAction(){
		\PHPQRCode\QRcode::png("Test");
		exit();
	}
}