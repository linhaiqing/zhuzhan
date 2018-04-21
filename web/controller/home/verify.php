<?php
namespace home;

use Move\db;


// 验证管理
class verify extends home
{
	// 初始化
	public function __construct()
	{
		parent::__construct();

		// 短信调试模式
		defined('MS_SMS') or define('MS_SMS', 0);

	}
	// 注册验证
	public function code()
	{
		verify();
	}
	// 注册验证
	public function moble_reg()
	{
		$moble  = iv("post.moble", 'd', '手机号码格式错误');
		$mobles = iv("post.mobles");
		if (!check($mobles, 'd', '+')) {
			ajax('国家编号错误');
		}
		$verify = iv("post.verify", 'dw', '验证码格式错误');
		$type   = iv("post.type", 'w', '发送方式错误');
		if (!verify($verify, "1")) {
			ajax('图形验证码错误');
		} else {
		}

		// 格式化手机号
		$moble = $mobles . '|' . $moble;

		// 检查存在
		$res = md('user')->cha_moble($moble);
		if (isset($res[1])) {
			ajax('手机号码已存在');
		}

		// 生成验证码
		$code = rand(111111, 999999);

		// 短信调试模式
		if (MS_SMS == 1) {
			session('verify_moble_reg', $code);
			ajax($code, 1);
			die;
		}

		$sms = new \ext\sms();
		$rs  = $sms->send($moble, $code, $type, 'reg');
		if (isset($rs[1])) {
			// 存储验证码
			session('verify_moble_reg', $code);
			ajax($rs[0], 1);
		} else {
			ajax($rs[0]);
		}
	}

	// 找回登录密码
	public function moble_findpwd()
	{

		// 2222222222222
		$moble  = iv("post.moble", 'd', '手机号码格式错误');
		$mobles = iv("post.mobles");
		if (!check($mobles, 'd', '+')) {
			ajax('国家编号错误');
		}
		$verify = iv("post.verify", 'dw', '验证码格式错误');
		$type   = iv("post.type", 'w', '发送方式错误');
		if (!verify($verify, "1")) {
			ajax('图形验证码错误');
		}

		// 格式化手机号
		$moble = $mobles . '|' . $moble;

		// 检查存在
		$res = md('user')->cha_moble($moble);
		if (!isset($res[1])) {
			ajax('手机号码不存在');
		}

		// 生成验证码
		$code = rand(111111, 999999);

		// 短信调试模式
		if (MS_SMS == 1) {
			session('verify_moble_findpwd', $code);
			session('verify_moble_findpwd_moble', $moble);
			ajax($code, 1);
			die;
		}
		$sms = new \ext\sms();
		$rs  = $sms->send($moble, $code, $type, 'findpwd');
		if (isset($rs[1])) {

			// 存储验证码
			session('verify_moble_findpwd', $code);
			session('verify_moble_findpwd_moble', $moble);
			ajax($rs[0], 1);
		} else {
			ajax($rs[0]);
		}
	}

	// 找回交易密码
	public function moble_findpwd_pay()
	{
		$moble  = iv("post.moble", 'd', '手机号码格式错误');
		$mobles = iv("post.mobles");
		if (!check($mobles, 'd', '+')) {
			ajax('国家编号错误');
		}
		$verify = iv("post.verify", 'dw', '验证码格式错误');
		$type   = iv("post.type", 'w', '发送方式错误');
		if (!verify($verify, "1")) {
			ajax('图形验证码错误');
		}

		// 格式化手机号
		$moble = $mobles . '|' . $moble;

		// 检查存在
		$res = md('user')->cha_moble($moble);
		if (!isset($res[1])) {
			ajax('手机号码不存在');
		}

		// 生成验证码
		$code = rand(111111, 999999);



		// 短信调试模式
		if (MS_SMS == 1) {
			session('verify_moble_findpwd_pay', $code);
			session('verify_moble_findpwd_pay_moble', $moble);
			ajax($code, 1);
			die;
		}
		$sms = new \ext\sms();
		$rs  = $sms->send($moble, $code, $type, 'findpwd_pay');
		if (isset($rs[1])) {
			// 存储验证码
			session('verify_moble_findpwd_pay', $code);
			session('verify_moble_findpwd_pay_moble', $moble);
			ajax($rs[0], 1);
		} else {
			ajax($rs[0]);
		}
	}

	// 绑定手机
	public function moble_set()
	{

		// 验证数据
		$moble  = iv("post.moble", 'd', '手机号码格式错误');
		$mobles = iv("post.mobles");
		if (!check($mobles, 'd', '+')) {
			ajax('国家编号错误');
		}
		$verify = iv("post.verify", 'dw');
		$type   = iv("post.type", 'w');
		(!verify($verify, "1")) && ajax('图形验证码错误');

		// 格式化手机号
		$moble = $mobles . '|' . $moble;

		// 检查存在
		$res = md('user')->cha_moble($moble);
		if (isset($res[1])) {
			ajax('手机号码已存在');
		}

		// 生成验证码
		$code = rand(111111, 999999);



		// 短信调试模式
		if (MS_SMS == 1) {
			session('moble_verify', $code);
			ajax($code, 1);
			die;
		}
		$sms = new \ext\sms();
		$rs  = $sms->send($moble, $code, $type, 'setmoble');
		if (isset($rs[1])) {
			// 存储验证码
			session('moble_verify', $code);
			ajax($rs[0], 1);
		} else {
			ajax($rs[0]);
		}
	}

	// 更新绑定手机
	public function moble_new()
	{

		// 检查数据
		$moble      = iv("post.moble", 'a', '旧手机号码格式错误');
		$moble_new  = iv("post.moble_new", 'd', '新手机号码格式错误');
		$mobles_new = iv("post.mobles_new");
		if (!check($mobles_new, 'd', '+')) {
			ajax('国家编号错误');
		}
		$type = iv("post.type", 'w');

		// 格式化手机号
		$moble_new = $mobles_new . '|' . $moble_new;

		// 检查存在
		$res = md('user')->cha_moble($moble_new);
		if (isset($res[1])) {
			ajax('手机号码已存在');
		}

		// 生成验证码
		$code = rand(111111, 999999);



		// 短信调试模式
		if (MS_SMS == 1) {
			session('moble_verify_new', $code);
			ajax($code, 1);
			die;
		}

		// 发送短信
		$sms = new \ext\sms();
		$rs  = $sms->send($moble, $code, $type, 'newmoble');
		if (isset($rs[1])) {
			// 存储验证码
			session('moble_verify_new', $code);
			ajax($rs[0], 1);
		} else {
			ajax($rs[0]);
		}
	}

	// 人民币提现
	public function moble_out()
	{
		$type = iv("post.type", 'w');
		$user = db::table("user")->where(["id" => $this->userid])->find();
		if (!$user) {
			ajax('请先登录');
		}
		$moble = $user['moble'];
		$code  = rand(111111, 999999);

		$time = session('moble_verify_cnyout_time');
		if (($time + 60 * 60) < time()) {
			session('moble_verify_cnyout_ci', null);
			session('moble_verify_cnyout_time', null);
		}
		$ci = session('moble_verify_cnyout_ci');
		if ($ci > 3) {
			ajax("频繁发送验证码，请稍后再试");
		}

		// 短信调试模式
		if (MS_SMS == 1) {
			session('moble_verify_cnyout', $code);
			session('moble_verify_cnyout_ci', ($ci + 1));
			session('moble_verify_cnyout_time', time());
			ajax($code, 1);
			die;
		}

		// 发送短信
		$sms = new \ext\sms();
		$rs  = $sms->send($moble, $code, $type, 'out');
		if (isset($rs[1])) {
			session('moble_verify_cnyout', $code);
			session('moble_verify_cnyout_ci', ($ci + 1));
			session('moble_verify_cnyout_time', time());
			ajax($rs[0], 1);
		} else {
			ajax($rs[0]);
		}
	}

	// 虚拟币转出
	public function moble_coinout()
	{
		$type = iv("post.type");
		$user = db::table("user")->where(["id" => $this->userid])->find();
		if (!$user) {
			ajax('请先登录');
		}
		$moble = $user['moble'];
		$code  = rand(111111, 999999);
		$time  = session('moble_verify_coinout_time');
		if (($time + 60 * 60) < time()) {
			session('moble_verify_coinout_ci', null);
			session('moble_verify_coinout_time', null);
		}
		$ci = session('moble_verify_coinout_ci');
		if ($ci > 10) {
			ajax("频繁发送验证码，请稍后再试");
		}

		// 短信调试模式
		if (MS_SMS == 1) {
			session('moble_verify_coinout', $code);
			session('moble_verify_coinout_ci', ($ci + 1));
			session('moble_verify_coinout_time', time());
			ajax($code, 1);
			die;
		}

		// 发送短信
		$sms = new \ext\sms();
		$rs  = $sms->send($moble, $code, $type, 'coinout');
		if (isset($rs[1])) {
			session('moble_verify_coinout', $code);
			session('moble_verify_coinout_ci', ($ci + 1));
			session('moble_verify_coinout_time', time());
			ajax($rs[0], 1);
		} else {
			ajax($rs[0]);
		}
	}

	// 异地登录
	public function yidi()
	{
		$type = iv("post.type");
		$user = db::table("user")->where(["id" => $this->userid])->find();
		if (!$user) {
			ajax('请先登录');
		}
		$moble = $user['moble'];
		$code  = rand(111111, 999999);
		$time  = session('moble_verify_yidi_time');
		if (($time + 60 * 60) < time()) {
			session('moble_verify_yidi_ci', null);
			session('moble_verify_yidi_time', null);
		}
		$ci = session('moble_verify_yidi_ci');
		if ($ci > 3) {
			ajax("频繁发送验证码，请稍后再试");
		}


		// 短信调试模式
		if (MS_SMS == 1) {
			session('moble_verify_yidi', $code);
			session('moble_verify_yidi_ci', ($ci + 1));
			session('moble_verify_yidi_time', time());
			ajax($code, 1);
			die;
		}

		// 发送短信
		$sms = new \ext\sms();
		$rs  = $sms->send($moble, $code, $type, 'yidi');
		if (isset($rs[1])) {
			session('moble_verify_yidi', $code);
			session('moble_verify_yidi_ci', ($ci + 1));
			session('moble_verify_yidi_time', time());
			ajax($rs[0], 1);
		} else {
			ajax($rs[0]);
		}
	}

	// push资产
	public function moble_push()
	{
		$type   = iv("post.type");
		$verify = iv("post.verify", 'dw', '验证码格式错误');
		if (!verify($verify, "1")) {
			ajax('图形验证码错误');
		}
		$user = db::table("user")->where(["id" => $this->userid])->find();
		if (!$user) {
			ajax('请先登录');
		}
		$moble = $user['moble'];
		$code  = rand(111111, 999999);
		$time  = session('moble_verify_push_time');
		if (($time + 60 * 60) < time()) {
			session('moble_verify_push_ci', null);
			session('moble_verify_push_time', null);
		}
		$ci = session('moble_verify_push_ci');
		if ($ci > 10) {
			ajax("频繁发送验证码，请稍后再试");
		}


		// 短信调试模式
		if (MS_SMS == 1) {
			session('moble_verify_push', $code);
			session('moble_verify_push_ci', ($ci + 1));
			session('moble_verify_push_time', time());
			ajax($code, 1);
			die;
		}

		// 发送短信
		$sms = new \ext\sms();
		$rs  = $sms->send($moble, $code, $type, 'push');
		if (isset($rs[1])) {
			session('moble_verify_push', $code);
			session('moble_verify_push_ci', ($ci + 1));
			session('moble_verify_push_time', time());
			ajax($rs[0], 1);
		} else {
			ajax($rs[0]);
		}
	}

    // 邮箱验证
	public function email()
	{
		$email  = iv("post.email",'email','邮箱格式错误');
		$type   = iv("post.type", 'w', '发送方式错误');
		// 生成验证码
		$code = rand(111111, 999999);

	
		if($this->user){
			$username = $this->user['username'];
		}else{
			$username ='新注册用户';
		}

		$title =  md('sys_config')->lists()['web_title'];

		$email_config =  md('user')->email_config();
		$type_arr = [];
		foreach($email_config as $k=>$v){
			$type_arr[] = $k;
		}

		if(in_array($type,$type_arr)){}else{
			ajax('不支持的发送类型');
		}

		$content = $email_config[$type].$code;

		$addr = ['email'=>$email,'name'=>$username,"title"=>$title,"content"=>$content];
		$payObj = new \ext\email();
		$res = $payObj->sendemail($addr,$email_config);
		if (isset($res[1])) {
			// 存储验证码
			session('verify_email_'.$type, $code);
			ajax($res[0], 1);
		} else {
			ajax($res[0]);
		}
	}

	public function whitelist()
	{
		$type = iv("post.type");
		$user = db::table("user")->where(["id" => $this->userid])->find();
		if (!$user) {
			ajax('请先登录');
		}
		$moble = $user['moble'];
		$code  = rand(111111, 999999);
		$time  = session('moble_verify_yidi_time');
		if (($time + 60 * 60) < time()) {
			session('moble_verify_whitelist_ci', null);
			session('moble_verify_whitelist_time', null);
		}
		$ci = session('moble_verify_whitelist_ci');
		if ($ci > 3) {
			ajax("频繁发送验证码，请稍后再试");
		}
		// 短信调试模式
		if (MS_SMS == 1) {
			session('moble_verify_whitelist', $code);
			session('moble_verify_yidi_whitelist', ($ci + 1));
			session('moble_verify_yidi_whitelist', time());
			ajax($code, 1);
			die;
		}

		// 发送短信
		$sms = new \ext\sms();
		$rs  = $sms->send($moble, $code, $type, 'whitelist');
		if (isset($rs[1])) {
			session('moble_verify_whitelist', $code);
			session('moble_verify_whitelist_ci', ($ci + 1));
			session('moble_verify_whitelist_time', time());
			ajax($rs[0], 1);
		} else {
			ajax($rs[0]);
		}
	}

}


