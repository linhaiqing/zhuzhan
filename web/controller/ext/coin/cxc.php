<?php
namespace ext\coin;
class cxc
{
	static public $host = "http://101.201.64.13:8235";

	public static function createAddress($secret)
	{
		$params = ['secret' => $secret];
		$params = json_encode($params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$host . "/api/accounts/open");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public static function calSecret($len = 80, $res = '')
	{
		for ($i = 0; $i < $len; $i++) {
			$num = rand(ord('a'), ord('z'));
			$char = chr($num);
			$res .= $char;
		}
		return $res;
	}

	public static function getBalance($address)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$host . "/api/accounts/getBalance?address=" . $address);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		$response = curl_exec($ch);
		curl_close($ch);
		$res = json_decode($response, true);
		if ($res) {
			return $res;
		} else {
			echo $response;
			exit();
		}
	}

	public static function login()
	{
		$curl = curl_init();
		$postObj = ["secret" => $password];
		$postData = json_encode($postObj);
		curl_setopt_array($curl, array(CURLOPT_URL => self::$host . "/api/transactions", CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "PUT", CURLOPT_POSTFIELDS => $postData, CURLOPT_HTTPHEADER => array("Content-Type: application/json",),));
		$response = curl_exec($curl);
		curl_close($curl);
	}

	public static function sendTrans($amount, $recipientId, $password, $publicKey)
	{
		$postObj = ["secret" => $password];
		$postData = json_encode($postObj);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$host . "/api/accounts/open");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_exec($ch);
		curl_close($ch);
		$amount = $amount * 100000000;
		$curl = curl_init();
		$postObj = ["secret" => $password, "amount" => $amount, "recipientId" => $recipientId, "publicKey" => $publicKey];
		$postData = json_encode($postObj);
		curl_setopt_array($curl, array(CURLOPT_URL => self::$host . "/api/transactions", CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "PUT", CURLOPT_POSTFIELDS => $postData, CURLOPT_HTTPHEADER => array("Content-Type: application/json",),));
		$response = curl_exec($curl);
		curl_close($curl);
		if ($res = json_decode($response, true)) {
			if ($res["success"]) {
				return ["success" => true, "transaction" => $res["transactionId"]];
			} else {
				return ['success' => false, 'transaction' => $res];
			}
		} else {
			echo $response;
			exit();
		}
	}

	public static function getTransLog($recipientId, $senderPublicKey, $limit = 50, $offset = 0)
	{
		$curl = curl_init();
		$url = self::$host . "/api/transactions?recipientId=" . $recipientId . "&orderBy=t_timestamp%3Adesc&limit=" . $limit . "&offset=" . $offset;
		curl_setopt_array($curl, array(CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_HTTPHEADER => array("cache-control: no-cache",),));
		$response = curl_exec($curl);
		curl_close($curl);
		$res = json_decode($response, true);
		if (isset($res['success']) && $res['success']) {
			return json_encode($res['transactions']);
		} else {
			exit();
		}
	}
}