<?php
namespace ext\pay;
class saoma extends movepay
{
	const NOTIFY_URL = '/pay/notify_url/show_type_flag/2';
	const ERTURN_URL = '/pay/return_url/show_type_flag/2';

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
		$bank = isset($_POST['bank']) ? trim($_POST['bank']) : "ccb";
		$this->pay_data["base_url"] = "http://www.coin86.com";
		$AdviceURL = $this->pay_data['base_url'] . self::NOTIFY_URL;
		$txnType = "";
		if ($bank == "alipay") {
			$txnType = "42";
		}
		if ($bank == "wechat") {
			$txnType = "41";
		}
		$paras = [];
		$paras["signMethod"] = "MD5";
		$paras["version"] = "1.0.0";
		$paras["txnType"] = $txnType;
		$paras["txnSubType"] = "00";
		$paras["bizType"] = "000000";
		$paras["accessType"] = "0";
		$paras["accessMode"] = "01";
		$paras["payType"] = "0701";
		$paras["merId"] = $this->pay_data['auth_data']['username'];
		$paras["merOrderId"] = $this->pay_data['orderno'];
		$paras["appId"] = "";
		$paras["txnTime"] = "20" . date("ymdhis");
		$paras["txnAmt"] = $this->pay_data['amount'] * 100;
		$paras["currency"] = "CNY";
		$paras["backUrl"] = $AdviceURL;
		$paras["payTimeOut"] = "";
		$paras["subject"] = "movesay";
		$paras["body"] = "";
		$paras["customerIp"] = "47.92.142.116";
		$paras["merResv1"] = "";
		$paras["termMerId"] = "";
		$array = $paras;
		ksort($array);
		$md5Key = $this->pay_data['auth_data']['password'];
		$msg = $this->signMsg($array, $md5Key);
		$signature = base64_encode(md5($msg, TRUE));
		$array["signature"] = $signature;
		$reqBase64Keys = array("subject", "body", "remark", "customerInfo", "accResv", "riskRateInfo", "billQueryInfo", "billDetailInfo");
		foreach ($reqBase64Keys as $bs64Key) {
			if (isset($array[$bs64Key])) {
				$array[$bs64Key] = base64_encode($array[$bs64Key]);
			}
		}
		$paras = $array;
		$this->post_data($paras, $bank);
	}

	public function notify_url()
	{
		$paras = $_POST;
		$post_signature = @$paras['signature'];
		unset($paras['signMethod']);
		unset($paras['signature']);
		$paras['respMsg'] = base64_decode(@$paras['respMsg'], true);
		ksort($paras);
		$md5Key = $this->pay_data['auth_data']['password'];
		$msg = $this->signMsg($paras, $md5Key);
		$signature = base64_encode(md5($msg, TRUE));
		$bool = 'failure';
		if ($signature == $post_signature && $paras["respCode"] == "1001") {
			$this->success();
			$bool = 'success';
		}
		echo $bool;
	}

	public function return_url()
	{
	}

	public function post_data($data)
	{
		$url = "http://pay.chinagpay.com/bas/BgTrans";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data2 = curl_exec($ch);
		curl_close($ch);
		parse_str($data2, $data2);
		$code = base64_decode(@$data2["resv"]);
		if (!$code) {
			ajax("二维码生成失败");
		} else {
			ajax($code, 1);
		}
		exit();
	}

	private function signMsg($array, $md5Key)
	{
		$msg = "";
		$i = 0;
		foreach ($array as $key => $val) {
			if ($key != "signMethod" && $key != "signature") {
				if ($i == 0) {
					$msg = $msg . "$key=$val";
				} else {
					$msg = $msg . "&$key=$val";
				}
				$i++;
			}
		}
		$msg = $msg . $md5Key;
		return $msg;
	}
}