<?php
class IndexController extends PcBasicController {

	public function init(){
        parent::init();
	}

	public function indexAction(){
		$data=array();
		$this->getView()->assign($data);
	}
}