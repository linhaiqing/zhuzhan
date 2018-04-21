<?phpnamespace app;use Move\db;class crowd extends home{	public function __construct()	{		parent::__construct();	}	public function index()	{		md("crowd")->check();		$where["status#!="] = 0;		$res = parent::pullpage("crowd", $where, "id desc");		foreach ($res["list"] as $k => $v) {			$res["list"][$k]['progress'] = $v['deal'] / $v['num'] * $v["csa"];			$res["list"][$k]['endtime'] = addtime($v["endtime"]);		}		ajax($res, 1);	}	public function log()	{		$this->userid = $this->userid();		if (!$this->userid) {			ajax('请登录', -99);		}		$where = ["userid" => $this->userid];		$res = parent::pullpage("crowd_log", $where, "id desc");		foreach ($res["list"] as $k => $v) {			$res["list"][$k]['addtime'] = addtime($v["addtime"]);		}		ajax($res, 1);	}	public function details()	{		$id = iv("id", "d", "参数错误");		$res["crowd"] = db::table("crowd")->where(["id" => $id])->find();		$res["crowd"]["strtime"] = addtime($res["crowd"]["strtime"]);		$res["crowd"]["addtime"] = addtime($res["crowd"]["addtime"]);		$res["crowd"]["endtime"] = addtime($res["crowd"]["endtime"]);		$res["crowd_bili"] = $res["crowd"] ["deal"] / ($res["crowd"] ["num"] / $res["crowd"] ["csa"]) * 100;		$this->userid = $this->userid(1);		if ($this->userid) {			$user_coin = db::table("user_coin")->where(["userid" => $this->userid])->find();			$res["user_coin"] = $user_coin[$res["crowd"] ['feecoin']];		}		$mymc_coin = new \mymc\coin();		$coin1 = $mymc_coin->find($res["crowd"]['coinname']);		$coin2 = $mymc_coin->find($res["crowd"]['feecoin']);		$res["coin1title"] = $coin1["title"];		$res["coin2title"] = $coin2["title"];		$where["crowd_id"] = $id;		$res["log"] = parent::pullpage("crowd_log", $where, "id desc");		foreach ($res["log"]["list"] as $k => $v) {			$res["log"]["list"][$k]['addtime'] = addtime($v["addtime"]);			$res["log"]["list"][$k]['endtime'] = addtime($v["endtime"]);			$username = db::table("user")->where(["id" => $v["userid"]])->find();			if ($username["username"]) {				$res["log"]["list"][$k]['username'] = substr_replace(str_replace("|", " ", $username["username"]), '**', 3, 2);			}		}		ajax($res, 1);	}	public function up()	{		$this->set_title('众筹中心');		$id = iv('post.id', 'd', 'id格式错误');		$num = iv('post.num', 'd', '众筹数量格式错误');		$paypassword = iv('post.paypassword', 'password', '交易密码格式错误');		$this->userid = $this->userid();		if (!$this->userid) {			ajax('请先登录');		}		$user1 = db::table('user')->where(['id' => $this->userid])->find();		if (!$user1) {			ajax('用户异常');		}		if (hashs($paypassword) != $user1 ['paypassword']) {			ajax('交易密码错误');		}		$res = md('crowd')->up($this->userid, $id, $num);		if (isset($res[1]) && $res[1]) {			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}	public function fee()	{		$where = ["userid" => session("userid")];		db::table("crowd_fee")->select();		$count = db::table("crowd_fee")->where($where)->count();		$PageObj = new page($count, 12);		$show = $PageObj->show();		$list = db::table("crowd_fee")->where($where)->order("`id` desc")->limit($PageObj->firstRow, $PageObj->listRows)->select();		$pages = ['show' => $show, 'list' => $list];		$this->assign('pages', $pages);		$this->display();	}}