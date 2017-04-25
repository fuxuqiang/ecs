<?php

namespace Includes\Classes\Captcha;

class Captcha
{
	// 生成验证码并显示在图片中
	public static function generate($imgW, $imgH, $charLen = 4) 
	{
		// 字体
		$fontSize = $imgH/2;
		$font = __DIR__.'/UbuntuMono-RI.ttf';
		// 生成验证码
		$char = array_merge(range('A','Z'), range('a','z'), range(1,9));
		$randKeys = array_rand($char, $charLen);
		shuffle($randKeys);
		$code = '';
		foreach($randKeys as $v){
			$code.=$char[$v];
		}
		// 赋值给SESSION
		session_start();
		$_SESSION['captcha'] = strtolower($code);
		// 生成画布并设置背景色
		$img = imagecreatetruecolor($imgW, $imgH);
		$imgColor = imagecolorallocate($img, 0xcc, 0xcc, 0xcc);
		imagefill($img, 0, 0, $imgColor);
		// 随机字体颜色
		$strColor = imagecolorallocate($img, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
		// 将码值写入画布
		imagettftext(
			$img, 
			$fontSize, 
			0,//mt_rand(-10,10), 
			mt_rand(1, $imgW/$charLen), 
			mt_rand($imgH*0.8, $imgH*0.9), 
			$strColor, 
			$font, 
			$code
		);
		// 显示图片
		//ob_clean();
		header('content-type:image/png');
		imagepng($img);
		// 销毁
		imagedestroy($img);
	}

	public static function check($captcha)
	{
		if ($captcha == $_SESSION['captcha']) {
			return true;
		}
		return false;
	}
}