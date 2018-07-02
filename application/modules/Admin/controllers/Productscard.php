<?php

/*
 * 功能：后台中心－卡密管理
 * Author:资料空白
 * Date:20180509
 */

class ProductscardController extends AdminBasicController
{
	private $m_products_card;
	private $m_products;
    public function init()
    {
        parent::init();
		$this->m_products_card = $this->load('products_card');
		$this->m_products = $this->load('products');
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
		
		$total=$this->m_products_card->Where($where)->Total();
		
        if ($total > 0) {
            if ($page > 0 && $page < (ceil($total / $limit) + 1)) {
                $pagenum = ($page - 1) * $limit;
            } else {
                $pagenum = 0;
            }
			
            $limits = "{$pagenum},{$limit}";
			$sql ="SELECT p1.*,p2.name FROM `t_products_card` as p1 left join `t_products` as p2 on p1.pid=p2.id Order by p1.id desc LIMIT {$limits}";
			$items=$this->m_products_card->Query($sql);
			
            if (empty($items)) {
                $data = array('code'=>1002,'count'=>0,'data'=>array(),'msg'=>'无数据');
            } else {
                $data = array('code'=>0,'count'=>$total,'data'=>$items,'msg'=>'有数据');
            }
        } else {
            $data = array('code'=>1001,'count'=>0,'data'=>array(),'msg'=>'无数据');
        }
		Helper::response($data);
	}
	
    public function addAction()
    {
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $this->redirect("/admin/login");
            return FALSE;
        }
		$data = array();
		
		$products=$this->m_products->Where(array('auto'=>1))->Order(array('id'=>'DESC'))->Select();
		$data['products'] = $products;
		
		$this->getView()->assign($data);
    }
	public function addajaxAction()
	{
		$method = $this->getPost('method',false);
		$pid = $this->getPost('pid',false);
		$card = $this->getPost('card',false);
		$csrf_token = $this->getPost('csrf_token', false);
		
		$data = array();
		
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		if($method AND $pid AND $card AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$m=array(
					'pid'=>$pid,
					'card'=>$card,
					'addtime'=>time(),
				);
				if($method == 'add'){
					$u = $this->m_products_card->Insert($m);
					if($u){
						//新增商品数量
						$qty_m = array('qty' => 'qty+1');
						$this->m_products->Where(array('id'=>$pid,'stockcontrol'=>1))->Update($qty_m,TRUE);
						$data = array('code' => 1, 'msg' => '新增成功');
					}else{
						$data = array('code' => 1003, 'msg' => '新增失败');
					}
				}else{
					$data = array('code' => 1002, 'msg' => '未知方法');
				}
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}
	
	public function deleteajaxAction()
	{
		$cardid = $this->getPost('cardid',false);
		$csrf_token = $this->getPost('csrf_token', false);
		
		$data = array();
		
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		if($cardid AND $cardid>0 AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$u = $this->m_products_card->DeleteByID($cardid);
				if($u){
					//减少商品数量
					$cards = $this->m_products_card->SelectByID('pid',$cardid);
					$qty_m = array('qty' => 'qty-1');
					$this->m_products->Where(array('id'=>$cards['pid'],'stockcontrol'=>1))->Update($qty_m,TRUE);
					$data = array('code' => 1, 'msg' => '成功');
				}else{
					$data = array('code' => 1003, 'msg' => '失败');
				}
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}
	
    public function importAction()
    {
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $this->redirect("/admin/login");
            return FALSE;
        }
		$data = array();
		$products=$this->m_products->Where(array('auto'=>1))->Order(array('id'=>'DESC'))->Select();
		$data['products'] = $products;
		$this->getView()->assign($data);
    }
	
	public function importajaxAction(){
		if(is_array($_FILES) AND !empty($_FILES) AND isset($_FILES['file'])){
			$pid = $this->getPost('pid');
			if(is_numeric($pid) AND $pid>0){
				try{
					$m = array();
					//读取文件
					$txtfile = $_FILES['file']['tmp_name'];
					$txtFileData = file_get_contents($txtfile);
					//处理编码问题
					$encoding = mb_detect_encoding($txtFileData, array('GB2312','GBK','UTF-16','UCS-2','UTF-8','BIG5','ASCII'));
					if($encoding != false){
						$txtFileData = iconv($encoding, 'UTF-8', $txtFileData);
					}else{
						$txtFileData = mb_convert_encoding ( $txtFileData, 'UTF-8','Unicode');
					}
					//开始处理
					$huiche=array("\n","\r");
					$replace='\r\n';
					$newTxtFileData=str_replace($huiche,$replace,$txtFileData); 
					$newTxtFileData_array = explode($replace,$newTxtFileData);
					foreach($newTxtFileData_array AS $line){
						if(strlen($line)>0){
							$m[]=array('pid'=>$pid,'card'=>$line,'addtime'=>time());
						}
					}
					if(!empty($m)){
						$u = $this->m_products_card->MultiInsert($m);
						if($u){
							//增加商品数量
							$addNum = count($m);
							$qty_m = array('qty' => 'qty+'.$addNum);
							$this->m_products->Where(array('id'=>$pid,'stockcontrol'=>1))->Update($qty_m,TRUE);
							$data = array('code' => 1, 'msg' => '成功');
						}else{
							$data = array('code' => 1004, 'msg' => '失败');
						}
					}else{
						$data = array('code' => 1003, 'msg' => '没有卡密存在','data'=>array());
					}
				}catch(\Exception $e) {
					$data = array('code' => 1002, 'msg' => $e->getMessage(),'data'=>array());
				}
			}else{
				$data = array('code' => 1001, 'msg' => '请选择商品','data'=>array());
			}
		}else{
			$data = array('code' => 1000, 'msg' => '上传内容为空,请重新上传','data'=>array());
		}
		Helper::response($data);
	}
}