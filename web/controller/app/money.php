<?phpnamespace app;use Move\db;class money extends home{	public function __construct()	{		parent::__construct();	}	public function index()	{		$platform = iv('platform');		$banner = db::table("sys_ad")->where(["type" => "app"])->select();		foreach ($banner as $val) {			$res['banner'][] = "/upload/" . $val["img"];		}		$where["status"] = 1;		$res = parent::pullpage("money", $where, "id desc");		foreach ($res["list"] as $k => $v) {			$res["list"][$k]['img'] = "/upload/" . $res["list"][$k]['img'];			if (isset($platform) && $platform == 'pc') {				$res["list"][$k]['img'] = 'http://' . $_SERVER['HTTP_HOST'] . $res["list"][$k]['img'];			}			$res["list"][$k]['jieshu'] = time() - $v['endtime'] > 0 ? 1 : 0;			$res['list'][$k]['progress'] = round($v["deal"] / $v["num"], 4);		}		ajax($res, 1);	}	public function log()	{		$this->userid = $this->userid();		if (!$this->userid) {			ajax('请登录', -99);		}		$where["userid"] = $this->userid;		$res = parent::pullpage("money_log", $where, "id desc");		foreach ($res["list"] as $k => $v) {			$res["list"][$k]['addtime'] = addtime($v["addtime"]);			$res["list"][$k]['endtime'] = addtime($v["endtime"]);		}		ajax($res, 1);	}	public function fee()	{		$this->userid = $this->userid();		if (!$this->userid) {			ajax('请登录', -99);		}		$where["userid"] = $this->userid;		$res = parent::pullpage("money_fee", $where, "id desc");		foreach ($res["list"] as $k => $v) {			$res["list"][$k]['addtime'] = addtime($v["addtime"]);		}		ajax($res, 1);	}	public function buy()	{		$platform = iv('platform');		$id = iv('get.id', 'd', '参数错误');		$res['money'] = db::table("money")->where(["id" => $id])->find();		if (isset($platform) && $platform == 'pc') {			$res['money']["img"] = 'http://' . $_SERVER['HTTP_HOST'] . "/upload/" . $res['money']["img"];		} else {			$res['money']["img"] = 'http://' . $_SERVER['HTTP_HOST'] . "/upload/" . $res['money']["img"];		}		$res['money']['jieshu'] = time() - $res['money']["endtime"] > 0 ? 1 : 0;		$res['money']["strtime"] = addtime($res['money']["strtime"]);		$res['money']["endtime"] = addtime($res['money']["endtime"]);		$where["moneyid"] = $id;		$res["log"] = parent::pullpage("money_log", $where, "id desc");		foreach ($res["log"]["list"] as $k => $v) {			$res["log"]["list"][$k]['addtime'] = addtime($v["addtime"]);			$username = db::table("user")->where(["id" => $v["userid"]])->find();			if ($username["username"]) {				$res["log"]["list"][$k]['username'] = substr_replace(str_replace("|", " ", $username["username"]), '**', 3, 2);			}		}		$this->userid = $this->userid(1);		if ($this->userid) {			$coin = db::table("user_coin")->where(["userid" => $this->userid])->find();			$res['money']["mycoin"] = round($coin[$res['money']["coinname"]], 2);		}		ajax($res, 1);	}	public function up()	{		$this->userid = $this->userid();		if (!$this->userid) {			ajax('请登录', -99);		}		$id = iv('post.id', 'a', '参数错误');		$num = iv('post.num', 'a', '理财数量格式错误');		$paypassword = iv('post.paypassword', 'password', '交易密码格式错误');		$res = md('money')->up($this->userid, $id, $num, $paypassword);		if (isset($res[1]) && $res[1]) {			ajax('操作成功', 1);		} else {			ajax($res[0]);		}	}	public function tiqian()	{		$this->userid = $this->userid();		if (!$this->userid) {			ajax('请登录', -99);		}		$id = iv('post.id', 'd', '请选择解冻项');		$paypassword = iv('post.paypassword', 'password', '交易密码格式错误');		$res = md('money')->tiqian($this->userid, $id, $paypassword);		if (isset($res[1]) && $res[1]) {			ajax('操作成功', 1);		} else {			ajax($res[0]);		}	}	public function benjin()	{		$this->userid = $this->userid();		if (!$this->userid) {			ajax('请登录', -99);		}		$id = iv('post.id', 'd', '请选择解冻项');		$res = md('money')->benjin($this->userid, $id);		if (isset($res[1]) && $res[1]) {			ajax('操作成功', 1);		} else {			ajax($res[0]);		}	}}