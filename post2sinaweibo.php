<?php
//用update.json接口发送新浪微博，2.0接口。好像appid必须是自己的微博账号创建的才能用。
function postData($url, $data)      
    {      
        $ch = curl_init();      
        $timeout = 300;       
        curl_setopt($ch, CURLOPT_URL, $url);     
        curl_setopt($ch, CURLOPT_REFERER, "http://www.yourdomain.com");   //构造来路    
        curl_setopt($ch, CURLOPT_POST, true);      
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);      
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, "username:password");//此处用了明文密码，也可用下面两行代码加密密码传输，用BASE64加密username:password即可。需要设置headers参数
		//$headers = array( "Authorization: Basic ' . 'BASE64加密结果'" ); 
		//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);      
        $handles = curl_exec($ch);      
        curl_close($ch);      
        return $handles;      
    }
    $url = 'https://api.weibo.com/2/statuses/update.json';//POST指向的链接   
	$status = '测试';
	$data = array(  
	'status' => $status, 'source'=>'your AppID'
      );      
	$data_query = http_build_query($data,"","&");
    $json_data = postData($url, $data_query); 
	
?>
