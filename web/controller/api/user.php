<?phpnamespace api;use Move\db;class user extends api{	public function index()	{	}	public function login()	{		$username = iv("post.username", 'a', '账号格式错误', 1);		$password = iv("post.password", 'password', '登录密码格式错误', 1);		$verify = iv("post.verify", 'dw', '验证码格式错误', 1);		$token = iv("post.token", 'dw', '令牌参数格式错误', 1);		$addip = get_ip();		$addr = get_city();		$time = time();		if ($verify && !verify($verify, "1")) {			ajax('图形验证码错误');		}		if (!session('api_user_login') || $token != session('api_user_login')) {			ajax('令牌错误');		}		if (isset($this->config['login_close']) && $this->config['login_close'] != 1) {			ajax('系统禁止登录');		}		if (isset($this->config['login_ip']) && $this->config['login_ip'] && in_array($addip, explode("|", $this->config['login_ip']))) {			ajax('您的ip禁止登录');		}		if (check($username, 'username')) {			$user = db::table('user')->where(['username' => $username])->find();			$remark = "通过用户名登录";		}		if (!$user && check($username, 'email')) {			$user = db::table('user')->where(['email' => $username])->find();			$remark = "通过邮箱登录";		}		if (!$user) {			$user = db::table('user')->where(['moble' => $username])->find();			$remark = "通过手机号登录";		}		if (!$user || $user["status"] != 1) {			ajax('您的账户不存在或已禁止登录');		}		if ($user["password"] != hashs($password)) {			ajax('您的账户或密码错误');		}		$rs = [];		db::exec("set autocommit=0");		db::exec("lock tables #pre#user write,#pre#user_log write");		$rs[] = db::table("user")->where(["id" => $user["id"]])->set(["logins" => ($user["logins"] + 1)]);		$rs[] = db::table("user_log")->add(["userid" => $user["id"], "type" => "登录", "remark" => $remark, "addip" => $addip, "addr" => $addr, "addtime" => $time, "status" => 1]);		if (check_arr($rs)) {			db::exec("commit");			db::exec("unlock tables");			session('userid', $user["id"]);			session("userid_" . $user["id"], hashs($_SERVER["HTTP_USER_AGENT"]));			session('api_user_login', null);			ajax('登录成功', 1);		} else {			db::exec("rollback");			db::exec("unlock tables");			ajax('登录失败');		}	}	public function reg()	{	}} 