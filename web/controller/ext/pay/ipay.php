<?php
namespace ext\pay;
class ipay extends movepay
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
		$AdviceURL = 'https://www.yajiaowang.com/pay/notify_url/show_type_flag/2';
		$secretKey = $this->pay_data['auth_data']['password'];
		$payUrl = "http://q.360ipay.com/qr/api/yft/v1.0/qr/pay";
		$arr = array("noncestr" => "d453acc05a823a1503d293908983cbd2", "merchantOutOrderNo" => $this->pay_data['orderno'], "orderTime" => "20" . date("ymdhis"), "orderMoney" => $this->pay_data['amount'], "notifyUrl" => $AdviceURL, "merid" => $this->pay_data['auth_data']['username']);
		ksort($arr);
		$valueString = "";
		foreach ($arr as $k => $v) {
			$valueString = $valueString . $k . "=" . $v . "&";
		}
		$valueString = $valueString . "key=" . $secretKey;
		$sign = md5($valueString);
		$arr['sign'] = $sign;
		$mykv = "";
		foreach ($arr as $k => $v) {
			$mykv = $mykv . "&" . $k . "=" . $v;
		}
		$mykv = substr($mykv, 1);
		$url = $payUrl . "?" . $mykv;
		$ret = file_get_contents($url);
		$ret = json_decode($ret, true);
		$code = @$ret['url'];
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
		foreach ($paras as $k => $v) {
			$valueString = $valueString . $k . "=" . $v . "&";
		}
		$secretKey = $this->pay_data['auth_data']['password'];
		$valueString = $valueString . "key=" . $secretKey;;
		$sign = md5($valueString);
		$bool = 'failure';
		if ($sign == $post_signature && $paras["payResult"] == "1") {
			$this->success();
			$bool = 'success';
		}
		echo $bool;
	}

	public function return_url()
	{
	}
}