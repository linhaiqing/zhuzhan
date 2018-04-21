<?php
namespace ext\pay;
class huarong extends movepay
{
	const NOTIFY_URL = '/pay/notify_url';
	const ERTURN_URL = '/pay/return_url';

	public static function init($pay_data)
	{
		if (!self::$handel) {
			return self::$handel = new self($pay_data);
		} else {
			return self::$handel;
		}
	}

	public function submit()
	{
		$priKey = base64_decode("LS0tLS1CRUdJTiBSU0EgUFJJVkFURSBLRVktLS0tLQ0KTUlJRW93SUJBQUtDQVFFQWk0d3VUWGY0QkVqZ3VHeHNRU09nYXRyMmllQUJhK0l4RlVMNVNZZGxuMUtqU0w5eQ0KWS90Y2p1L0t3cVRoY0cvd09Qa09kZ0lBMWFmdTN6TnFIcmp6U0Y2OTZLK3h2RlQwaDRjeStvVTRmVnRYNTc4cg0KbHBHa2N4dUtPczdyYzQ1dW84SmRGM2lDVkcyM2JtT2pNS21waW9KNzJFTUVuR0E1QjVOTGJZNEFtUmNxMWJuZw0KMkJCalFVSHVpZjI3NUsyZlRCMmFpSFMxSkVUZ0FxQjVGZmZ3ZndtOWV6S3pPVDJoWnkzcnBZWXdwTUN4OUZ6cg0KQ01rY3FmbGlyekcwc1BYMTlHWHBGSGs5QkFvNURBb3VmVzdsNDNRYm9weDRYOHZDUENaTXQyRC9hNU4vTU0vSQ0KY1B5NVRvVktNeXFwb3ZCZ2ZFTU1LajNWeWdvSnVRa1hxRzJDSFFJREFRQUJBb0lCQVFDREZOMlZwWUNTdmNaaA0KVm05bHpNcHVOVnBxYnNISERrVGUzUGQveXpCNU5VM3VFdStuM1VDM2Y2WHRxcVdkVVhqTWU1N0syTnpoMVlwNw0KblRjc3ZyekFHVWE0b1BrS21KN09CQUN0OUovNG1Pbjg1Y1ZTL1dJRlFqUlpRMjlHTDkvYjhjSXlQdC9sNE4zeQ0KVXFQREhVd0ZzTVlXZ2ZWOTBxRWZiWXZuMEFEZHk5TXpGeVV4RThWV1BvT1FYVjhDYzF0dm9BT3c5ZlNrR2VGTw0KTS84OHZraERCWC93WThYVU92VHd2MjVzOGRSZ0lUcnZRaU1tSWNrNnJrUWJzRml0RVZGckt6c0UzbUtOTm5XVw0KRGRxTmV4amgrY0RabEpjZHdDSGlZL2F5bUJ5RzZxRnlzc2phSktSWHJPUnc5NzFDbktuUXpobC9GL0RJNkM0Vw0KV1AxQWRQUGRBb0dCQUw4d3VKQ1pKQWRHMXZOcGNNQVZjNms3MUw3S2txSGJabVVvY0NwMmxkVnNvci9jb01GUQ0KZmt4ZE1KT0tjR1JYKzBuNllQSS8rMDRJR1pqUUlvdWpiV3BtclJCZ0NUSVE2cmp0M1l3VUthemZxQm4vQk9lUQ0KaWYyMGRJVjBKbGZtcU9kUEZ4cTRNYy9LMEU2dzJGMmFTeW9oTXJObXBQL2RUVVpjVmpHMmdHSWZBb0dCQUxyWg0KOWo4ZjJlNXRYZnlNdzB2QzEwZnZqMGJWeUlrZWQ1R0EwRDZWbk9DaXZXaCtJNnRvQmNydTNkSjYycE5xajFkdQ0KbXJMOTFsYSs0c1JVQ1FpTmVpNEN3NHVreUpJaW1KOEFNSjJmbUJNZlcweklCTHNwaW1KeEgzUWtBaEM0WVl1Rg0KZUFJQms0NkJRS0xYdE93elFPRUVoYXNveUkzL0I5Z3VjSW4yT2F4REFvR0FZTU4yMWZBNzlOYmNtWXRtL2tDZA0KTGl6UzZ0RTV6Mk5zZW93eDRMdnQ3M3V4RHl2SnpLMUJqc0tuZ0RUVDEyVlVFRWdCWXZvUTVaaGg1QjZMTFdORQ0KVUZLeENwV2djYzl3RExLT3E0UFU1K0syZ0ptNlVMeXRxbEwzbWtlRDFIOHdRd21iWnZHTWZGOW9lRHMyOW9ETg0Kdy8vVFdrcjcvV2R5NGNaLzlWOHBzUDhDZ1lCS3JQR05LTjJnUEhWamlaNURtUXNRNU4wT0lrS1RDbW9lN0t4Tg0KVnQzcnFSNnB0RC90TVJXZmxQZHlOMGREdzBLbVhyM1U2ejNPVFZ1enV5SWhHT25MbWE4dEVCNVl5UnJWOE8xOQ0KRVZ0bHlSSmtEVXhXd0xOZHhIZ2Rvb1p3eUQrejB4T3pPWTRQVmZqUEhBWmluOWsrRk9DWEZ5V1FHbXk0S1Zjdw0KekFjZ29RS0JnRFNxSVg2amNvNkdOdVAwSWY5Vy9NOFFGNmhvdjQyeHU2OGdjZkhjMlNGTTF6Y3h4Ni9zYms1WA0KWlIwVGdlanRMZ3o0d1QrQUIrU0VzS2NDdmVOMmZVTEE0RmFWY2FxQ1VyaWJYL05OakxSVFRRK2ZWSkVzaUlrYw0KeW01RVY4SmJZMC9FMlRlRk8zNXRsTjExcm5xakZmMWtHbUsrT2FYUDYxbHdKT3FpTFVxLw0KLS0tLS1FTkQgUlNBIFBSSVZBVEUgS0VZLS0tLS0=");
		define('MSYMURL', 'http://' . $_SERVER['HTTP_HOST'] . '/');
		$params_post = array('requestNo' => 'LS200112424222126', 'version' => "V1.0", 'productId' => "0208", 'transId' => '12', 'merNo' => "850440058121001", 'orderDate' => date('Ymd'), 'orderNo' => $this->pay_data['orderno'], 'returnUrl' => "http://www.ascbtc.com/pay/return_url", 'notifyUrl' => "http://www.ascbtc.com/pay/notify_url", 'transAmt' => $this->pay_data['amount'] * 100, 'commodityName' => 'movesay', 'bankCode' => $_POST["bank"],);
		$params_post["signature"] = $this->getignature($params_post, $priKey);
		echo ' <html>			<head>				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">			</head>			<body onLoad="document.dinpayForm.submit();">				<form name="dinpayForm" method="post" action="http://test.ryfpay.com:8081/payment-gate-web/gateway/api/backTransReq" target="_blank">					<input type="hidden" name="requestNo"		  value="' . $params_post['requestNo'] . '"' . "/>" . '<input type="hidden" name="version" value="' . $params_post['version'] . '"' . "/>" . '<input type="hidden" name="productId" value="' . $params_post['productId'] . '"' . "/>" . '<input type="hidden" name="transId" value="' . $params_post['transId'] . '"' . "/>" . '<input type="hidden" name="merNo" value="' . $params_post['merNo'] . '"' . "/>" . '<input type="hidden" name="orderDate" value="' . $params_post['orderDate'] . '"' . "/>" . '<input type="hidden" name="orderNo" value="' . $params_post['orderNo'] . '"' . "/>" . '<input type="hidden" name="returnUrl" value="' . $params_post['returnUrl'] . '"' . "/>" . '<input type="hidden" name="notifyUrl" value="' . $params_post['notifyUrl'] . '"' . "/>" . '<input type="hidden" name="transAmt" value="' . $params_post['transAmt'] . '"' . "/>" . '<input type="hidden" name="commodityName" value="' . $params_post['commodityName'] . '"' . "/>" . '<input type="hidden" name="bankCode" value="' . $params_post['bankCode'] . '"' . "/>" . '<input type="hidden" name="signature" value="' . $params_post['signature'] . '"' . "/>" . '</form>			    </body>		    </html>';
	}

	public function notify_url()
	{
		file_put_contents(APP_PATH . "/pay2.log", var_export(array_merge($_SERVER, $_POST), true), FILE_APPEND);
		$pubKey = base64_decode("LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0NCk1JSUJJakFOQmdrcWhraUc5dzBCQVFFRkFBT0NBUThBTUlJQkNnS0NBUUVBaTR3dVRYZjRCRWpndUd4c1FTT2cNCmF0cjJpZUFCYStJeEZVTDVTWWRsbjFLalNMOXlZL3RjanUvS3dxVGhjRy93T1BrT2RnSUExYWZ1M3pOcUhyanoNClNGNjk2Syt4dkZUMGg0Y3krb1U0ZlZ0WDU3OHJscEdrY3h1S09zN3JjNDV1bzhKZEYzaUNWRzIzYm1Pak1LbXANCmlvSjcyRU1FbkdBNUI1TkxiWTRBbVJjcTFibmcyQkJqUVVIdWlmMjc1SzJmVEIyYWlIUzFKRVRnQXFCNUZmZncNCmZ3bTllekt6T1QyaFp5M3JwWVl3cE1DeDlGenJDTWtjcWZsaXJ6RzBzUFgxOUdYcEZIazlCQW81REFvdWZXN2wNCjQzUWJvcHg0WDh2Q1BDWk10MkQvYTVOL01NL0ljUHk1VG9WS015cXBvdkJnZkVNTUtqM1Z5Z29KdVFrWHFHMkMNCkhRSURBUUFCDQotLS0tLUVORCBQVUJMSUMgS0VZLS0tLS0=");
		$params_return = $_POST;
		$return_signature = $params_return["signature"];
		$params_return = array_filter($params_return);
		unset($params_return["signature"]);
		$checksignature = $this->checksignature($params_return, $return_signature, $pubKey);
		$bool = 'Verification Error';
		if ($checksignature) {
			if ($params_return['respCode'] == "0000") {
				$this->success();
				$bool = 'SUCCESS';
			}
		}
		file_put_contents(APP_PATH . "/pay3.log", date("Y-m-d H:i:s", time()) . " " . $bool . "\n", FILE_APPEND);
		echo $bool;
	}

	public function return_url()
	{
		file_put_contents(APP_PATH . "/pay.log", var_export(array_merge($_SERVER, $_POST), true), FILE_APPEND);
		$pubKey = base64_decode("LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0NCk1JSUJJakFOQmdrcWhraUc5dzBCQVFFRkFBT0NBUThBTUlJQkNnS0NBUUVBaTR3dVRYZjRCRWpndUd4c1FTT2cNCmF0cjJpZUFCYStJeEZVTDVTWWRsbjFLalNMOXlZL3RjanUvS3dxVGhjRy93T1BrT2RnSUExYWZ1M3pOcUhyanoNClNGNjk2Syt4dkZUMGg0Y3krb1U0ZlZ0WDU3OHJscEdrY3h1S09zN3JjNDV1bzhKZEYzaUNWRzIzYm1Pak1LbXANCmlvSjcyRU1FbkdBNUI1TkxiWTRBbVJjcTFibmcyQkJqUVVIdWlmMjc1SzJmVEIyYWlIUzFKRVRnQXFCNUZmZncNCmZ3bTllekt6T1QyaFp5M3JwWVl3cE1DeDlGenJDTWtjcWZsaXJ6RzBzUFgxOUdYcEZIazlCQW81REFvdWZXN2wNCjQzUWJvcHg0WDh2Q1BDWk10MkQvYTVOL01NL0ljUHk1VG9WS015cXBvdkJnZkVNTUtqM1Z5Z29KdVFrWHFHMkMNCkhRSURBUUFCDQotLS0tLUVORCBQVUJMSUMgS0VZLS0tLS0=");
		$params_return = $_POST;
		$return_signature = $params_return["signature"];
		$params_return = array_filter($params_return);
		unset($params_return["signature"]);
		$checksignature = $this->checksignature($params_return, $return_signature, $pubKey);
		$returnData = ["orderno" => $params_return["orderNo"], "status" => 0, "msg" => $params_return["respDesc"], "Remark" => ''];
		if ($checksignature) {
			if ($params_return['respCode'] == "0000") {
				$this->success();
				$returnData["status"] = 1;
			} else {
				$returnData["status"] = -1;
			}
		} else {
			$returnData["status"] = -2;
		}
		config("PayReturn", $returnData);
	}

	public function getignature($params_signature, $priKey)
	{
		ksort($params_signature);
		$signstring = "";
		$i = 0;
		foreach ($params_signature as $key => $val) {
			$signstring = $signstring . ($i == 0 ? $key . "=" . $val : ('&' . $key . "=" . $val));
			$i++;
		}
		openssl_sign($signstring, $ret, $priKey);
		$signature = base64_encode('' . $ret);
		return $signature;
	}

	public function checksignature($params_signature, $returnsignature, $pubKey)
	{
		ksort($params_signature);
		$signstring = "";
		$i = 0;
		foreach ($params_signature as $key => $val) {
			$signstring = $signstring . ($i == 0 ? $key . "=" . $val : ('&' . $key . "=" . $val));
			$i++;
		}
		$returnsignature = base64_decode($returnsignature);
		switch (openssl_verify($signstring, $returnsignature, $pubKey)) {
			case 1:
				$ret = true;
				break;
			case 0:
			case -1:
			default:
				$ret = false;
		}
		return $ret;
	}
}