<?php
namespace ext\pay;
class ailong extends movepay
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
		$this->pay_data["base_url"] = "http://www.coin86.com";
		$returnVal = $this->pay_data['auth_data']['value'];
		$ReturnURL = $returnVal ? $returnVal . self::ERTURN_URL : $this->pay_data['base_url'] . self::ERTURN_URL;
		$AdviceURL = $this->pay_data['base_url'] . self::NOTIFY_URL;
		$paras = [];
		$paras["signMethod"] = "md5";
		$paras["version"] = "1.0.0";
		$paras["txnType"] = "01";
		$paras["txnSubType"] = "01";
		$paras["bizType"] = "000000";
		$paras["accessType"] = "0";
		$paras["merId"] = $this->pay_data['auth_data']['username'];
		$paras["merOrderId"] = $this->pay_data['orderno'];
		$paras["txnTime"] = "20" . date("ymdhis");
		$paras["currency"] = "CNY";
		$paras["txnAmt"] = $this->pay_data['amount'] * 100;
		$paras["channelId"] = "";
		$paras["accessMode"] = "01";
		$paras["frontUrl"] = $ReturnURL;
		$paras["backUrl"] = $AdviceURL;
		$paras["payType"] = "0201";
		$paras["bankId"] = $_POST["bank"];
		$paras["dcType"] = "0";
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
		$html = '<html><head><title>Payment By CreditCard online@move</title><meta http-equiv="Content-Type"content="text/html; charset=utf-8"></head><body>            <form action="http://pay.chinagpay.com/bas/FrontTrans" method="post" name="E_FORM">';
		foreach ($paras as $k => $v) {
			$html .= '<input type="hidden" name="' . $k . '" value="' . $v . '">';
		}
		$html .= '<input type="submit" name="b1" value="正在跳转支付中心,请稍后..."></form>';
		$html .= '</form>';
		$html .= "<script>document.forms['E_FORM'].submit();</script>";
		$msg = "正在跳转支付中心,请稍后...";
		$a_str = base64_decode("PCFET0NUWVBFIGh0bWw+CjxodG1sPgo8aGVhZD4KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04Ij4KCTx0aXRsZT5Nb3Zlc2F5IOaguOW/g+ahhuaetumAmueUqOaPkOekujwvdGl0bGU+Cgk8bWV0YSBodHRwLWVxdWl2PSJYLVVBLUNvbXBhdGlibGUiIGNvbnRlbnQ9IklFPWVkZ2UsQ2hyb21lPTEiPgoJPG1ldGEgbmFtZT0idmlld3BvcnQiIGNvbnRlbnQ9IndpZHRoPWRldmljZS13aWR0aCwgaW5pdGlhbC1zY2FsZT0xLjAiPgoJPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCQkvKmNzcyByZXNldCBzdGFydCovCgkJaHRtbCwgYm9keSwgZGl2LCBzcGFuLCBhcHBsZXQsIG9iamVjdCwgaWZyYW1lLAoJCWgxLCBoMiwgaDMsIGg0LCBoNSwgaDYsIHAsIGJsb2NrcXVvdGUsIHByZSwKCQlhLCBhYmJyLCBhY3JvbnltLCBhZGRyZXNzLCBiaWcsIGNpdGUsIGNvZGUsCgkJZGVsLCBkZm4sIGVtLCBpbWcsIGlucywga2JkLCBxLCBzLCBzYW1wLAoJCXNtYWxsLCBzdHJpa2UsIHN0cm9uZywgc3ViLCBzdXAsIHR0LCB2YXIsCgkJYiwgdSwgaSwgY2VudGVyLAoJCWRsLCBkdCwgZGQsIG9sLCB1bCwgbGksCgkJZmllbGRzZXQsIGZvcm0sIGxhYmVsLCBsZWdlbmQsCgkJdGFibGUsIGNhcHRpb24sIHRib2R5LCB0Zm9vdCwgdGhlYWQsIHRyLCB0aCwgdGQsCgkJYXJ0aWNsZSwgYXNpZGUsIGNhbnZhcywgZGV0YWlscywgZW1iZWQsCgkJZmlndXJlLCBmaWdjYXB0aW9uLCBmb290ZXIsIGhlYWRlciwgaGdyb3VwLAoJCW1lbnUsIG5hdiwgb3V0cHV0LCBydWJ5LCBzZWN0aW9uLCBzdW1tYXJ5LAoJCXRpbWUsIG1hcmssIGF1ZGlvLCB2aWRlb3sKCQkJbWFyZ2luOjA7CgkJCXBhZGRpbmc6MDsKCQkJYm9yZGVyOjA7CgkJCWZvbnQtc2l6ZToxMDAlOwoJCQlmb250OmluaGVyaXQ7CgkJCXZlcnRpY2FsLWFsaWduOmJhc2VsaW5lOwoJCX0KCgkJYXJ0aWNsZSwgYXNpZGUsIGRldGFpbHMsIGZpZ2NhcHRpb24sIGZpZ3VyZSwKCQlmb290ZXIsIGhlYWRlciwgaGdyb3VwLCBtZW51LCBuYXYsIHNlY3Rpb257CgkJCWRpc3BsYXk6YmxvY2s7CgkJfQoKCQlib2R5ewoJCQlsaW5lLWhlaWdodDoxOwoJCX0KCgkJb2wsIHVsewoJCQlsaXN0LXN0eWxlOm5vbmU7CgkJfQoKCQlibG9ja3F1b3RlLCBxewoJCQlxdW90ZXM6bm9uZTsKCQl9CgoJCWJsb2NrcXVvdGU6YmVmb3JlLCBibG9ja3F1b3RlOmFmdGVyLAoJCXE6YmVmb3JlLCBxOmFmdGVyewoJCQljb250ZW50OicnOwoJCQljb250ZW50Om5vbmU7CgkJfQoKCQl0YWJsZXsKCQkJYm9yZGVyLWNvbGxhcHNlOmNvbGxhcHNlOwoJCQlib3JkZXItc3BhY2luZzowOwoJCX0KCgkJLypjc3MgcmVzZXQgZW5kKi8KCgkJaHRtbCwgYm9keXsKCQkJd2lkdGg6MTAwJTsKCQkJaGVpZ2h0OjEwMCU7CgkJCXRleHQtYWxpZ246Y2VudGVyOwoJCQlvdmVyZmxvdzpoaWRkZW47CgkJfQoKCQlodG1sewoJCQlmb250OjEwMCUvMS42ICdIZWx2ZXRpY2EgTmV1ZScsIEhlbHZldGljYSwgJ0hpcmFnaW5vIFNhbnMgR0InLCBTVEhlaXRpU0MtTGlnaHQsICdNaWNyb3NvZnQgWWFIZWknLCDlvq7ova/pm4Xpu5EsIEFyaWFsLCBzYW5zLXNlcmlmOwoJCX0KCgkJaW1newoJCQltYXgtd2lkdGg6MTAwJTsKCQkJaGVpZ2h0OjEwMCU7CgkJfQoKCQkuY29udGFpbmVyewoJCQl3aWR0aDoxMDAlOwoJCQloZWlnaHQ6MTAwJTsKCQkJcG9zaXRpb246cmVsYXRpdmU7CgkJfQoKCQkubWFpbnsKCQkJd2lkdGg6MTAwJTsKCQkJaGVpZ2h0OjEwMCU7CgkJfQoKCQkuZXJyLXRpcHMtY257CgkJCW1hcmdpbi10b3A6MzA5cHg7CgkJfQoKCQkjZXJyX2NvZGV7CgkJCWZvbnQtc2l6ZTozNnB4OwoJCQlmb250LXN0eWxlOml0YWxpYzsKCQkJY29sb3I6IzAwQTJDQTsKCQl9CgoJCSN0cGlzX2NuewoJCQltYXJnaW4tbGVmdDoyMHB4OwoJCQlmb250LXNpemU6MzJweDsKCQkJY29sb3I6IzMzMzsKCQl9CgoJCS5lcnItdGlwcy1lbnsKCQkJbWFyZ2luLXRvcDoyMnB4OwoJCQlmb250LXNpemU6NTVweDsKCQkJY29sb3I6IzY2NjsKCQl9CgoJCS5pbWctbGluZXsKCQkJbWFyZ2luLXRvcDoxMHB4OwoJCX0KCgkJLmhvc3QtaW5mb3sKCQkJbWFyZ2luLXRvcDoxMHB4OwoJCQljb2xvcjojNjY2OwoJCQlmb250LXNpemU6MThweDsKCQkJZm9udC13ZWlnaHQ6MjAwOwoJCX0KCgkJLmhvc3QtaW5mby1tYXJ7CgkJCW1hcmdpbi1sZWZ0OjIwcHg7CgkJfQoKCQlAbWVkaWEgc2NyZWVuIGFuZCAobWluLXdpZHRoOjk5MnB4KSBhbmQgKG1heC13aWR0aDoxMzY2cHgpewoJCQkuZXJyLXRpcHMtY257CgkJCQltYXJnaW4tdG9wOjIwMHB4OwoJCQl9CgoJCQkjZXJyX2NvZGV7CgkJCQlmb250LXNpemU6MTIwcHg7CgkJCX0KCgkJCSN0cGlzX2NuewoJCQkJZm9udC1zaXplOjQwcHg7CgkJCX0KCgkJCS5lcnItdGlwcy1lbnsKCQkJCWZvbnQtc2l6ZTozNXB4OwoJCQl9CgkJfQoKCQlAbWVkaWEgc2NyZWVuIGFuZCAgKG1pbi13aWR0aDo3NjlweCkgYW5kIChtYXgtd2lkdGg6OTkxcHgpewoJCQkuZXJyLXRpcHMtY257CgkJCQltYXJnaW4tdG9wOjIwMHB4OwoJCQl9CgoJCQkjZXJyX2NvZGV7CgkJCQlmb250LXNpemU6MTIwcHg7CgkJCX0KCgkJCSN0cGlzX2NuewoJCQkJZm9udC1zaXplOjQwcHg7CgkJCX0KCgkJCS5lcnItdGlwcy1lbnsKCQkJCWZvbnQtc2l6ZTozNXB4OwoJCQl9CgoJCQkuaG9zdC1pbmZvewoJCQkJZm9udC1zaXplOjIwcHg7CgkJCX0KCQl9CgoJCUBtZWRpYSBzY3JlZW4gYW5kIChtaW4td2lkdGg6NTIwcHgpIGFuZCAobWF4LXdpZHRoOjc2OHB4KXsKCQkJLmVyci10aXBzLWNuewoJCQkJbWFyZ2luLXRvcDozNTBweDsKCQkJfQoKCQkJI2Vycl9jb2RlewoJCQkJZm9udC1zaXplOjEwMHB4OwoJCQl9CgoJCQkjdHBpc19jbnsKCQkJCWZvbnQtc2l6ZTo0MHB4OwoJCQl9CgoJCQkuZXJyLXRpcHMtZW57CgkJCQlmb250LXNpemU6MzVweDsKCQkJfQoKCQkJLmhvc3QtaW5mb3sKCQkJCWZvbnQtc2l6ZToyMHB4OwoJCQl9CgkJfQoKCQlAbWVkaWEgc2NyZWVuIGFuZCAobWluLXdpZHRoOjM2MHB4KSBhbmQgKG1heC13aWR0aDo1MTlweCl7CgkJCS5lcnItdGlwcy1jbnsKCQkJCW1hcmdpbi10b3A6MjUwcHg7CgkJCX0KCgkJCSNlcnJfY29kZXsKCQkJCWZvbnQtc2l6ZTo2MHB4OwoJCQl9CgoJCQkjdHBpc19jbnsKCQkJCWZvbnQtc2l6ZTozMHB4OwoJCQl9CgoJCQkuZXJyLXRpcHMtZW57CgkJCQlmb250LXNpemU6MjVweDsKCQkJfQoKCQkJLmhvc3QtaW5mb3sKCQkJCWZvbnQtc2l6ZToxNXB4OwoJCQl9CgkJfQoKCQlAbWVkaWEgc2NyZWVuIGFuZCAobWF4LXdpZHRoOjM1OXB4KXsKCQkJLmVyci10aXBzLWNuewoJCQkJbWFyZ2luLXRvcDoyMDBweDsKCQkJfQoKCQkJI2Vycl9jb2RlewoJCQkJZm9udC1zaXplOjUwcHg7CgkJCX0KCgkJCSN0cGlzX2NuewoJCQkJZm9udC1zaXplOjI1cHg7CgkJCX0KCgkJCS5lcnItdGlwcy1lbnsKCQkJCWZvbnQtc2l6ZToyMHB4OwoJCQl9CgoJCQkuaG9zdC1pbmZvewoJCQkJZm9udC1zaXplOjEwcHg7CgkJCX0KCQl9CgoJCS8qaWU55Lul5LiL54mI5pys5qih5ouf5aqS5L2T5p+l6K+iKi8KCgkJLnc5OTIgLmVyci10aXBzLWNuewoJCQltYXJnaW4tdG9wOjIwMHB4OwoJCX0KCgkJLnc5OTIgI2Vycl9jb2RlewoJCQlmb250LXNpemU6MTIwcHg7CgkJfQoKCQkudzk5MiAjdHBpc19jbnsKCQkJZm9udC1zaXplOjQwcHg7CgkJfQoKCQkudzk5MiAuZXJyLXRpcHMtZW57CgkJCWZvbnQtc2l6ZTozNXB4OwoJCX0KCgkJLnc3NjkgLmVyci10aXBzLWNuewoJCQltYXJnaW4tdG9wOjIwMHB4OwoJCX0KCgkJLnc3NjkgI2Vycl9jb2RlewoJCQlmb250LXNpemU6MTIwcHg7CgkJfQoKCQkudzc2OSAjdHBpc19jbnsKCQkJZm9udC1zaXplOjQwcHg7CgkJfQoKCQkudzc2OSAuZXJyLXRpcHMtZW57CgkJCWZvbnQtc2l6ZTozNXB4OwoJCX0KCgkJLnc3NjkgLmhvc3QtaW5mb3sKCQkJZm9udC1zaXplOjIwcHg7CgkJfQoKCQkudzUyMSAuZXJyLXRpcHMtY257CgkJCW1hcmdpbi10b3A6MzUwcHg7CgkJfQoKCQkudzUyMSAjZXJyX2NvZGV7CgkJCWZvbnQtc2l6ZToxMDBweDsKCQl9CgoJCS53NTIxICN0cGlzX2NuewoJCQlmb250LXNpemU6NDBweDsKCQl9CgoJCS53NTIxIC5lcnItdGlwcy1lbnsKCQkJZm9udC1zaXplOjM1cHg7CgkJfQoKCQkudzUyMSAuaG9zdC1pbmZvewoJCQlmb250LXNpemU6MjBweDsKCQl9CgoJCS53MzIxIC5lcnItdGlwcy1jbnsKCQkJbWFyZ2luLXRvcDoyNTBweDsKCQl9CgoJCS53MzIxICNlcnJfY29kZXsKCQkJZm9udC1zaXplOjYwcHg7CgkJfQoKCQkudzMyMSAjdHBpc19jbnsKCQkJZm9udC1zaXplOjMwcHg7CgkJfQoKCQkudzMyMSAuZXJyLXRpcHMtZW57CgkJCWZvbnQtc2l6ZToyNXB4OwoJCX0KCgkJLnczMjEgLmhvc3QtaW5mb3sKCQkJZm9udC1zaXplOjE1cHg7CgkJfQoKCQkubHQtdzMyMCAuZXJyLXRpcHMtY257CgkJCW1hcmdpbi10b3A6MjAwcHg7CgkJfQoKCQkubHQtdzMyMCAjZXJyX2NvZGV7CgkJCWZvbnQtc2l6ZTo1MHB4OwoJCX0KCgkJLmx0LXczMjAgI3RwaXNfY257CgkJCWZvbnQtc2l6ZToyNXB4OwoJCX0KCgkJLmx0LXczMjAgLmVyci10aXBzLWVuewoJCQlmb250LXNpemU6MjBweDsKCQl9CgoJCS5sdC13MzIwIC5ob3N0LWluZm97CgkJCWZvbnQtc2l6ZToxMHB4OwoJCX0KCTwvc3R5bGU+CjwvaGVhZD4KPGJvZHk+CjxkaXYgY2xhc3M9ImNvbnRhaW5lciI+Cgk8ZGl2IGNsYXNzPSJtYWluIj4KCQk8ZGl2IGNsYXNzPSJlcnItdGlwcy1jbiI+CgkJCTxzcGFuIGlkPSJlcnJfY29kZSI+5rip6aao5o+Q56S6PC9zcGFuPjxzcGFuIGlkPSJ0cGlzX2NuIj46PC9zcGFuPgoJCTwvZGl2PgoJCTxkaXYgY2xhc3M9ImVyci10aXBzLWVuIj4=");
		$b_str = base64_decode("PC9kaXY+CgkJPGRpdiBjbGFzcz0iaW1nLWxpbmUiPgoJCQk8aW1nIGFsdD0iIiBzcmM9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFCbWtBQUFBQkNBWUFBQUExa0VwT0FBQUFHWFJGV0hSVGIyWjBkMkZ5WlFCQlpHOWlaU0JKYldGblpWSmxZV1I1Y2NsbFBBQUFBM0ZwVkZoMFdFMU1PbU52YlM1aFpHOWlaUzU0YlhBQUFBQUFBRHcvZUhCaFkydGxkQ0JpWldkcGJqMGk3N3UvSWlCcFpEMGlWelZOTUUxd1EyVm9hVWg2Y21WVGVrNVVZM3ByWXpsa0lqOCtJRHg0T25odGNHMWxkR0VnZUcxc2JuTTZlRDBpWVdSdlltVTZibk02YldWMFlTOGlJSGc2ZUcxd2RHczlJa0ZrYjJKbElGaE5VQ0JEYjNKbElEVXVOUzFqTURFMElEYzVMakUxTVRRNE1Td2dNakF4TXk4d015OHhNeTB4TWpvd09Ub3hOU0FnSUNBZ0lDQWdJajRnUEhKa1pqcFNSRVlnZUcxc2JuTTZjbVJtUFNKb2RIUndPaTh2ZDNkM0xuY3pMbTl5Wnk4eE9UazVMekF5THpJeUxYSmtaaTF6ZVc1MFlYZ3Ribk1qSWo0Z1BISmtaanBFWlhOamNtbHdkR2x2YmlCeVpHWTZZV0p2ZFhROUlpSWdlRzFzYm5NNmVHMXdUVTA5SW1oMGRIQTZMeTl1Y3k1aFpHOWlaUzVqYjIwdmVHRndMekV1TUM5dGJTOGlJSGh0Ykc1ek9uTjBVbVZtUFNKb2RIUndPaTh2Ym5NdVlXUnZZbVV1WTI5dEwzaGhjQzh4TGpBdmMxUjVjR1V2VW1WemIzVnlZMlZTWldZaklpQjRiV3h1Y3pwNGJYQTlJbWgwZEhBNkx5OXVjeTVoWkc5aVpTNWpiMjB2ZUdGd0x6RXVNQzhpSUhodGNFMU5Pazl5YVdkcGJtRnNSRzlqZFcxbGJuUkpSRDBpZUcxd0xtUnBaRG8xTXpSa1pUTTFNeTAxWTJFekxUTTNOR010T1dZd015MDBPV0ZqTmpjMlpXUTBaVFFpSUhodGNFMU5Pa1J2WTNWdFpXNTBTVVE5SW5odGNDNWthV1E2TXpoQk4wRTRRekkzTmpVeU1URkZOa0pCTVRkRVFrSkNORGMzTWpCR016WWlJSGh0Y0UxTk9rbHVjM1JoYm1ObFNVUTlJbmh0Y0M1cGFXUTZNemhCTjBFNFF6RTNOalV5TVRGRk5rSkJNVGRFUWtKQ05EYzNNakJHTXpZaUlIaHRjRHBEY21WaGRHOXlWRzl2YkQwaVFXUnZZbVVnVUdodmRHOXphRzl3SUVORElDaFhhVzVrYjNkektTSStJRHg0YlhCTlRUcEVaWEpwZG1Wa1JuSnZiU0J6ZEZKbFpqcHBibk4wWVc1alpVbEVQU0o0YlhBdWFXbGtPbUV5WXpRME56YzJMVE0xTldFdE5HTTBOUzA0TXpZekxUUTNZVFk1TWpRNFpURTVOeUlnYzNSU1pXWTZaRzlqZFcxbGJuUkpSRDBpZUcxd0xtUnBaRG8xTXpSa1pUTTFNeTAxWTJFekxUTTNOR010T1dZd015MDBPV0ZqTmpjMlpXUTBaVFFpTHo0Z1BDOXlaR1k2UkdWelkzSnBjSFJwYjI0K0lEd3ZjbVJtT2xKRVJqNGdQQzk0T25odGNHMWxkR0UrSUR3L2VIQmhZMnRsZENCbGJtUTlJbklpUHo3SjdGblFBQUFCdWtsRVFWUjQycXhXU1pMRU1BaURlY1Q4di9vNTh5bjFxV3RTTkl1RW5ac1R3aUtFak52cjc5ZitIN2Z2eDRrekh1OWQvRDk3ci9oNHh2KzhqK2VublQyK0lmaFRhcW4rcWI1M2NTM0pkeFBUbXJvWmZGbmNuZVNEaW10WC80U3ZIK1E5eGUwNFZ2WE9DeDR5T2FuNEd1bXJxZ2NERnhWZXNqWWJyazF6MHZITWgxbWRPS2pxMDVRTGd4T3J0UXl2WFp3M3U4QUpGelRiaUg1UCtxNW95cWxHZE9jZlVjZEE4UHBVbjFqZXFYWktuT3d1N09wRndMSzZzNWw3NEdTL1VPMXNpYnN0NTVuTjlZTW5EakZSOXdhR1F6ZHFaZlhXeU4ydDhyM0pzYk5Cd2YxcE53VzVUNnU5MmZxRjZNK0ZuakEyU0d4aDNJTlFRenhudHBsL05QNnJlRDdramlGdk5uNk13L2hsOHF2ZVJad1Erb2VFT3hOV0hUN1I3N1l2YkI4bVRFSDRmZWJNNE1EV0FxRkdORFBBekVlWCt5bSs4WHhTT3hyc0dheHdBYzhNWHh6MEVaZXc2SG9EY2VZaHpoaWFXTDZzaytYWlptWkFZc1hnek02ZTBoZDJmdFVjcDNzQmhJK08vNVdtTURxNzRlNVVWNGFaMGlkRkc2dFlKdlpON2FkZDBEOTE3bkZCcTJ5aG5kczdWcjBYRmEzZjRHd0xqV1h4ZDlQMmpnMi9YTXdCdzg0MGFZQWY1TWZhVGxxVmZhZDJ4N2NBQXdCcTJGS1JqNjdLSUFBQUFBQkpSVTVFcmtKZ2dnPT0iPgoJCTwvZGl2PgoJCTxkaXYgY2xhc3M9Imhvc3QtaW5mbyI+CgkJCTxzcGFuPui/lOWbnummlumhtTo8c3BhbiBpZD0iaXAiPjxhIGhyZWY9Ii8iIHN0eWxlPSJjb2xvcjogIzAwYTJjYTsiPummlumhtTwvYT48L3NwYW4+PC9zcGFuPgoJCTwvZGl2PgoJPC9kaXY+CjwvZGl2Pgo8L2JvZHk+CjwvaHRtbD4=");
		echo $a_str . $msg . $b_str;
		echo $html;
	}

	public function notify_url()
	{
		$paras = $_POST;
		$post_signature = @$paras['signature'];
		unset($paras['signMethod']);
		unset($paras['signature']);
		$paras['respMsg'] = base64_decode($paras['respMsg'], true);
		$paras['resv'] = base64_decode($paras['resv'], true);
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
		$paras = $_POST;
		$post_signature = @$paras['signature'];
		unset($paras['signMethod']);
		unset($paras['signature']);
		$paras['respMsg'] = base64_decode($paras['respMsg'], true);
		$paras['resv'] = base64_decode($paras['resv'], true);
		ksort($paras);
		$md5Key = $this->pay_data['auth_data']['password'];
		$msg = $this->signMsg($paras, $md5Key);
		$signature = base64_encode(md5($msg, TRUE));
		$returnData = ["orderno" => $paras["merOrderId"], "status" => 0, "msg" => "", "Remark" => ''];
		if ($signature == $post_signature) {
			if ($_POST["respCode"]) {
				$this->success();
				$returnData["status"] = 1;
			} else {
				$returnData["status"] = -1;
			}
		} else {
			$returnData["status"] = -2;
		}
		config("PayReturn", $returnData);
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