<?php
namespace ext\pay;
class wftsaoma extends movepay
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
		$bank = isset($_POST['bank']) ? trim($_POST['bank']) : "alipay";
		$AdviceURL = $this->pay_data['base_url'] . self::NOTIFY_URL;
		$AdviceURL = "http://www.yibaojy.com/pay/notify_url";
		$txnType = "";
		if ($bank == "alipay") {
			$txnType = "alipay";
		}
		if ($bank == "wechat") {
			$txnType = "weixin";
			if ($bank == 'tenpay') {
				$txnType = "tenpay";
			}
		}
		$paras = [];
		$paras["service"] = "pay." . $txnType . ".native";
		$paras["version"] = '1.0';
		$paras["charset"] = 'UTF-8';
		$paras["sign_type"] = 'MD5';
		$paras["mch_id"] = $this->pay_data['auth_data']['username'];
		$paras["out_trade_no"] = $this->pay_data['orderno'];
		$paras["device_info"] = '111';
		$paras["body"] = '买卖网充值';
		$paras["attach"] = '';
		$paras["total_fee"] = $this->pay_data['amount'] * 100;
		$paras["mch_create_ip"] = '127.0.0.1';
		$paras["notify_url"] = $AdviceURL;
		$paras["op_user_id"] = "";
		$paras["goods_tag"] = "";
		$paras["product_id"] = "";
		$paras["nonce_str"] = mt_rand(time(), time() + rand());
		$paras["limit_credit_pay"] = "";
		$array = $paras;
		ksort($array);
		$md5Key = $this->pay_data['auth_data']['password'];
		$msg = $this->createSign($array, $md5Key);
		$array["sign"] = $msg;
		$data = $this->toXml($array);
		$reqContent["url"] = "https://pay.swiftpass.cn/pay/gateway";
		$reqContent['data'] = $data;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $reqContent['url']);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $reqContent['data']);
		$res = curl_exec($ch);
		curl_close($ch);
		$res = $this->parseXML($res);
		if ($res["status"] == 0) {
			$code = @$res["code_url"];
			if ($code) {
				ajax($code, 1);
			}
		} else {
			ajax("二维码生成失败");
		}
	}

	public function notify_url()
	{
		$paras = $_POST;
		$post_signature = @$paras['sign'];
		if ($paras['status'] == 0) {
			unset($paras['sign']);
		}
		ksort($paras);
		$md5Key = $this->pay_data['auth_data']['password'];
		$msg = $this->createSign($paras, $md5Key);
		$bool = 'fail';
		if ($msg == $post_signature && $paras["status"] == "0" && $paras["result_code"] == "0") {
			$this->success();
			$bool = 'success';
		}
		echo $bool;
	}

	public function return_url()
	{
	}

	private function createSign($array, $md5Key)
	{
		$signPars = "";
		foreach ($array as $k => $v) {
			if ("" != $v && "sign" != $k) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $md5Key;
		$sign = strtoupper(md5($signPars));
		return $sign;
	}

	public static function toXml($array)
	{
		$xml = '<xml>';
		forEach ($array as $k => $v) {
			$xml .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>';
		}
		$xml .= '</xml>';
		return $xml;
	}

	public function parseXML($xmlSrc)
	{
		if (empty($xmlSrc)) {
			return false;
		}
		$array = array();
		$xml = simplexml_load_string($xmlSrc);
		$encode = $this->getXmlEncode($xmlSrc);
		if ($xml && $xml->children()) {
			foreach ($xml->children() as $node) {
				if ($node->children()) {
					$k = $node->getName();
					$nodeXml = $node->asXML();
					$v = substr($nodeXml, strlen($k) + 2, strlen($nodeXml) - 2 * strlen($k) - 5);
				} else {
					$k = $node->getName();
					$v = (string)$node;
				}
				if ($encode != "" && $encode != "UTF-8") {
					$k = iconv("UTF-8", $encode, $k);
					$v = iconv("UTF-8", $encode, $v);
				}
				$array[$k] = $v;
			}
		}
		return $array;
	}

	public function getXmlEncode($xml)
	{
		$ret = preg_match("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
		if ($ret) {
			return strtoupper($arr[1]);
		} else {
			return "";
		}
	}
}