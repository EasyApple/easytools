
<?php

class RSATool
{
	private $pi_key;
	private $pu_key;
	public function init()
	{

$private_key = '-----BEGIN RSA PRIVATE KEY-----  
MIICXQIBAAKBgQC3//sR2tXw0wrC2DySx8vNGlqt3Y7ldU9+LBLI6e1KS5lfc5jl  
TGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2klBd6h4wrbbHA2XE1sq21ykja/  
Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o2n1vP1D+tD3amHsK7QIDAQAB  
AoGBAKH14bMitESqD4PYwODWmy7rrrvyFPEnJJTECLjvKB7IkrVxVDkp1XiJnGKH  
2h5syHQ5qslPSGYJ1M/XkDnGINwaLVHVD3BoKKgKg1bZn7ao5pXT+herqxaVwWs6  
ga63yVSIC8jcODxiuvxJnUMQRLaqoF6aUb/2VWc2T5MDmxLhAkEA3pwGpvXgLiWL  
3h7QLYZLrLrbFRuRN4CYl4UYaAKokkAvZly04Glle8ycgOc2DzL4eiL4l/+x/gaq  
deJU/cHLRQJBANOZY0mEoVkwhU4bScSdnfM6usQowYBEwHYYh/OTv1a3SqcCE1f+  
qbAclCqeNiHajCcDmgYJ53LfIgyv0wCS54kCQAXaPkaHclRkQlAdqUV5IWYyJ25f  
oiq+Y8SgCCs73qixrU1YpJy9yKA/meG9smsl4Oh9IOIGI+zUygh9YdSmEq0CQQC2  
4G3IP2G3lNDRdZIm5NZ7PfnmyRabxk/UgVUWdk47IwTZHFkdhxKfC8QepUhBsAHL  
QjifGXY4eJKUBm3FpDGJAkAFwUxYssiJjvrHwnHFbg0rFkvvY63OSmnRxiL4X6EY  
yI9lblCsyfpl25l7l5zmJrAHn45zAiOoBrWqpM5edu7c  
-----END RSA PRIVATE KEY-----';  
  
$public_key = '-----BEGIN PUBLIC KEY-----  
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt  
3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl  
Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o  
2n1vP1D+tD3amHsK7QIDAQAB  
-----END PUBLIC KEY-----'; 

		//check keys 判断私钥、公钥是否可用，可用则返回 Resource id
		$this->pi_key =  openssl_pkey_get_private($private_key); 
		$this->pu_key = openssl_pkey_get_public($public_key);
	}

	//私钥加密  
	public function private_encrypt($data)
	{
		$encrypted = ""; 
		openssl_private_encrypt($data,$encrypted,$this->pi_key);
		$encrypted = base64_encode($encrypted);
		return $encrypted;  
	}
	
	//公钥解密 
	public function public_decrypt($encrypted)
	{
		$decrypted = ""; 
		openssl_public_decrypt(base64_decode($encrypted),$decrypted,$this->pu_key); 
		return $decrypted;  
	}

	//公钥加密  
	public function public_encrypt($data)
	{
		$encrypted = ""; 
		openssl_public_encrypt($data,$encrypted,$this->pu_key);
		$encrypted = base64_encode($encrypted);
		return $encrypted;  
	}
	
	//私钥解密 
	public function private_decrypt($encrypted)
	{
		$decrypted = ""; 
		openssl_private_decrypt(base64_decode($encrypted),$decrypted,$this->pi_key); 
		return $decrypted;  
	}	

}

function bin_to_ascii($bin)
{
	$ascii = "";
	$len = strlen($bin);
    for($i = 0; $i < ($len/8); $i++)
    {
        $ascii.= chr(intval(substr($bin,$i*8,8),2));
    }
 
    return $ascii;
}

?>


<html>
<body>

	<?php
		$rsaTool = new RSATool();
		$rsaTool->init();
		$userName = $_POST["name"];
		$deviceInfo = $_POST["info"];
		$licenseBase64 = $rsaTool->private_encrypt($deviceInfo);
		$licenseBin = base64_decode($licenseBase64);
		$licenseAscii = bin_to_ascii($licenseBin);
	?>

	Welcome <?php echo $userName; ?><br>
	DeviceInfo: <?php echo $deviceInfo; ?><br>
	LicenseBase64: <?php echo $licenseBase64; ?><br>
	LicenseAscii: <?php echo $licenseAscii; ?><br>

</body>
</html>

