<?php
namespace Aliyunoss;
class Aliyunoss{
	private $bucket;
	private $accessKeyId;
	private $accessKeySecret;
	private $endpoint;
	private $ossClient;
	
	function __construct(){
		\Yaf\Loader::import(LIB_PATH.'/Aliyunoss/index.php');
		if(DEBUG){
			$this->bucket='mtrp2p-test';
			$this->endpoint='oss-cn-shenzhen.aliyuncs.com';//外网
		}else{
			$this->bucket='mtrp2p';
			$this->endpoint='oss-cn-shenzhen-internal.aliyuncs.com';//内网
		}
		$this->accessKeyId='LTAIy5g9i2n6CmsB';
		$this->accessKeySecret='6XNoSDfv7RKBi79uJJCh3zN8wzlgRh';
		
		
		$this->ossClient=new \OSS\OssClient($this->accessKeyId,$this->accessKeySecret,$this->endpoint);
	}
	
	public function Updateimg($files,$folder){
		if(!empty($files) and !empty($folder)){
			$ext = pathinfo($files['name']);
			$ext = strtolower($ext['extension']);
			$allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
			if(!in_array($ext, $allow_type)){
			  return array('code'=>0,'msg'=>'格式有误');
			}
			$tempFile = $files['tmp_name'];
			$targetPath  = 'upload/'.$folder.'/'.date('Ymd');
			$filename=date("His");
			$new_file_name = $filename.'.'.$ext;
			$targetFile = $targetPath .'/'. $new_file_name;	
			$ok=$this->ossClient->uploadFile($this->bucket, $targetFile, $tempFile);
			if(!$ok){
				$data=array('code'=>0,'msg'=>'File is not exist');
			} else {				
				$img = 'upload/'.$folder.'/'.date('Ymd').'/'.$new_file_name;
				$data['code'] = 1;
				$data['img'] =$img ;
			}
		}else{
			$data=array('code'=>0,'msg'=>'File is not exist');
		}
		return $data;
	}

	//兼容版 用于数据流
	public function Updateimg2($files,$folder){
		if(!empty($files) and !empty($folder)){
			$ok=$this->ossClient->putObject($this->bucket, $folder, $files);
			if(!$ok){
				$data=array('code'=>0,'msg'=>'File is not exist');
			} else {				
				$data['code'] = 1;
				$data['img'] = $folder;
			}
		}else{
			$data=array('code'=>0,'msg'=>'File is not exist');
		}
		return $data;
	}

}