<?php
namespace ext\pay;
class sandpay extends movepay
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
		if (!MS_GOPAY) {
			$MerNo = "0000001502";
			$VerficationCode = "11111aaaaa";
		}
		$gopayServerTime = file_get_contents("https://gateway.gopay.com.cn/time.do");
		if (!$gopayServerTime) {
			show_msg("gopayServerTime not get!");
		}
		$parameter = ["version" => "2.1", "charset" => "1", "language" => "1", "signType" => "1", "tranCode" => "8888", "merchantID" => $MerNo, "merOrderNum" => $BillNo, "tranAmt" => $Amount, "feeAmt" => 0, "currencyType" => "156", "frontMerUrl" => $ReturnURL, "backgroundMerUrl" => $AdviceURL, "tranDateTime" => $OrderTime, "virCardNoIn" => "0000000002000000257", "tranIP" => "127.0.0.1", "isRepeatSubmit" => "0", "goodsName" => $Remark, "gopayServerTime" => $gopayServerTime];
		$signStr = 'version=[' . $parameter['version'] . ']tranCode=[' . $parameter['tranCode'] . ']merchantID=[' . $parameter['merchantID'] . ']merOrderNum=[' . $parameter['merOrderNum'] . ']tranAmt=[' . $parameter['tranAmt'] . ']feeAmt=[' . $parameter['feeAmt'] . ']tranDateTime=[' . $parameter['tranDateTime'] . ']frontMerUrl=[' . $parameter['frontMerUrl'] . ']backgroundMerUrl=[' . $parameter['backgroundMerUrl'] . ']orderId=[]gopayOutOrderId=[]tranIP=[' . $parameter['tranIP'] . ']respCode=[]gopayServerTime=[' . $parameter['gopayServerTime'] . ']VerficationCode=[' . $VerficationCode . ']';
		$parameter['signValue'] = md5($signStr);
		return $parameter;
	}

	private function _buildRequestForm($paras)
	{
		if (defined("MS_GOPAY") && MS_GOPAY) {
			$action = 'https://cashier.sandpay.com.cn/gateway/api';
		} else {
			$action = 'http://61.129.71.103:8004/gateway/api';
		}
		$html = '<html><head><title>Payment By CreditCard online@move</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><form action="' . $action . '" method="post" name="E_FORM">';
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
		$version = $_POST["version"];
		$charset = $_POST["charset"];
		$language = $_POST["language"];
		$signType = $_POST["signType"];
		$tranCode = $_POST["tranCode"];
		$merchantID = $_POST["merchantID"];
		$merOrderNum = $_POST["merOrderNum"];
		$tranAmt = $_POST["tranAmt"];
		$feeAmt = $_POST["feeAmt"];
		$frontMerUrl = $_POST["frontMerUrl"];
		$backgroundMerUrl = $_POST["backgroundMerUrl"];
		$tranDateTime = $_POST["tranDateTime"];
		$tranIP = $_POST["tranIP"];
		$respCode = $_POST["respCode"];
		$msgExt = $_POST["msgExt"];
		$orderId = $_POST["orderId"];
		$gopayOutOrderId = $_POST["gopayOutOrderId"];
		$bankCode = $_POST["bankCode"];
		$tranFinishTime = $_POST["tranFinishTime"];
		$merRemark1 = $_POST["merRemark1"];
		$merRemark2 = $_POST["merRemark2"];
		$signValue = $_POST["signValue"];
		$signValue = $_POST["signValue"];
		$VerficationCode = "11111aaaaa";
		$signValue2 = 'version=[' . $version . ']tranCode=[' . $tranCode . ']merchantID=[' . $merchantID . ']merOrderNum=[' . $merOrderNum . ']tranAmt=[' . $tranAmt . ']feeAmt=[' . $feeAmt . ']tranDateTime=[' . $tranDateTime . ']frontMerUrl=[' . $frontMerUrl . ']backgroundMerUrl=[' . $backgroundMerUrl . ']orderId=[' . $orderId . ']gopayOutOrderId=[' . $gopayOutOrderId . ']tranIP=[' . $tranIP . ']respCode=[' . $respCode . ']gopayServerTime=[]VerficationCode=[' . $VerficationCode . ']';
		$signValue2 = md5($signValue2);
		$returnData = ["orderno" => $merOrderNum, "status" => 0, "msg" => urldecode($msgExt), "Remark" => $msgExt];
		$bool = '9999';
		if ($signValue == $signValue2) {
			if ($respCode == '0000') {
				parent::write_log(["msg" => "notify_url 成功", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
				$this->success();
				$bool = '0000';
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
		$version = $_POST["version"];
		$charset = $_POST["charset"];
		$language = $_POST["language"];
		$signType = $_POST["signType"];
		$tranCode = $_POST["tranCode"];
		$merchantID = $_POST["merchantID"];
		$merOrderNum = $_POST["merOrderNum"];
		$tranAmt = $_POST["tranAmt"];
		$feeAmt = $_POST["feeAmt"];
		$frontMerUrl = $_POST["frontMerUrl"];
		$backgroundMerUrl = $_POST["backgroundMerUrl"];
		$tranDateTime = $_POST["tranDateTime"];
		$tranIP = $_POST["tranIP"];
		$respCode = $_POST["respCode"];
		$msgExt = $_POST["msgExt"];
		$orderId = $_POST["orderId"];
		$gopayOutOrderId = $_POST["gopayOutOrderId"];
		$bankCode = $_POST["bankCode"];
		$tranFinishTime = $_POST["tranFinishTime"];
		$merRemark1 = $_POST["merRemark1"];
		$merRemark2 = $_POST["merRemark2"];
		$signValue = $_POST["signValue"];
		$signValue = $_POST["signValue"];
		$VerficationCode = "11111aaaaa";
		$signValue2 = 'version=[' . $version . ']tranCode=[' . $tranCode . ']merchantID=[' . $merchantID . ']merOrderNum=[' . $merOrderNum . ']tranAmt=[' . $tranAmt . ']feeAmt=[' . $feeAmt . ']tranDateTime=[' . $tranDateTime . ']frontMerUrl=[' . $frontMerUrl . ']backgroundMerUrl=[' . $backgroundMerUrl . ']orderId=[' . $orderId . ']gopayOutOrderId=[' . $gopayOutOrderId . ']tranIP=[' . $tranIP . ']respCode=[' . $respCode . ']gopayServerTime=[]VerficationCode=[' . $VerficationCode . ']';
		$signValue2 = md5($signValue2);
		$returnData = ["orderno" => $merOrderNum, "status" => 0, "msg" => urldecode($msgExt), "Remark" => $msgExt];
		if ($signValue == $signValue2) {
			if ($respCode == '0000') {
				$this->success();
				parent::write_log(["msg" => "充值成功", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
				$returnData["status"] = 1;
			} else {
				parent::write_log(["msg" => "提交支付信息失败，返回红色的提示信息", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
				$returnData["status"] = -1;
			}
		} else {
			parent::write_log(["msg" => "状态:国付宝交易数据有效性验证失败", "POST" => $_POST, "PAYDATA" => $this->pay_data]);
			$returnData["status"] = -2;
		}
		config("PayReturn", $returnData);
	}
}