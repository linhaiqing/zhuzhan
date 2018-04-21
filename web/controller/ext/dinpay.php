<?php
namespace ext;
class dinpay
{
	public function submit()
	{
		$merchant_code = "2001010048";
		$notify_url = "http://mobtc.com/paytest/dinpay/notify_url";
		$order_amount = 0.01;
		$product_name = "dinpay";
		$return_url = "http://mobtc.com/paytest/dinpay/return_url";
		$order_no = "test001011";
		$merchant_private_key = '-----BEGIN PRIVATE KEY-----MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAMbrfnc4ap2tapPES4rcq/8T8CPqbKKbSu5/dhCUhEP63AomMi5zkg9vCgucOYBU6y3p5CUxOBRTFWzuxxhC22RsI2HQ3YWCIXeXwXn1iA7nX+8TJRSwlD/UepVrlkcctPcLapoldubyK7TEOuzS5GM4ZGEYHakNhpoPzHXeG5wLAgMBAAECgYEAt7BppWBUm+yzGWdQNxgWQEPCIvhsfzAWXu0XMrJi/X+fSEQDXP/eJiBsz1Hhi49faZOt2WZ9Gc+rXl+VPOMoQg9SR4aXj/BTZRDwmbc38VVRHoRs6FITyXWUiP9N74VChVgFxtetR5+a7YbUFLzzib6qHBrmCK2uJl4yYMelu+kCQQDvhXvhqS4Oj+H88WYlnj99Lw2vRNseixzD1KRnMtPfR4lIwhP1aSHeR6iFE9I2j9dEKwxgV4Y5tOhmhWq1YonVAkEA1Jrs0k3gV2dHGf/68Kjn45Ecc9AhaZzVAYEqvuSQxgVLXhifLEAcVyajG/KON/Df64+1xuVaAGNPWCZjMxWeXwJAZYspodmfdPz6ycltjLUyJVg1bQC8BI7lewBDUpzxxc8GHJpq67fOa/XlM8XbcNY6ZOP7Mzzn4r8XZ59YRd2ooQJAYJXFUsE4UtzDa2dkTCKfvj11TJPYKg+qoRVaIwDZWGydUbEj1smFVn5iCVdHDtjqESznauVU3uo+6I//7vw6RwJALcgtfPVeb7lkE7MaRWzJ4dKJjZu8H5HZ9sgH+xCdsShRbvx+YWIVULfNjl5awnGgUQMRGUAoYz41N7IDbasl+w==-----END PRIVATE KEY-----';
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
		$dinpay_public_key = '-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCZ9Wpg/vg44WHb5tmfnpXnkDbD7SC8eEAvEkAjSx17I01BeI5l7P3WW0cNQD3Pg4EnVSRE6CzJ6WXPGnm3UHCkZYJCS78mBu+SUbgS30rRFeyjSQlagO/XvW+QJqJzukPUrdfqjq+0HkBABilTjlkWMhXy2f98pZv2pEUyCwG/7wIDAQAB-----END PUBLIC KEY-----';
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
		if ($flag) {
			echo "SUCCESS";
		} else {
			echo "Verification Error";
		}
	}

	public function return_url()
	{
		$dinpay_public_key = '-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCZ9Wpg/vg44WHb5tmfnpXnkDbD7SC8eEAvEkAjSx17I01BeI5l7P3WW0cNQD3Pg4EnVSRE6CzJ6WXPGnm3UHCkZYJCS78mBu+SUbgS30rRFeyjSQlagO/XvW+QJqJzukPUrdfqjq+0HkBABilTjlkWMhXy2f98pZv2pEUyCwG/7wIDAQAB-----END PUBLIC KEY-----';
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
		if ($flag == true) {
			$result = "deposit successful";
		} else {
			$result = "deposit failed";
		}
	}
}