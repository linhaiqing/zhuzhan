<?php
ini_set('date.timezone', 'Asia/Shanghai');
wcg::setConfig(["account" => "WCG-7P87-E29R-9BWH-6UMAU", "publicKey" => "6a63f08c57226a253722dbda1fa7367b8b0b1929f9a74ef08a69de749e9f2305", "password" => "brother observe fantasy raw consider despair garden poet whole upset write pierce"]);
$data = wcg::sendTrans("WCG-2ZM5-AC8A-84KD-DLKFM", 0.13);
print_r($data);

class wcg
{
	public static $config = ["account" => "", "password" => "", "publicKey" => ""];

	public static function setConfig($config)
	{
		self::$config = $config;
	}

	public static function getTransLog()
	{
		$password = self::$config["password"];
		$account = self::$config["account"];
		$data = self::geBlockchainTransactions($account);
		$data = $data["transactions"];
		$ret_data = [];
		foreach ($data as $key => $val) {
			$type = (strtolower($val["senderRS"]) == strtolower($account)) ? 1 : 0;
			if ($type == 0) {
				if (isset($val["attachment"]["encryptedMessage"]["data"])) {
					$data[$key]["attachment"]["decode"] = self::decode_data($val["attachment"]["encryptedMessage"]["data"], $val["senderPublicKey"], $password, $val["attachment"]["encryptedMessage"]["nonce"]);
				} else {
					$data[$key]["attachment"]["decode"] = "";
				}
				$ret_data[] = ["type" => (strtolower($val["senderRS"]) == strtolower($account)) ? 1 : 0, "num" => $val["amountNQT"] / 100000000, "fee" => $val["feeNQT"] / 100000000, "senderRS" => $val["senderRS"], "decode" => $data[$key]["attachment"]["decode"], "height" => $val["height"], "confirmations" => $val["confirmations"], "timestamp" => $val["timestamp"], "date" => date("Y-m-d H:i:s", $val["timestamp"] + 1385294408), "signature" => $val["signature"], "transaction" => $val["transaction"],];
			}
		}
		if (isset($_GET["all"])) {
			return json_encode($data);
		} else {
			return json_encode($ret_data);
		}
	}

	private static function decode_data($data, $PublicKey, $password, $nonce)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(CURLOPT_URL => "https://qukuai.movesay.com/wcg/show.php", CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => "PublicKey=$PublicKey&password=" . base64_encode($password) . "&encrypted=$data&nonce=$nonce", CURLOPT_HTTPHEADER => array("cache-control: no-cache", "content-type: application/x-www-form-urlencoded"),));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}

	private static function geBlockchainTransactions($account)
	{
		$curl = curl_init();
		$url = "https://www.wcgacc.com/wcg?requestType=getBlockchainTransactions&account=$account&firstIndex=0&lastIndex=100&random=0." . rand(10000000, 9999999) . rand(100000, 99999) . rand(100000, 99999);
		curl_setopt_array($curl, array(CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_HTTPHEADER => array("cache-control: no-cache", "content-type: application/x-www-form-urlencoded"),));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			echo "cURL Error #:" . $err;
			exit();
		} else {
			$data = json_decode($response, true);
			if (!$data) {
				echo $response;
				echo "\n json decode error!";
				exit();
			}
			return $data;
		}
	}

	public static function sendTrans($recipient, $num)
	{
		$password = self::$config["password"];
		$publicKey = self::$config["publicKey"];
		$num = $num * 100000000;
		$curl = curl_init();
		curl_setopt_array($curl, array(CURLOPT_URL => "https://www.wcgacc.com/wcg?requestType=sendMoney", CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => "recipient=$recipient&deadline=1440&phased=false&phasingLinkedFullHash=&phasingHashedSecret=&phasingHashedSecretAlgorithm=2&publicKey=$publicKey&feeNQT=0&amountNQT=$num", CURLOPT_HTTPHEADER => array("cache-control: no-cache", "content-type: application/x-www-form-urlencoded", "postman-token: 81da2470-f9b4-66ec-4c5d-3ece43c22908"),));
		$response = curl_exec($curl);
		curl_close($curl);
		if ($ret = json_decode($response, true)) {
			if (isset($ret["unsignedTransactionBytes"])) {
				$transactionBytes = self::serverSign($ret["unsignedTransactionBytes"], $password);
				return self::boardstTrans($transactionBytes);
			} else {
				return false;
			}
		} else {
			echo $response;
			exit();
		}
	}

	private static function boardstTrans($transactionBytes)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(CURLOPT_URL => "https://www.wcgacc.com/wcg?requestType=broadcastTransaction", CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => "transactionBytes=$transactionBytes&prunableAttachmentJSON=%7B%22version.OrdinaryPayment%22%3A0%7D", CURLOPT_HTTPHEADER => array("cache-control: no-cache", "content-type: application/x-www-form-urlencoded"),));
		$response = curl_exec($curl);
		curl_close($curl);
		if ($ret = json_decode($response, true)) {
			if (isset($ret["fullHash"])) {
				return $ret;
			} else {
				return false;
			}
		} else {
			echo $response;
			exit();
		}
	}

	private static function serverSign($transactionBytes, $password)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(CURLOPT_URL => "https://qukuai.movesay.com/wcg/sign.php?transactionBytes=$transactionBytes&password=" . base64_encode($password), CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_HTTPHEADER => array("cache-control: no-cache", "postman-token: 5ebb7067-2997-8aab-8fac-6df547b46a92"),));
		$response = curl_exec($curl);
		curl_close($curl);
		if ($response) {
			return $response;
		} else {
			echo "serverSign Error!";
			exit();
		}
	}
}