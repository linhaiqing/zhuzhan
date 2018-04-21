<?php
namespace ext\coin;
class asch
{
	public static function sendTrans($amount, $recipientId, $password, $secondPassword = 0)
	{
		$amount = $amount * 100000000;
		$curl = curl_init();
		$postObj = ["secret" => $password, "amount" => $amount, "recipientId" => $recipientId];
		if ($secondPassword) {
			$postObj["secondSecret"] = $secondPassword;
		}
		$postData = json_encode($postObj);
		curl_setopt_array($curl, array(CURLOPT_URL => "https://mainnet.asch.io/api/transactions", CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "PUT", CURLOPT_POSTFIELDS => $postData, CURLOPT_HTTPHEADER => array("Content-Type: application/json",),));
		$response = curl_exec($curl);
		curl_close($curl);
		if ($res = json_decode($response, true)) {
			if ($res["success"]) {
				return ["transactionId" => $res["transactionId"]];
			} else {
				return false;
			}
		} else {
			echo $response;
			exit();
		}
	}

	public static function getTransLog($recipientId, $limit = 100)
	{
		$curl = curl_init();
		$url = "https://mainnet.asch.io/api/transactions?recipientId=" . $recipientId . "&orderBy=t_timestamp%3Adesc&type=6&limit=" . $limit . "&offset=0";
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