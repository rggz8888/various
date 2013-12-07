<?php
 /*
 * 7384��΢����������ͼƬϵͳ V0.1
 * ============================================================================
 * ��Ȩ����: 7384ȫ����������
 * ��վ��ַ: http://www.7384.org��
 * ��������: admin@ewuyi.net��
 * ----------------------------------------------------------------------------
 * ȫ�������й������� http://buy.7384.org
 * �ɼ�/���ʲ�ѯϵͳ�� http://ewuyi.net/buypay/
 * ΢��Ϣ/��Ƹ����ϵͳ�� http://ewuyi.net/buypay/
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

function makeimger($text = "���ݻ�ȡʧ��...",$types,$ids){
	$setStyle = 'BC221C|DCF2F4'; #������ɫ,Ҳ���Կ���Ϊҳ���ѡ�񲢴����������
	$haveBrLinker = ""; #����ʹ�÷ָ���
	$fontFile = 'simfang.ttf'; #�����ļ���,��fontĿ¼��,Ҳ���Կ���Ϊҳ���ѡ�񲢴����������
	$userStyle = explode('|', $setStyle); #�ֿ���ɫ
	$text = substr($text, 0, 1000); #��ȡǰһ����ַ�
	$text = iconv("GB2312", "UTF-8",$text); 
	$imgpath = "".$types."/"; #ͼƬ��ŵ�ַ
	if(!is_dir($imgpath)){ mkdir($imgpath); }
	$imgfile =  $imgpath . $ids . '.gif';

	if(file_exists($imgfile))
	{
	return $imgfile;	
	}
	else
	{
	//�����Ǳ߿��ȣ�����ǰ̨���ݲ���
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
	$im = imagecreatetruecolor($canvasWidth, $canvasHeight); #���廭��
	$colorArray = str2rgb($userStyle[1]);
	imagefill($im, 0, 0, imagecolorallocate($im, $colorArray['red'], $colorArray['green'], $colorArray['blue']));
	
	$colorArray = str2rgb('666666');
	$colorLine = imagecolorallocate($im, $colorArray['red'], $colorArray['green'], $colorArray['blue']);
	$padding = 3;
	$x1 = $y1 = $x4 = $y2 = $padding;
	$x2 = $x3 = $canvasWidth - $padding - 1;
	$y3 = $y4 = $canvasHeight - $padding - 1;
	//���Կ���Ϊҳ���ѡ�񲢴����������,ѡ���Ƿ���ʾ�߿��Լ���ɫ��
	imageline($im, $x1, $y1, $x2, $y2, $colorLine);
	imageline($im, $x2, $y2, $x3, $y3, $colorLine);
	imageline($im, $x3, $y3, $x4, $y4, $colorLine);
	imageline($im, $x4, $y4, $x1, $y1, $colorLine);

	//����·����,Ҳ���Կ���Ϊҳ���ѡ�񲢴����������
	$fontStyle = 'font/' . $fontFile;
	if(!is_file($fontStyle)){
		exit('����ѡ�������ļ�Ŷ!');
	}
	
	//д���ĸ��漴����
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
	//Ҳ���Կ���Ϊҳ���ѡ�񲢴����������,������ʾ�����֣��Լ��Ƿ���ʾ����ʾλ�õȡ�
	$text = '��ͼ��17386.Net �Զ����ɣ���������վ�۵�!';
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
//���ﻹ��������������ɫ��������ɫ���߿���ɫ�������С���߿��Ƿ���ʾ���߿��ȣ�ѡ����ʾ�����壬�Լ�����еİ�Ȩ��...
   // echo"<span style='color:red;'>����������!</span>"; 
   // echo"<form action='' method='post'>"; 
    //echo "��������: <textarea name='gg' cols='80' rows='5' id='nzhan'>�����ġ�</textarea><br>";
    //echo"<input type='submit' name='Submit' value='����'>"; 
    //echo"</form>"; 
    //exit();
//}else{
    $imghtml=makeimger($gg,"WeiBo",time().rand(1111,9999));
	$gg="";
 		 echo"<p><img src=".$imghtml."></p>";
 
}
?>