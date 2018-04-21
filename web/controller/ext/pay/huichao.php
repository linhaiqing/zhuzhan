<?php
namespace ext\pay;
class huichao extends movepay
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
		$parameter = $this->_data_format($this->pay_data);
		echo $this->_buildRequestForm($parameter);
	}

	private function _data_format($data)
	{
		$MerNo = $this->pay_data['auth_data']['username'];
		$MD5key = $this->pay_data['auth_data']['password'];
		$BillNo = $this->pay_data['orderno'];
		$Amount = $data['amount'];
		$ReturnURL = $data['base_url'] . self::ERTURN_URL;
		$AdviceURL = $data['base_url'] . self::NOTIFY_URL;
		$Remark = $data['remark'];
		$OrderTime = date('YmdHis');
		$md5src = "MerNo=" . $MerNo . "&BillNo=" . $BillNo . "&Amount=" . $Amount . "&OrderTime=" . $OrderTime . "&ReturnURL=" . $ReturnURL . "&AdviceURL=" . $AdviceURL . "&" . $MD5key;
		$SignInfo = strtoupper(md5($md5src));
		$products = $data['title'];
		$parameter = ["MerNo" => $MerNo, "BillNo" => $BillNo, "Amount" => $Amount, "ReturnURL" => $ReturnURL, "AdviceURL" => $AdviceURL, "SignInfo" => $SignInfo, 'OrderTime' => $OrderTime, "Remark" => $Remark, "products" => $products];
		return $parameter;
	}

	private function _buildRequestForm($paras)
	{
		$html = '<html><head><title>Payment By CreditCard online@move</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><form action="https://gwapi.yemadai.com/pay/sslpayment" method="post" name="E_FORM">';
		foreach ($paras as $k => $v) {
			$html .= '<input type="hidden" name="' . $k . '" value="' . $v . '">';
		}
		$html .= '<input type="submit" name="b1" value="Payment"></form>';
		$html .= '</form>';
		$html .= "<script>document.forms['E_FORM'].submit();</script>";
		return $html;
	}

	public function notify_url()
	{
		$MerNo = $this->pay_data['auth_data']['username'];
		$MD5key = $this->pay_data['auth_data']['password'];
		$BillNo = $_POST["BillNo"];
		$OrderNo = $_POST["OrderNo"];
		$Amount = $_POST["Amount"];
		$Succeed = $_POST["Succeed"];
		$Result = $_POST["Result"];
		$SignInfo = $_POST["SignInfo"];
		$Remark = $_POST["Remark"];
		$md5src = "MerNo=" . $MerNo . "&BillNo=" . $BillNo . "&OrderNo=" . $OrderNo . "&Amount=" . $Amount . "&Succeed=" . $Succeed . "&" . $MD5key;
		$md5sign = strtoupper(md5($md5src));
		$bool = 'failure';
		if ($SignInfo == $md5sign) {
			if ($Succeed == '88') {
				parent::write_log(["msg" => "notify_url 成功", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
				$this->success();
				$bool = 'ok';
			} else {
				parent::write_log(["msg" => "notify_url 提交支付信息失败，返回红色的提示信息状态:{$_POST['Succeed']}", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
			}
		} else {
			parent::write_log(["msg" => "notify_url 信息:合法性验证失败", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
		}
		echo $bool;
	}

	public function return_url()
	{
		$MerNo = $this->pay_data['auth_data']['username'];
		$MD5key = $this->pay_data['auth_data']['password'];
		$BillNo = $_POST["BillNo"];
		$OrderNo = $_POST["OrderNo"];
		$Amount = $_POST["Amount"];
		$Succeed = $_POST["Succeed"];
		$Result = $_POST["Result"];
		$SignInfo = $_POST["SignInfo"];
		$Remark = $_POST["Remark"];
		$md5src = "MerNo=" . $MerNo . "&BillNo=" . $BillNo . "&OrderNo=" . $OrderNo . "&Amount=" . $Amount . "&Succeed=" . $Succeed . "&" . $MD5key;
		$md5sign = strtoupper(md5($md5src));
		$returnData = ["orderno" => $BillNo, "status" => 0, "msg" => urldecode($Result), "Remark" => $Remark];
		if ($SignInfo == $md5sign) {
			if ($Succeed == '88') {
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