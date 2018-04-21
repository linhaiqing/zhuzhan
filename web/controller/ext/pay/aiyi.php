<?php
namespace ext\pay;
class aiyi extends movepay
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
		$pay_type = isset($_POST['pay_type']) ? trim($_POST['pay_type']) : ajax("pay_type 参数丢失");
		if ($pay_type != "cibalipay" && $pay_type != "cibweixin") {
			ajax("pay_type 格式错误" . $pay_type);
		}
		$url = "https://www.iyibank.com/pay/gateway";
		$nonce_str = mt_rand(time(), time() + rand());
		$signature = "key=" . trim($this->pay_data['auth_data']['password']);
		$arr['service'] = $pay_type;
		$arr['version'] = "1.0";
		$arr['charset'] = "utf-8";
		$arr['sign_type'] = "MD5";
		$arr['mch_id'] = trim($this->pay_data['auth_data']['username']);
		$arr['out_trade_no'] = $this->pay_data['orderno'];
		$arr['body'] = $this->pay_data['title'];
		$arr['total_fee'] = $this->pay_data['amount'];
		$arr['mch_create_ip'] = "127.0.0.1";
		$arr['notify_url'] = $this->pay_data['base_url'] . self::ERTURN_URL;
		$arr['callback_url'] = $this->pay_data['base_url'] . self::NOTIFY_URL;
		$arr['nonce_str'] = $nonce_str;
		$values = $this->_post_data($url, $signature, $arr);
		if (isset($values['err_msg']) && strtolower($values['err_msg']) == "success") {
			ajax(["img" => $values['token_id']], 1);
		} else {
			if (strpos($values['message'], "重复") !== false) {
				ajax("已支付过", 2);
			} else {
				ajax($values['message']);
			}
		}
	}

	private function check_pay()
	{
		if ($this->check_tradeno() != 0) {
			ajax("订单已处理");
		}
		$url = "https://www.iyibank.com/pay/orderquery";
		$nonce_str = mt_rand(time(), time() + rand());
		$signature = "key=" . trim($this->pay_data['auth_data']['password']);
		$arr['version'] = "1.0";
		$arr['charset'] = "utf-8";
		$arr['sign_type'] = "MD5";
		$arr['out_trade_no'] = $this->pay_data['orderno'];
		$arr['mch_id'] = trim($this->pay_data['auth_data']['username']);
		$arr['nonce_str'] = $nonce_str;
		$values = $this->_post_data($url, $signature, $arr);
		if (isset($values['trade_state']) && strtolower($values['trade_state']) == "success") {
			if (abs($this->pay_data['amount'] - $values['total_fee']) > 0.01) {
				ajax("充值订单金额:" . $this->pay_data['amount'] . " 实际到账金额:" . $values['total_fee'] . " 误差过大充值失败", 1);
			}
			$this->success();
			ajax("支付成功,跳转中...", 1);
		} else {
			ajax("待支付");
		}
	}

	protected function _post_data($url, $signature, $arr)
	{
		ksort($arr);
		$temp = "";
		foreach ($arr as $x => $x_value) {
			if ($x_value != null) {
				$temp = $temp . $x . "=" . $x_value . "&";
			}
		}
		$arr['sign'] = strtoupper(md5($temp . $signature));
		$xmls = "<xml>";
		foreach ($arr as $k => $v) {
			if ($v != null) {
				if ($k == 'body') {
					$xmls .= '<body>' . $v . '</body>';
				} else {
					$xmls .= '<' . $k . '>' . $v . '</' . $k . '>';
				}
			}
		}
		$xmls .= '</xml>';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $xmls);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		$data = curl_exec($curl);
		curl_close($curl);
		libxml_disable_entity_loader(true);
		return json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
	}

	public function notify_url()
	{
		echo "SUCCESS";
		exit();
	}

	public function return_url()
	{
		if (isset($_GET['aiyi']) && $_GET['aiyi']) {
			$this->check_pay();
			exit();
		} else {
			echo "SUCCESS";
			exit();
		}
	}
}