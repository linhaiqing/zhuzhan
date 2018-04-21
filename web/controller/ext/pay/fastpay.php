<?php
namespace ext\pay;
class fastpay extends movepay
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
		$post_data = array('merNo' => '15152a8780da25f1173fb1ae7f53eb7f', 'txnAmt' => '10', 'orderId' => date("YmdHis"), 'txnTime' => date("YmdHis"), 'frontUrl' => 'http://q.360ipay.com/qr/fp/api/v1.0/test1', 'backUrl' => 'http://q.360ipay.com/qr/fp/api/v1.0/test2');
		$postUrl = 'http://q.360ipay.com/qr/fp/api/v1.0/pay';
		$curlPost = $post_data;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch);
		curl_close($ch);
		echo $data;
	}

	public function notify_url()
	{
	}

	public function return_url()
	{
	}
}