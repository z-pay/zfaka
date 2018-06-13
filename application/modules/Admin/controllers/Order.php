<?php

/*
 * 功能：后台中心－订单
 * Author:资料空白
 * Date:20180509
 */

class OrderController extends AdminBasicController
{
	private $m_order;
	private $m_products_card;
    public function init()
    {
        parent::init();
		$this->m_order = $this->load('order');
		$this->m_products_card = $this->load('products_card');
    }

    public function indexAction()
    {
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $this->redirect("/admin/login");
            return FALSE;
        }

		$data = array();
		$this->getView()->assign($data);
    }

	//ajax
	public function ajaxAction()
	{
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		$where = array();
		
		$page = $this->get('page');
		$page = is_numeric($page) ? $page : 1;
		
		$limit = $this->get('limit');
		$limit = is_numeric($limit) ? $limit : 10;
		
		$total=$this->m_order->Where($where)->Total();
		
        if ($total > 0) {
            if ($page > 0 && $page < (ceil($total / $limit) + 1)) {
                $pagenum = ($page - 1) * $limit;
            } else {
                $pagenum = 0;
            }
			
            $limits = "{$pagenum},{$limit}";
			$items=$this->m_order->Where($where)->Limit($limits)->Order(array('id'=>'DESC'))->Select();
			
            if (empty($items)) {
                $data = array('code'=>0,'count'=>0,'data'=>array(),'msg'=>'无数据');
            } else {
                $data = array('code'=>0,'count'=>$total,'data'=>$items,'msg'=>'有数据');
            }
        } else {
            $data = array('code'=>0,'count'=>0,'data'=>array(),'msg'=>'无数据');
        }
		Helper::response($data);
	}
	
	public function viewAction()
    {
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $this->redirect("/admin/login");
            return FALSE;
        }
		$id = $this->get('id');
		if($id AND $id>0){
			$data = array();
			$order=$this->m_order->SelectByID('',$id);
			$data['order'] =$order;
			
			$cards=$this->m_products_card->Where(array('oid'=>$order['id']))->Select();
			$card_mi_array = array_column($cards, 'card');
			$card_mi_str = implode(',',$card_mi_array);
			$data['cardmi'] = $cardmi;
			
			$this->getView()->assign($data);
		}else{
            $this->redirect("/admin/products");
            return FALSE;
		}
    }
}