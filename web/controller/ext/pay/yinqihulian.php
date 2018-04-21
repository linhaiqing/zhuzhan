<?php
namespace ext\pay;
class yinqihulian extends movepay
{
	const NOTIFY_URL = '/pay/notify_url/show_type_flag/2';

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
		$bank = isset($_POST['bank']) ? trim($_POST['bank']) : "alipay";
		if ($bank == "alipay") {
			$txnType = "2";
		}
		if ($bank == "wechat") {
			$txnType = "1";
		}
		$AdviceURL = 'http://www.yajiaowang.com/pay/notify_url/show_type_flag/2';
		$secretKey = $this->pay_data['auth_data']['password'];
		$payUrl = "http://qr.360ipay.com/v1.0/qrCodePay";
		$arr = array("merNo" => $this->pay_data['auth_data']['username'], "notifyUrl" => $AdviceURL, "orderDate" => "20" . date("ymd"), "orderNo" => $this->pay_data['orderno'], "goods" => "123", "payType" => $txnType, "transAmt" => $this->pay_data['amount'] * 100,);
		ksort($arr);
		$valueString = "";
		foreach ($arr as $value) {
			$valueString = $valueString . $value;
		}
		$valueString = $valueString . $secretKey;
		$sign = hash("sha256", $valueString);
		$arr['sign'] = $sign;
		$mykv = "";
		foreach ($arr as $k => $v) {
			$mykv = $mykv . "&" . $k . "=" . $v;
		}
		$mykv = substr($mykv, 1);
		$url = $payUrl . "?" . $mykv;
		$ret = file_get_contents($url);
		$ret = json_decode($ret, true);
		$code = @$ret['qrUrl'];
		if (!$code) {
			ajax("二维码生成失败");
		} else {
			ajax($code, 1);
		}
	}

	public function notify_url()
	{
		$paras = $_POST;
		$post_signature = @$paras['sign'];
		unset($paras['sign']);
		ksort($paras);
		$valueString = "";
		foreach ($paras as $value) {
			$valueString = $valueString . $value;
		}
		$secretKey = $this->pay_data['auth_data']['password'];
		$valueString = $valueString . $secretKey;
		$sign = hash("sha256", $valueString);
		$bool = 'failure';
		if ($sign == $post_signature && $paras["orderStatus"] == "S") {
			$this->success();
			$bool = 'success';
		}
		echo $bool;
	}

	public function return_url()
	{
	}
}