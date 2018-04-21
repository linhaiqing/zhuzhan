<?php
namespace ext\pay;
class jifuzf extends movepay
{
	public static function init($pay_data)
	{
		if (!self::$handel) {
			return self::$handel = new self($pay_data);
		} else {
			return self::$handel;
		}
	}

	function rsa_publickey_encrypt($data)
	{
		$pubk = base64_decode('LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0NCk1JSUJJakFOQmdrcWhraUc5dzBCQVFFRkFBT0NBUThBTUlJQkNnS0NBUUVBOFkrUXVZbXNCSWRmaW1OVXdITloNClVaWWVtREJHUkJKay8zVjNkSlN5NGl0aGF5cHdXdEFoc29oN2RvZUN0SUxxN082bXB4cE1lUDFrLzdzbzlsNTANCjFnVFBYT1UwR3ovL2FZQWFUMGYyY1RodTd4T3ZWczVGeWgvQU1WengxSEZJQzRXanBOc0hPWWtMWUJubVdNd2gNCisyY01lT1duLytPbHpnbnpNZXZsR0J3bzZqcVhsbGttcUkydXdXQjR6NC9uUmEwUUV1ZkJrUE5vcFkzckloZDINCkVpeFFNUnZBbEJ3V3A2bFp2bDlnRjJ2ZG53K2hrd1hkUU5QdDVOWnVOTjZoSWx4eU9xdWZ2N0dMYkZpbnp3MXkNCmJ5VW1maWNLNE04U1k0cWZna2hoUk50Sm1BdW5OYWVPcVB2Yk9HTENIUVJaOVcyVjBwWUhCS3V0N1JWQXRvWlENCjNRSURBUUFCDQotLS0tLUVORCBQVUJMSUMgS0VZLS0tLS0=');
		$pubk = openssl_pkey_get_public($pubk);
		$en = "";
		$en1 = openssl_public_encrypt($data, $en, $pubk, OPENSSL_PKCS1_PADDING);
		return $en;
	}

	function rsa_privatekey_decrypt($data)
	{
		$prik = base64_decode('LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tDQpNSUlDZUFJQkFEQU5CZ2txaGtpRzl3MEJBUUVGQUFTQ0FtSXdnZ0plQWdFQUFvR0JBTGlvOUkyQmRBaTRtSUlEDQpmUDhuV3NTZXhESmNvcXpyVGlidzR6Rm5obkl3enJGMmVKRFQwMUdUclVyYlI0NVNuN29rQldibXpWY3U5Vmg5DQpLY0xyOUtKNFVEWVNxbFpFUnoyRVJuL1JxQVR3YjNoeWlBU3VwOFY0bGJobncxb09XU2JJbU0xRFVCSS8wSWxpDQpCQkk5WlNSWGJadjNvOHBpWmkwdGQ3YjRhWElkQWdNQkFBRUNnWUVBcWVBMGFlRkhYNmFDRmtrak9vOTN2eDNYDQpuQVVBV2xkMTQva0M2TFkyUnVLRG8xbzdlL0NDbks5Z1lsYnJzeEJyaEpBU3NDRnZrZDdQTWdLaTRvQ3k0UnNRDQpwdUQyVjZpRXJrYkt6aU4yV2N2cVh3djkxK05QQkdJL25MVUNTOXRFMUlRY3FvTm1aSTc2VDJaQzVMcmxnMjdkDQo0cFRST2tHRkNMTmNwa2FSWVBrQ1FRRHBEOXlsZ2xKRk9XWSt1cDlzRW5GRWREa2NMSmFMMDBPc3JkRSswbExkDQpyV3JaZlV1azJUbTVjWTU4bFAzWjZxS1VXSXJaaHVNOWZycktSTGRuUmdudkFrRUF5dFdUZHQzRi9ZN1lCNXVJDQpTY2RKREllUHR4MVBSU3RJVXVZM3FnZE10K1AvRmtjQVRGVGNIcXNDaVcrSzRlS2N5R3NZbUtwNUtFbDdHVkd1DQp0eGVBc3dKQkFLZ2hKejNHNis3QVg4QTFCVGtEZk82eklWZnYycEw0VDBQTkNMeFhJaXlGSkNYTnU3b3FjdmdTDQpzWVd5UGdic3UyaGxENVdwTjFsdmlQVE42Ymo0ejhVQ1FEL2FJalQrTmJEUGdmUkxJd2xPd2hFK3I2Qk9pc0RVDQpra3oraWpGdFlOcFczTnVQZUdtUElVaEtQMTh5alA3MURyUU0rczQ4aEMySmpFWDVMODRWaVA4Q1FRQ1hVWE9tDQpEQkkvV2N6YUE5UFB2NSt3QkYzdHZsNXA3VnprMkFjQUZ0VndNemtkdGR3azl0UFpwRXR3SUNVQUdPUStzSXFzDQpxbkp0VUx1aWxadzNncFY5DQotLS0tLUVORCBQUklWQVRFIEtFWS0tLS0t');
		$prik = openssl_get_privatekey($prik);
		openssl_private_decrypt($data, $de, $prik, OPENSSL_PKCS1_PADDING);
		return $de;
	}

	function rsa_encrypt($data, $rsa_bit = 2048)
	{
		$inputLen = strlen($data);
		$offSet = 0;
		$i = 0;
		$maxDecryptBlock = $rsa_bit / 8 - 11;
		$en = '';
		while ($inputLen - $offSet > 0) {
			if ($inputLen - $offSet > $maxDecryptBlock) {
				$cache = $this->rsa_publickey_encrypt(substr($data, $offSet, $maxDecryptBlock));
			} else {
				$cache = $this->rsa_publickey_encrypt(substr($data, $offSet, $inputLen - $offSet));
			}
			$en = $en . $cache;
			$i++;
			$offSet = $i * $maxDecryptBlock;
		}
		return $en;
	}

	function rsa_decrypt($data, $rsa_bit = 2048)
	{
		$inputLen = strlen($data);
		$offSet = 0;
		$i = 0;
		$maxDecryptBlock = $rsa_bit / 8;
		$de = '';
		$cache = '';
		while ($inputLen - $offSet > 0) {
			if ($inputLen - $offSet > $maxDecryptBlock) {
				$cache = $this->rsa_privatekey_decrypt(substr($data, $offSet, $maxDecryptBlock));
			} else {
				$cache = $this->rsa_privatekey_decrypt(substr($data, $offSet, $inputLen - $offSet));
			}
			$de = $de . $cache;
			$i = $i + 1;
			$offSet = $i * $maxDecryptBlock;
		}
		return $de;
	}

	function getpayruls($username, $orderno, $amount, $txnType)
	{
		$data = array('userid' => $username, 'money' => $amount, 'customerid' => $orderno, 'temp1' => '12', 'temp2' => $txnType);
		$posts = $this->AEScode(json_encode($data));
		$param = base64_encode($this->rsa_encrypt($posts[0]));
		$body['body'] = $posts[1];
		$body['encryptKey'] = $param;
		$body['flag'] = 'EISKELIALT0394L2D';
		$url = "http://pay.jifuzf.com/mobile/index/payurlmf";
		$result2 = $this->curl_json($url, json_encode($body));
		$result = json_decode($result2, true);
		if (!empty($result)) {
			$key = $this->rsa_decrypt(base64_decode($result['encryptKey']));
			$result = json_decode($this->AEScode($result['body'], $key, "DE"), true);
			return $result;
		}
		return array("url" => "", "flag" => "0", "errorMsg" => "发生错误！！");
	}

	function getpaydencode()
	{
		$josn_comm = $_POST;
		if (!empty($postdata)) {
			$bacommand = base64_decode($josn_comm['encryptKey']);
			$flg_user_de = $josn_comm['flag'];
			$key = $this->rsa_decrypt($bacommand);
			$body = json_decode($this->AEScode($josn_comm['body'], $key, "DE"), true);
			return $body;
		}
	}

	function AEScode($string, $key = "", $type = "EN")
	{
		if ($type == "DE") {
			$keyStr = $key;
		} else {
			$keyStr = $this->getRandom(16);
		}
		$aes = new AES();
		$aes->set_key($keyStr);
		$aes->require_pkcs5();
		if ($type == "DE") {
			return $aes->decrypt($string);
		}
		$encText = $aes->encrypt($string);
		return array($keyStr, $encText);
	}

	function getRandom($length = 2)
	{
		$string = "";
		$str = "abcdefghijklmnopqrstuvwsyz";
		for ($i = 0; $i < $length; $i++) {
			$string .= $str[rand(0, strlen($str) - 1)];
		}
		return $string;
	}

	function SinParamsToString($params)
	{
		$sign_str = '';
		ksort($params);
		foreach ($params as $key => $val) {
			if ($key == 'signature') {
				continue;
			}
			$sign_str .= sprintf("%s=%s&", $key, $val);
		}
		return substr($sign_str, 0, strlen($sign_str) - 1);
	}

	function curl_json($url, $data)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8', 'Content-Length: ' . strlen($data)));
		$output = curl_exec($ch);
		return $output;
	}

	function postData($url, $data)
	{
		$ch = curl_init();
		$timeout = 300;
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("content-type: application/x-www-form-urlencoded;charset=UTF-8",));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$handles = curl_exec($ch);
		curl_close($ch);
		return $handles;
	}

	function submit()
	{
		$bank = isset($_POST['bank']) ? trim($_POST['bank']) : "alipay";
		if ($bank == "wechat") {
			$bank = "weixin";
		}
		$username = $this->pay_data['auth_data']['username'];
		$orderno = $this->pay_data['orderno'];
		$amount = $this->pay_data['amount'];
		$result = $this->getpayruls($username, $orderno, $amount, $bank);
		$code = @$result["url"];
		if ($code) {
			ajax($code, 1);
		} else {
			ajax($result["info"]);
		}
	}

	public function notify_url()
	{
		$josn_comm = $_POST;
		$bool = 'failure';
		if (!empty($josn_comm)) {
			$bacommand = base64_decode($josn_comm['encryptKey']);
			$key = $this->rsa_decrypt($bacommand);
			$body = json_decode($this->AEScode($josn_comm['body'], $key, "DE"), true);
			if ($body['info'] == 'ok') {
				$this->success();
				$bool = 'success';
			}
		}
		echo $bool;
	}

	public function return_url()
	{
	}
} 