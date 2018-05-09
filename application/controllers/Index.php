<?php
class IndexController extends PcBasicController {

	public function init(){
        parent::init();
	}

	public function indexAction(){
		if(!$this->login OR empty($this->uinfo)){
			$this->redirect("/member/login/");
		}else{
			$this->redirect("/member/center/");
		}
		return FALSE;
	}
}