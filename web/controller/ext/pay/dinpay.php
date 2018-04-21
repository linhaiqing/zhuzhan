<?php
namespace ext\pay;
class dinpay extends movepay
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
		$merchant_code = $this->pay_data['auth_data']['username'];
		if (!$merchant_code) {
			$merchant_code = "2001010048";
		}
		$notify_url = "http://mobtc.com/pay/notify_url";
		$order_amount = $this->pay_data['amount'];
		$product_name = "dinpay";
		$return_url = "http://mobtc.com/pay/return_url";
		$order_no = $this->pay_data['orderno'];
		$merchant_private_key = base64_decode("LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tDQpNSUlDZGdJQkFEQU5CZ2txaGtpRzl3MEJBUUVGQUFTQ0FtQXdnZ0pjQWdFQUFvR0JBTWJyZm5jNGFwMnRhcFBFDQpTNHJjcS84VDhDUHFiS0tiU3U1L2RoQ1VoRVA2M0FvbU1pNXprZzl2Q2d1Y09ZQlU2eTNwNUNVeE9CUlRGV3p1DQp4eGhDMjJSc0kySFEzWVdDSVhlWHdYbjFpQTduWCs4VEpSU3dsRC9VZXBWcmxrY2N0UGNMYXBvbGR1YnlLN1RFDQpPdXpTNUdNNFpHRVlIYWtOaHBvUHpIWGVHNXdMQWdNQkFBRUNnWUVBdDdCcHBXQlVtK3l6R1dkUU54Z1dRRVBDDQpJdmhzZnpBV1h1MFhNckppL1grZlNFUURYUC9lSmlCc3oxSGhpNDlmYVpPdDJXWjlHYytyWGwrVlBPTW9RZzlTDQpSNGFYai9CVFpSRHdtYmMzOFZWUkhvUnM2RklUeVhXVWlQOU43NFZDaFZnRnh0ZXRSNSthN1liVUZMenppYjZxDQpIQnJtQ0sydUpsNHlZTWVsdStrQ1FRRHZoWHZocVM0T2orSDg4V1lsbmo5OUx3MnZSTnNlaXh6RDFLUm5NdFBmDQpSNGxJd2hQMWFTSGVSNmlGRTlJMmo5ZEVLd3hnVjRZNXRPaG1oV3ExWW9uVkFrRUExSnJzMGszZ1YyZEhHZi82DQo4S2puNDVFY2M5QWhhWnpWQVlFcXZ1U1F4Z1ZMWGhpZkxFQWNWeWFqRy9LT04vRGY2NCsxeHVWYUFHTlBXQ1pqDQpNeFdlWHdKQVpZc3BvZG1mZFB6NnljbHRqTFV5SlZnMWJRQzhCSTdsZXdCRFVwenh4YzhHSEpwcTY3Zk9hL1hsDQpNOFhiY05ZNlpPUDdNenpuNHI4WFo1OVlSZDJvb1FKQVlKWEZVc0U0VXR6RGEyZGtUQ0tmdmoxMVRKUFlLZytxDQpvUlZhSXdEWldHeWRVYkVqMXNtRlZuNWlDVmRIRHRqcUVTem5hdVZVM3VvKzZJLy83dnc2UndKQUxjZ3RmUFZlDQpiN2xrRTdNYVJXeko0ZEtKalp1OEg1SFo5c2dIK3hDZHNTaFJidngrWVdJVlVMZk5qbDVhd25HZ1VRTVJHVUFvDQpZejQxTjdJRGJhc2wrdz09DQotLS0tLUVORCBQUklWQVRFIEtFWS0tLS0t");
		$service_type = "direct_pay";
		$interface_version = "V3.0";
		$sign_type = "RSA-S";
		$input_charset = "UTF-8";
		$order_time = date('Y-m-d H:i:s');
		$pay_type = "";
		$redo_flag = "";
		$product_code = "";
		$product_desc = "";
		$product_num = "";
		$show_url = "";
		$client_ip = "";
		$bank_code = "";
		$extend_param = "";
		$extra_return_param = "";
		$signStr = "";
		if ($bank_code != "") {
			$signStr = $signStr . "bank_code=" . $bank_code . "&";
		}
		if ($client_ip != "") {
			$signStr = $signStr . "client_ip=" . $client_ip . "&";
		}
		if ($extend_param != "") {
			$signStr = $signStr . "extend_param=" . $extend_param . "&";
		}
		if ($extra_return_param != "") {
			$signStr = $signStr . "extra_return_param=" . $extra_return_param . "&";
		}
		$signStr = $signStr . "input_charset=" . $input_charset . "&";
		$signStr = $signStr . "interface_version=" . $interface_version . "&";
		$signStr = $signStr . "merchant_code=" . $merchant_code . "&";
		$signStr = $signStr . "notify_url=" . $notify_url . "&";
		$signStr = $signStr . "order_amount=" . $order_amount . "&";
		$signStr = $signStr . "order_no=" . $order_no . "&";
		$signStr = $signStr . "order_time=" . $order_time . "&";
		if ($pay_type != "") {
			$signStr = $signStr . "pay_type=" . $pay_type . "&";
		}
		if ($product_code != "") {
			$signStr = $signStr . "product_code=" . $product_code . "&";
		}
		if ($product_desc != "") {
			$signStr = $signStr . "product_desc=" . $product_desc . "&";
		}
		$signStr = $signStr . "product_name=" . $product_name . "&";
		if ($product_num != "") {
			$signStr = $signStr . "product_num=" . $product_num . "&";
		}
		if ($redo_flag != "") {
			$signStr = $signStr . "redo_flag=" . $redo_flag . "&";
		}
		if ($return_url != "") {
			$signStr = $signStr . "return_url=" . $return_url . "&";
		}
		$signStr = $signStr . "service_type=" . $service_type;
		if ($show_url != "") {
			$signStr = $signStr . "&show_url=" . $show_url;
		}
		$merchant_private_key = openssl_get_privatekey($merchant_private_key);
		openssl_sign($signStr, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);
		$sign = base64_encode($sign_info);
		echo ' <html>			<head>				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">			</head>			<body onLoad="document.dinpayForm.submit();">				<form name="dinpayForm" method="post" action="https://pay.dinpay.com/gateway?input_charset=UTF-8" target="_blank">					<input type="hidden" name="sign"		  value="' . $sign . '"' . "/>" . '<input type="hidden" name="merchant_code" value="' . $merchant_code . '"' . "/>" . '<input type="hidden" name="bank_code" value="' . $bank_code . '"' . "/>" . '<input type="hidden" name="order_no" value="' . $order_no . '"' . "/>" . '<input type="hidden" name="order_amount" value="' . $order_amount . '"' . "/>" . '<input type="hidden" name="service_type" value="' . $service_type . '"' . "/>" . '<input type="hidden" name="input_charset" value="' . $input_charset . '"' . "/>" . '<input type="hidden" name="notify_url" value="' . $notify_url . '"' . "/>" . '<input type="hidden" name="interface_version" value="' . $interface_version . '"' . "/>" . '<input type="hidden" name="sign_type" value="' . $sign_type . '"' . "/>" . '<input type="hidden" name="order_time" value="' . $order_time . '"' . "/>" . '<input type="hidden" name="product_name" value="' . $product_name . '"' . "/>" . '<input type="hidden" name="client_ip" value="' . $client_ip . '"' . "/>" . '<input type="hidden" name="extend_param" value="' . $extend_param . '"' . "/>" . '<input type="hidden" name="extra_return_param" value="' . $extra_return_param . '"' . "/>" . '<input type="hidden" name="pay_type" value="' . $pay_type . '"' . "/>" . '<input type="hidden" name="product_code" value="' . $product_code . '"' . "/>" . '<input type="hidden" name="product_desc" value="' . $product_desc . '"' . "/>" . '<input type="hidden" name="product_num" value="' . $product_num . '"' . "/>" . '<input type="hidden" name="return_url" value="' . $return_url . '"' . "/>" . '<input type="hidden" name="show_url" value="' . $show_url . '"' . "/>" . '<input type="hidden" name="redo_flag" value="' . $redo_flag . '"' . "/>" . '</form>			    </body>		    </html>';
	}

	public function notify_url()
	{
		$_POST['order_time'] = str_replace("+", " ", $_POST['order_time']);
		$_POST['trade_time'] = str_replace("+", " ", $_POST['trade_time']);
		$dinpay_public_key = base64_decode("LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0NCk1JR2ZNQTBHQ1NxR1NJYjNEUUVCQVFVQUE0R05BRENCaVFLQmdRQ1o5V3BnL3ZnNDRXSGI1dG1mbnBYbmtEYkQNCjdTQzhlRUF2RWtBalN4MTdJMDFCZUk1bDdQM1dXMGNOUUQzUGc0RW5WU1JFNkN6SjZXWFBHbm0zVUhDa1pZSkMNClM3OG1CdStTVWJnUzMwclJGZXlqU1FsYWdPL1h2VytRSnFKenVrUFVyZGZxanErMEhrQkFCaWxUamxrV01oWHkNCjJmOThwWnYycEVVeUN3Ry83d0lEQVFBQg0KLS0tLS1FTkQgUFVCTElDIEtFWS0tLS0t");
		$merchant_code = $_POST["merchant_code"];
		$interface_version = $_POST["interface_version"];
		$sign_type = $_POST["sign_type"];
		$dinpaySign = base64_decode($_POST["sign"]);
		$notify_type = $_POST["notify_type"];
		$notify_id = $_POST["notify_id"];
		$order_no = $_POST["order_no"];
		$order_time = $_POST["order_time"];
		$order_amount = $_POST["order_amount"];
		$trade_status = $_POST["trade_status"];
		$trade_time = $_POST["trade_time"];
		$trade_no = $_POST["trade_no"];
		$bank_seq_no = $_POST["bank_seq_no"];
		$extra_return_param = $_POST["extra_return_param"];
		$signStr = "";
		if ($bank_seq_no != "") {
			$signStr = $signStr . "bank_seq_no=" . $bank_seq_no . "&";
		}
		if ($extra_return_param != "") {
			$signStr = $signStr . "extra_return_param=" . $extra_return_param . "&";
		}
		$signStr = $signStr . "interface_version=" . $interface_version . "&";
		$signStr = $signStr . "merchant_code=" . $merchant_code . "&";
		$signStr = $signStr . "notify_id=" . $notify_id . "&";
		$signStr = $signStr . "notify_type=" . $notify_type . "&";
		$signStr = $signStr . "order_amount=" . $order_amount . "&";
		$signStr = $signStr . "order_no=" . $order_no . "&";
		$signStr = $signStr . "order_time=" . $order_time . "&";
		$signStr = $signStr . "trade_no=" . $trade_no . "&";
		$signStr = $signStr . "trade_status=" . $trade_status . "&";
		$signStr = $signStr . "trade_time=" . $trade_time;
		$dinpay_public_key = openssl_get_publickey($dinpay_public_key);
		$flag = openssl_verify($signStr, $dinpaySign, $dinpay_public_key, OPENSSL_ALGO_MD5);
		$bool = 'Verification Error';
		if ($flag) {
			if (strtolower($trade_status) == 'success') {
				parent::write_log(["msg" => "notify_url 成功", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
				$this->success();
				$bool = 'SUCCESS';
			} else {
				parent::write_log(["msg" => "notify_url 提交支付信息失败，返回红色的提示信息状态:{$trade_status}", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
			}
		} else {
			parent::write_log(["msg" => "notify_url 信息:合法性验证失败", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
		}
		echo $bool;
	}

	public function return_url()
	{
		$_POST['order_time'] = str_replace("+", " ", $_POST['order_time']);
		$_POST['trade_time'] = str_replace("+", " ", $_POST['trade_time']);
		$dinpay_public_key = base64_decode("LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0NCk1JR2ZNQTBHQ1NxR1NJYjNEUUVCQVFVQUE0R05BRENCaVFLQmdRQ1o5V3BnL3ZnNDRXSGI1dG1mbnBYbmtEYkQNCjdTQzhlRUF2RWtBalN4MTdJMDFCZUk1bDdQM1dXMGNOUUQzUGc0RW5WU1JFNkN6SjZXWFBHbm0zVUhDa1pZSkMNClM3OG1CdStTVWJnUzMwclJGZXlqU1FsYWdPL1h2VytRSnFKenVrUFVyZGZxanErMEhrQkFCaWxUamxrV01oWHkNCjJmOThwWnYycEVVeUN3Ry83d0lEQVFBQg0KLS0tLS1FTkQgUFVCTElDIEtFWS0tLS0t");
		$merchant_code = $_POST["merchant_code"];
		$interface_version = $_POST["interface_version"];
		$sign_type = $_POST["sign_type"];
		$dinpaySign = base64_decode($_POST["sign"]);
		$notify_type = $_POST["notify_type"];
		$notify_id = $_POST["notify_id"];
		$order_no = $_POST["order_no"];
		$order_time = $_POST["order_time"];
		$order_amount = $_POST["order_amount"];
		$trade_status = $_POST["trade_status"];
		$trade_time = $_POST["trade_time"];
		$trade_no = $_POST["trade_no"];
		$bank_seq_no = $_POST["bank_seq_no"];
		$extra_return_param = @$_POST["extra_return_param"];
		$signStr = "";
		if ($bank_seq_no != "") {
			$signStr = $signStr . "bank_seq_no=" . $bank_seq_no . "&";
		}
		if ($extra_return_param != "") {
			$signStr = $signStr . "extra_return_param=" . $extra_return_param . "&";
		}
		$signStr = $signStr . "interface_version=" . $interface_version . "&";
		$signStr = $signStr . "merchant_code=" . $merchant_code . "&";
		$signStr = $signStr . "notify_id=" . $notify_id . "&";
		$signStr = $signStr . "notify_type=" . $notify_type . "&";
		$signStr = $signStr . "order_amount=" . $order_amount . "&";
		$signStr = $signStr . "order_no=" . $order_no . "&";
		$signStr = $signStr . "order_time=" . $order_time . "&";
		$signStr = $signStr . "trade_no=" . $trade_no . "&";
		$signStr = $signStr . "trade_status=" . $trade_status . "&";
		$signStr = $signStr . "trade_time=" . $trade_time;
		$dinpay_public_key = openssl_get_publickey($dinpay_public_key);
		$flag = openssl_verify($signStr, $dinpaySign, $dinpay_public_key, OPENSSL_ALGO_MD5);
		$returnData = ["orderno" => $order_no, "status" => 0, "msg" => $trade_status, "Remark" => ''];
		if ($flag == true) {
			if (strtolower($trade_status) == 'success') {
				$this->success();
				parent::write_log(["msg" => "充值成功", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
				$returnData["status"] = 1;
			} else {
				parent::write_log(["msg" => "提交支付信息失败，返回红色的提示信息", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
				$returnData["status"] = -1;
			}
		} else {
			parent::write_log(["msg" => "状态:汇潮交易数据有效性验证失败", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
			$returnData["status"] = -2;
		}
		config("PayReturn", $returnData);
	}
}