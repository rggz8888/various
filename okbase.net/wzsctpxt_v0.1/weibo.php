<?php
 /*
 * 7384长微博文字生成图片系统 V0.1
 * ============================================================================
 * 版权所有: 7384全国公交网；
 * 网站地址: http://www.7384.org；
 * 作者邮箱: admin@ewuyi.net；
 * ----------------------------------------------------------------------------
 * 全国各城市公交网： http://buy.7384.org
 * 成绩/工资查询系统： http://ewuyi.net/buypay/
 * 微信息/招聘发布系统： http://ewuyi.net/buypay/
 * ============================================================================
*/

function str_div($str, $width = 10){
	$strArr = array();
	$len = strlen($str);
	$count = 0;
	$flag = 0;
	while($flag < $len){
		if(ord($str[$flag]) > 128){
			$count += 1;
			$flag += 3;
		}
		else{
			$count += 0.5;
			$flag += 1 ;
		}
		if($count >= $width){
			$strArr[] = substr($str, 0, $flag);
			$str = substr($str, $flag);
			$len -= $flag;
			$count = 0;
			$flag = 0;
		}
	}
	$strArr[] = $str;
	return $strArr;
}

function str2rgb($str)
{
	$color = array('red'=>0, 'green'=>0, 'blue'=>0);
	$str = str_replace('#', '', $str);
	$len = strlen($str);
	if($len==6){
		$arr=str_split($str,2);
		$color['red'] = (int)base_convert($arr[0], 16, 10);
		$color['green'] = (int)base_convert($arr[1], 16, 10);
		$color['blue'] = (int)base_convert($arr[2], 16, 10);
		return $color;
	}
	if($len==3){
		$arr=str_split($str,1);
		$color['red'] = (int)base_convert($arr[0].$arr[0], 16, 10);
		$color['green'] = (int)base_convert($arr[1].$arr[1], 16, 10);
		$color['blue'] = (int)base_convert($arr[2].$arr[2], 16, 10);
		return $color;
	}
	return $color;
}

function makeimger($text = "内容获取失败...",$types,$ids){
	$setStyle = 'BC221C|DCF2F4'; #设置颜色,也可以开发为页面可选择并传递这个参数
	$haveBrLinker = ""; #超长使用分隔符
	$fontFile = 'simfang.ttf'; #字体文件名,放font目录下,也可以开发为页面可选择并传递这个参数
	$userStyle = explode('|', $setStyle); #分开颜色
	$text = substr($text, 0, 1000); #截取前一万个字符
	$text = iconv("GB2312", "UTF-8",$text); 
	$imgpath = "".$types."/"; #图片存放地址
	if(!is_dir($imgpath)){ mkdir($imgpath); }
	$imgfile =  $imgpath . $ids . '.gif';

	if(file_exists($imgfile))
	{
	return $imgfile;	
	}
	else
	{
	//这里是边框宽度，可以前台传递参数
	$paddingTop = 20;
	$paddingLeft = 15;
	$paddingBottom = 60;
	$copyrightHeight = 0;
	
	$canvasWidth = 616;
	$canvasHeight = $paddingTop + $paddingBottom + $copyrightHeight;
	
	$fontSize = 16;
	$lineHeight = intval($fontSize * 1.8);
	
	$textArr = array();
	$tempArr = explode("\n", trim($text));
	$j = 0;
	foreach($tempArr as $v){
		$arr = str_div($v, 25);
		$textArr[] = array_shift($arr);
		foreach($arr as $v){
			$textArr[] = $haveBrLinker . $v;
			$j ++;
			if($j > 100){ break; }
		}
		$j ++;
		if($j > 100){ break; }
	}
	
	$textLen = count($textArr);
	
	$canvasHeight = $lineHeight * $textLen + $canvasHeight;
	$im = imagecreatetruecolor($canvasWidth, $canvasHeight); #定义画布
	$colorArray = str2rgb($userStyle[1]);
	imagefill($im, 0, 0, imagecolorallocate($im, $colorArray['red'], $colorArray['green'], $colorArray['blue']));
	
	$colorArray = str2rgb('666666');
	$colorLine = imagecolorallocate($im, $colorArray['red'], $colorArray['green'], $colorArray['blue']);
	$padding = 3;
	$x1 = $y1 = $x4 = $y2 = $padding;
	$x2 = $x3 = $canvasWidth - $padding - 1;
	$y3 = $y4 = $canvasHeight - $padding - 1;
	//可以开发为页面可选择并传递这个参数,选择是否显示边框以及颜色。
	imageline($im, $x1, $y1, $x2, $y2, $colorLine);
	imageline($im, $x2, $y2, $x3, $y3, $colorLine);
	imageline($im, $x3, $y3, $x4, $y4, $colorLine);
	imageline($im, $x4, $y4, $x1, $y1, $colorLine);

	//字体路径，,也可以开发为页面可选择并传递这个参数
	$fontStyle = 'font/' . $fontFile;
	if(!is_file($fontStyle)){
		exit('请先选择字体文件哦!');
	}
	
	//写入四个随即数字
	$colorArray = str2rgb($userStyle[0]);
	$fontColor = imagecolorallocate($im, $colorArray['red'], $colorArray['green'], $colorArray['blue']);
	
	foreach($textArr as $k=>$text){
		$offset = $paddingTop + $lineHeight * ($k + 1) - intval(($lineHeight-$fontSize) / 2);
		imagettftext($im, $fontSize, 0, $paddingLeft, $offset, $fontColor, $fontStyle, $text);
	}
	
	$fontColor = imagecolorallocate($im, 0, 0, 0);
	$offset += 18;
	$text = '----------------------------------------------------------------------------------------------';
	imagettftext($im, 10, 0, $paddingLeft, $offset, $fontColor, $fontStyle, $text);
	
	$offset += 28;
	$fontColor = imagecolorallocate($im, 255, 0, 0);
	//也可以开发为页面可选择并传递这个参数,比如显示的文字，以及是否显示，显示位置等。
	$text = '本图由17386.Net 自动生成，但不代表本站观点!';
	$text = iconv("GB2312", "UTF-8",$text); 
	imagettftext($im, 16, 0, $paddingLeft + 20, $offset, $fontColor, $fontStyle, $text);

	imagegif($im, $imgfile);
	imagedestroy($im);
	//echo $imgfile;
    	//exit($imgfile);
	}
	return $imgfile;
}

//$gg=$_POST['gg'];
$gg = "abcd";
//if($gg==""){
//这里还可以增加字体颜色，背景颜色，边框颜色，字体大小，边框是否显示，边框宽度，选择显示的字体，以及最后行的版权等...
   // echo"<span style='color:red;'>请输入内容!</span>"; 
   // echo"<form action='' method='post'>"; 
    //echo "输入内容: <textarea name='gg' cols='80' rows='5' id='nzhan'>输入文。</textarea><br>";
    //echo"<input type='submit' name='Submit' value='生成'>"; 
    //echo"</form>"; 
    //exit();
//}else{
    $imghtml=makeimger($gg,"WeiBo",time().rand(1111,9999));
	$gg="";
 		 echo"<p><img src=".$imghtml."></p>";
 
}
?>