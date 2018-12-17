<?php
/**
 * File: F_Img.php
 * Author: 资料空白
 * Date: 2016-11-11再整理
 */

 
/*
 *  Create thumb
 *  @access public
 *  $sourceImg: source image
 *  $destination: 保存的目标路径
 *  $saveName: 保存的新图片名
 *  $targetWidth  缩略图宽度
 *  $targetHeight 缩略图高度
 *  @return full path + file name if success 
 *  Remark: 该函数使用的 getExtension 与 createRDir 存在于 F_File.php 中
 */
if ( ! function_exists('createThumb')){
	function createThumb($source, $destination, $saveName, $targetWidth, $targetHeight){
		// Get image size
		$originalSize = getimagesize($source);
		
		// Set thumb image size
		$targetSize = setWidthHeight($originalSize[0], $originalSize[1], $targetWidth, $targetHeight);
		
		// Get image extension
		$ext = getExtension($source);
		
		// Determine source image type
		if($ext == 'gif'){
			$src = imagecreatefromgif($source);
		}elseif($ext == 'png'){
			$src = imagecreatefrompng($source);
		}elseif ($ext == 'jpg' || $ext == 'jpeg'){
			$src = imagecreatefromjpeg($source);
		}else{
			return 'Unknow image type !';
		}
		
		// Copy image
		$dst = imagecreatetruecolor($targetSize[0], $targetSize[1]);
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $targetSize[0], $targetSize[1],$originalSize[0], $originalSize[1]);    
		
		if(!file_exists($destination)){
			if(!createRDir($destination)){
				return 'Unabled to create destination folder !';
			}
		}
		
		// destination + fileName
		$thumbName = $destination.'/'.$saveName.'.'.$ext;
		
		if($ext == 'gif'){
			imagegif($dst, $thumbName);
		}else if($ext == 'png'){
			imagepng($dst, $thumbName);
		}else if($ext == 'jpg' || $ext == 'jpeg'){
			imagejpeg($dst, $thumbName, 100);
		}else{
			return 'Fail to create thumb !';
		}
		
		imagedestroy($dst);
		imagedestroy($src);
		return $thumbName;
	}
}

	/*
	 *  Set thumb image width and height
	 */
if ( ! function_exists('setWidthHeight')){
	function setWidthHeight($width, $height, $maxWidth, $maxHeight) {
		if($width > $height){
			if($width > $maxWidth){
				$difinwidth = $width/$maxWidth;
				$height = intval($height/$difinwidth);
				$width  = $maxWidth;
				
				if($height > $maxHeight){
					$difinheight = $height/$maxHeight;
					$width  = intval($width/$difinheight);
					$height = $maxHeight;
				}
			}else{
				if($height > $maxHeight){
					$difinheight = $height/$maxHeight;
					$width  = intval($width/$difinheight);
					$height = $maxHeight;
				}
			}
		}else{
			if($height > $maxHeight){
				$difinheight = $height/$maxHeight;
				$width  = intval($width/$difinheight);
				$height = $maxHeight;
				
				if($width > $maxWidth){
					$difinwidth = $width/$maxWidth;
					$height = intval($height/$difinwidth);
					$width  = $maxWidth;
				}
			}else{
				if($width > $maxWidth){
					$difinwidth = $width/$maxWidth;
					$height = intval($height/$difinwidth);
					$width  = $maxWidth;
				}
			}
		}
		
		$final = array($width, $height);
		return $final;
	}
}

	/*
	 *  Functionality: Add watermark
	 *  @Params:
			$source: source img with path
			$destination: target img with path
			$watermarkPath: water mark img
		@Retrun: image with watermark
	 */
if ( ! function_exists('addWatermark')){
	function addWatermark($source, $destination, $watermarkPath){
		list($owidth,$oheight) = getimagesize($source);
		$width = $height = 300;
		$im = imagecreatetruecolor($width, $height);
		$img_src = imagecreatefromjpeg($source);
		imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
		$watermark = imagecreatefrompng($watermarkPath);
		list($w_width, $w_height) = getimagesize($watermarkPath);
		$pos_x = $width - $w_width;
		$pos_y = $height - $w_height;
		imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
		imagejpeg($im, $destination, 100);
		imagedestroy($im);
	}
}

if ( ! function_exists('ImageToJPG')){
	function ImageToJPG($srcFile,$dstFile,$towidth,$toheight) 
	{ 
		$quality=80; 
		$data = @GetImageSize($srcFile); 
		switch ($data['2']) 
		{ 

		case 1: 

		$im = imagecreatefromgif($srcFile); 
		break; 
		case 2: 

		$im = imagecreatefromjpeg($srcFile); 
		break; 
		case 3: 
		$im = imagecreatefrompng($srcFile); 

		break; 

		case 6: 

		$im = ImageCreateFromBMP( $srcFile ); 

		break; 
		} 

		$srcW=@ImageSX($im); 
		$srcH=@ImageSY($im); 
		$dstX=$towidth; 
		$dstY=$toheight; 

		$ni=@imageCreateTrueColor($dstX,$dstY); 

		@ImageCopyResampled($ni,$im,0,0,0,0,$dstX,$dstY,$srcW,$srcH); 
		@ImageJpeg($ni,$dstFile,$quality); 
		@imagedestroy($im); 
		@imagedestroy($ni); 
	} 
}

if ( ! function_exists('base64EncodeImage')){
	function base64EncodeImage($image_file) {
	  $base64_image = '';
	  $image_info = getimagesize($image_file);
	  $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
	  $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
	  return $base64_image;
	}
}