<?php
namespace ext\pay;
class yeepay extends movepay
{
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
		$returnVal = $this->pay_data['auth_data']['value'];
		$reqURL_onLine = "https://www.yeepay.com/app-merchant-proxy/node";
		$merchantKey = $this->pay_data['auth_data']['password'];
		$data = [];
		$data['p0_Cmd'] = "Buy";
		$data['p1_MerId'] = $this->pay_data['auth_data']['username'];
		$data['p2_Order'] = $this->pay_data['orderno'];
		$data['p3_Amt'] = $this->pay_data['amount'];
		$data['p4_Cur'] = "CNY";
		$data['p5_Pid'] = "";
		$data['p6_Pcat'] = "";
		$data['p7_Pdesc'] = "";
		@$data['p8_Url'] = "http://www.yibaojy.com/pay/notify_url";
		$data['p9_SAF'] = "";
		$data['pa_MP'] = "movesay";
		$data['pd_FrpId'] = "";
		$data['pm_Period'] = "7";
		$data['pn_Unit'] = "day";
		$data['pr_NeedResponse'] = 1;
		$data['pt_UserName'] = "";
		$data['pt_PostalCode'] = "";
		$data['pt_Address'] = "";
		$data['pt_TeleNo'] = "";
		$data['pt_Mobile'] = "";
		$data['pt_Email'] = "";
		$data['pt_LeaveMessage'] = "";
		$hmac = $this->HmacMd5(implode($data), $merchantKey);
		eval(base64_decode("JHN0ciA9IDw8PEVPVA0KCQkJCQk8aHRtbD4NCgkJCQkJPGhlYWQ+DQoJCQkJCTx0aXRsZT5UbyBZZWVQYXkgUGFnZTwvdGl0bGU+DQoJCQkJCTwvaGVhZD4NCgkJCQkJPGJvZHkgb25sb2FkPSJkb2N1bWVudC55ZWVwYXkuc3VibWl0KCk7Ij4NCgkJCQkJPGZvcm0gbmFtZT0neWVlcGF5JyBhY3Rpb249J3skcmVxVVJMX29uTGluZX0nIG1ldGhvZD0ncG9zdCc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3AwX0NtZCcJCQkJCXZhbHVlPSd7JGRhdGFbInAwX0NtZCJdfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3AxX01lcklkJwkJCQl2YWx1ZT0neyRkYXRhWydwMV9NZXJJZCddfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3AyX09yZGVyJwkJCQl2YWx1ZT0neyRkYXRhWydwMl9PcmRlciddfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3AzX0FtdCcJCQkJCXZhbHVlPSd7JGRhdGFbJ3AzX0FtdCddfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3A0X0N1cicJCQkJCXZhbHVlPSd7JGRhdGFbJ3A0X0N1ciddfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3A1X1BpZCcJCQkJCXZhbHVlPSd7JGRhdGFbJ3A1X1BpZCddfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3A2X1BjYXQnCQkJCQl2YWx1ZT0neyRkYXRhWydwNl9QY2F0J119Jz4NCgkJCQkJPGlucHV0IHR5cGU9J2hpZGRlbicgbmFtZT0ncDdfUGRlc2MnCQkJCXZhbHVlPSd7JGRhdGFbJ3A3X1BkZXNjJ119Jz4NCgkJCQkJPGlucHV0IHR5cGU9J2hpZGRlbicgbmFtZT0ncDhfVXJsJwkJCQkJdmFsdWU9J3skZGF0YVsncDhfVXJsJ119Jz4NCgkJCQkJPGlucHV0IHR5cGU9J2hpZGRlbicgbmFtZT0ncDlfU0FGJwkJCQkJdmFsdWU9J3skZGF0YVsncDlfU0FGJ119Jz4NCgkJCQkJPGlucHV0IHR5cGU9J2hpZGRlbicgbmFtZT0ncGFfTVAnCQkJCQkJdmFsdWU9J3skZGF0YVsncGFfTVAnXX0nPg0KCQkJCQk8aW5wdXQgdHlwZT0naGlkZGVuJyBuYW1lPSdwZF9GcnBJZCcJCQkJdmFsdWU9J3skZGF0YVsncGRfRnJwSWQnXX0nPg0KCQkJCQk8aW5wdXQgdHlwZT0naGlkZGVuJyBuYW1lPSdwbV9QZXJpb2QnCQkJCXZhbHVlPSd7JGRhdGFbJ3BtX1BlcmlvZCddfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3BuX1VuaXQnCQkJCSAgdmFsdWU9J3skZGF0YVsncG5fVW5pdCddfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3ByX05lZWRSZXNwb25zZScJdmFsdWU9J3skZGF0YVsncHJfTmVlZFJlc3BvbnNlJ119Jz4NCgkJCQkJPGlucHV0IHR5cGU9J2hpZGRlbicgbmFtZT0ncHRfVXNlck5hbWUnCQkJdmFsdWU9J3skZGF0YVsncHRfVXNlck5hbWUnXX0nPg0KCQkJCQk8aW5wdXQgdHlwZT0naGlkZGVuJyBuYW1lPSdwdF9Qb3N0YWxDb2RlJwkJdmFsdWU9J3skZGF0YVsncHRfUG9zdGFsQ29kZSddfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3B0X0FkZHJlc3MnCQkJdmFsdWU9J3skZGF0YVsncHRfQWRkcmVzcyddfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J3B0X1RlbGVObycJCQkJdmFsdWU9J3skZGF0YVsncHRfVGVsZU5vJ119Jz4NCgkJCQkJPGlucHV0IHR5cGU9J2hpZGRlbicgbmFtZT0ncHRfTW9iaWxlJwkJCQl2YWx1ZT0neyRkYXRhWydwdF9Nb2JpbGUnXX0nPg0KCQkJCQk8aW5wdXQgdHlwZT0naGlkZGVuJyBuYW1lPSdwdF9FbWFpbCcJCQkJdmFsdWU9J3skZGF0YVsncHRfRW1haWwnXX0nPg0KCQkJCQk8aW5wdXQgdHlwZT0naGlkZGVuJyBuYW1lPSdwdF9MZWF2ZU1lc3NhZ2UnCXZhbHVlPSd7JGRhdGFbJ3B0X0xlYXZlTWVzc2FnZSddfSc+DQoJCQkJCTxpbnB1dCB0eXBlPSdoaWRkZW4nIG5hbWU9J2htYWMnCQkJCQkJdmFsdWU9J3skaG1hY30nPg0KCQkJCQk8L2Zvcm0+DQoJCQkJCTwvYm9keT4NCgkJCQkJPC9odG1sPg0KRU9UOw0KDQplY2hvICRzdHI7"));
	}

	public function notify_url()
	{
		$p1_MerId = $this->pay_data['auth_data']['username'];
		$merchantKey = $this->pay_data['auth_data']['password'];
		$data = array();
		if ($_REQUEST['r9_BType'] == 1) {
			$data['p1_MerId'] = $_REQUEST['p1_MerId'];
			$data['r0_Cmd'] = $_REQUEST['r0_Cmd'];
			$data['r1_Code'] = $_REQUEST['r1_Code'];
			$data['r2_TrxId'] = $_REQUEST['r2_TrxId'];
			$data['r3_Amt'] = $_REQUEST['r3_Amt'];
			$data['r4_Cur'] = $_REQUEST['r4_Cur'];
			$data['r5_Pid'] = iconv("utf-8", "gbk", $_REQUEST['r5_Pid']);
			$data['r6_Order'] = $_REQUEST['r6_Order'];
			$data['r7_Uid'] = $_REQUEST['r7_Uid'];
			$data['r8_MP'] = iconv("utf-8", "gbk", $_REQUEST['r8_MP']);
			$data['r9_BType'] = $_REQUEST['r9_BType'];
			$data['hmac'] = $_REQUEST['hmac'];
			$data['hmac_safe'] = $_REQUEST['hmac_safe'];
		} else {
			$data['p1_MerId'] = $_REQUEST['p1_MerId'];
			$data['r0_Cmd'] = $_REQUEST['r0_Cmd'];
			$data['r1_Code'] = $_REQUEST['r1_Code'];
			$data['r2_TrxId'] = $_REQUEST['r2_TrxId'];
			$data['r3_Amt'] = $_REQUEST['r3_Amt'];
			$data['r4_Cur'] = $_REQUEST['r4_Cur'];
			$data['r5_Pid'] = $_REQUEST['r5_Pid'];
			$data['r6_Order'] = $_REQUEST['r6_Order'];
			$data['r7_Uid'] = $_REQUEST['r7_Uid'];
			$data['r8_MP'] = $_REQUEST['r8_MP'];
			$data['r9_BType'] = $_REQUEST['r9_BType'];
			$data['hmac'] = $_REQUEST['hmac'];
			$data['hmac_safe'] = $_REQUEST['hmac_safe'];
		}
		$hmacLocal = $this->HmacLocal($data, $merchantKey);
		$safeLocal = $this->gethamc_safe($data, $p1_MerId, $merchantKey);
		if ($data['r9_BType'] == "1") {
			if ($data['hmac'] != $hmacLocal || $data['hmac_safe'] != $safeLocal) {
				$returnData["status"] = -2;
			} else {
				if ($data['r1_Code'] == "1") {
					$this->success();
					$returnData["status"] = 1;
				} else {
					$returnData["status"] = -1;
				}
			}
			config("PayReturn", $returnData);
			redirect('/finan/pay');
		} elseif ($data['r9_BType'] == "2") {
			$bool = "failure";
			if ($data['hmac'] != $hmacLocal || $data['hmac_safe'] != $safeLocal) {
			} else {
				if ($data['r1_Code'] == "1") {
					$this->success();
					$bool = 'SUCCESS';
				}
			}
			echo $bool;
		}
	}

	public function return_url()
	{
	}

	private function HmacMd5($data, $key)
	{
		$key = iconv("GBK", "UTF-8", $key);
		$data = iconv("GBK", "UTF-8", $data);
		$b = 64;
		if (strlen($key) > $b) {
			$key = pack("H*", md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad;
		$k_opad = $key ^ $opad;
		return md5($k_opad . pack("H*", md5($k_ipad . $data)));
	}

	private function HmacLocal($data, $merchantKey)
	{
		$text = "";
		while (list($key, $value) = each($data)) {
			if (isset($key) && $key != "hmac" && $key != "hmac_safe") {
				$text .= $value;
			}
		}
		return $this->HmacMd5($text, $merchantKey);
	}

	private function gethamc_safe($data, $p1_MerId, $merchantKey)
	{
		$text = "";
		while (list($key, $value) = each($data)) {
			if ($key != "hmac" && $key != "hmac_safe" && $value != null) {
				$text .= $value . "#";
			}
		}
		$text1 = rtrim(trim($text), '#');;
		return $this->HmacMd5($text1, $merchantKey);
	}
} 