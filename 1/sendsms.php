<?php

header("Content-Type: text/html; charset=utf-8");
 $appid = "";
 $appsecret = "";
$phone="18911773156";//收消息人
$randcode="123456";//验证码内容6位数字
$exp_time="2013-11-2 0:0:0";//有效期
 $timestamp = date('Y-m-d H:i:s');


$tokenAPI = "https://oauth.api.189.cn/emp/oauth2/v2/access_token";


 $ch=curl_init();
  curl_setopt_array(
    $ch,
    array(
      CURLOPT_URL=>$tokenAPI,
      CURLOPT_RETURNTRANSFER=>true,
      CURLOPT_POST=>true,
     CURLOPT_POSTFIELDS=>'app_id='.$appid.'&app_secret='.$appsecret.'&grant_type=client_credentials'
    )
  );
  $content=curl_exec($ch);
curl_close($ch);
//echo $content;
 $obj=json_decode($content);
$access_token=$obj->access_token;





         $url = "http://api.189.cn/v2/dm/randcode/token?";

        $param['app_id']= "app_id=".$appid;
        $param['access_token'] = "access_token=".$access_token;
        $param['timestamp'] = "timestamp=".$timestamp;
        ksort($param);
        $plaintext = implode("&",$param);
        $param['sign'] = "sign=".rawurlencode(base64_encode(hash_hmac("sha1", $plaintext, $appsecret, $raw_output=true)));
        ksort($param);
       
        $url .= implode("&",$param);
       $ch = curl_init($url);
        
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        $resultArray = json_decode($result,true);
        $token = $resultArray['token'];



$sendurl="http://api.189.cn/v2/dm/randcode/sendSms";

     
  $sendparam['app_id']= "app_id=".$appid;
        $sendparam['access_token'] = "access_token=".$access_token;
        $sendparam['timestamp'] = "timestamp=".$timestamp;
        $sendparam['token'] = "token=".$token;
        $sendparam['phone'] = "phone=".$phone;
        $sendparam['randcode'] = "randcode=".$randcode;
       
        if(isset($exp_time))
            $sendparam['exp_time'] = "exp_time=".$exp_time;
        ksort($sendparam);
        $sendplaintext = implode("&",$sendparam);
        $sendparam['sign'] = "sign=".rawurlencode(base64_encode(hash_hmac("sha1", $sendplaintext, $appsecret, $raw_output=true)));
        ksort($sendparam);
        $str = implode("&",$sendparam);

         $ch = curl_init($sendurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $data = curl_exec($ch);
     echo $data;
    curl_close($ch);




?>

