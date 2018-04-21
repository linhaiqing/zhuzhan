<?phpnamespace home;use Move\db;use Move\ext\page;class cissue extends home{	public function __construct()	{		parent::__construct();	}	public function index()	{		$this->set_title('充值认购');		$this->token = token('home_cissue_buy');		$issue = db::table("cissue")->find();		if ($issue) {			$mymc_coin = new \mymc\coin();			$coin_coinname = $mymc_coin->find($issue["coinname"]);			$coin_buycoin = $mymc_coin->find($issue["buycoin"]);			if (!isset($coin_coinname['img']) || !$coin_coinname['img']) {				$coin_coinname['img'] = '';			}			$issue["img"] = $coin_coinname['img'];			$issue["bili"] = num($issue["deal"] / $issue["num"] * 100);			if ($issue['status'] == 1) {				$issue["jieshu"] = ($issue["sndtime"] > time()) ? 1 : 0;				$issue["kaishi"] = ($issue["strtime"] > time()) ? 1 : 0;			} else {				$issue["jieshu"] = 0;				$issue["kaishi"] = 0;			}			$issue["buycoininfo"] = $coin_buycoin;			$issue["coininfo"] = $coin_coinname;		} else {			show_msg('项目不存在');		}		$this->assign('issue', $issue);		$this->display();	}	public function up()	{		$this->check_up('充值认购_立即认购');		$token = iv("post.token", 'dw', '令牌错误');		$num = iv('post.num', 'd', '充值数量格式错误');		$paypassword = iv('post.paypassword', 'password', '交易密码格式错误');		$type = iv('post.type', 'w', '充值方式格式错误');		$info = iv('post.info', 'a', '充值信息');		if (!$token || $token != session('home_cissue_buy')) {			ajax('恶意访问');		}		if (!$this->userid) {			ajax('请先登录');		}		$res = md('cissue')->up(session("userid"), $num, $paypassword, $type, $info);		if (isset($res[1]) && $res[1]) {			session('home_cissue_buy', null);			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}	public function status()	{		$this->check_up('充值认购_充值状态');		$id = iv('post.id', 'd', '数据编号错误');		$issue_log = db::table('cissue_log')->where(['id' => $id])->find();		if (empty($issue_log)) {			ajax('订单不存在', 0);		}		if ($issue_log['userid'] != session('userid')) {			ajax('非法操作', 0);		}		if ($issue_log['status'] != 0) {			ajax('当前状态不可操作', 0);		}		$res = db::table('cissue_log')->where(['id' => $id])->save(['status' => 1]);		if ($res) {			ajax('操作成功,等待后台审核', 1);		} else {			ajax('操作失败', 0);		}	}	public function log()	{		$this->set_title('认购记录');		if ($this->userid) {			$where = ['userid' => $this->userid];			$count = db::table('cissue_log')->where($where)->count();			$page_obj = new page($count, 15);			$show = $page_obj->show();			$list = db::table('cissue_log')->where($where)->order('`sort` asc ,id desc')->limit($page_obj->firstRow, $page_obj->listRows)->select();			if ($list) {				foreach ($list as $k => $v) {					$mymc_coin = new \mymc\coin();					$coin_coinname = $mymc_coin->find($v["coinname"]);					if (!isset($coin_coinname['img']) || !$coin_coinname['img']) {						$coin_coinname['img'] = '';					}					$list[$k]["img"] = $coin_coinname['img'];					if ($v["ci"]) {						$list[$k]["shen"] = num((($v["ci"] - $v["jd"]) * $v["num"] / $v["ci"]), 8);					} else {						$list[$k]["shen"] = 0;					}				}			}		} else {			$list = [];			$show = '';		}		$this->pages = ["list" => $list, "show" => $show];		$this->display();	}	public function jiedong()	{		$this->check_up('认购中心解冻');		$id = iv('post.id', 'd', '请选择解冻项');		if (!$this->userid) {			ajax('请先登录');		}		$res = md('cissue')->jiedong($this->userid, $id);		if (isset($res[1]) && $res[1]) {			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}	public function invit()	{		$this->set_title('认购赠送');		if ($this->userid) {			$where = ["userid" => $this->userid];			$count = db::table("cissue_invit")->where($where)->count();			$PageObj = new page($count, 12);			$show = $PageObj->show();			$list = db::table("cissue_invit")->where($where)->order("`id` desc")->limit($PageObj->firstRow, $PageObj->listRows)->select();			foreach ($list as $k => $v) {				if ($v['invit'] != 0) {					$list[$k]['invitname'] = db::table("user")->where(["id" => $v["invit"]])->find("username");				} else {					$list[$k]['invitname'] = '系统';				}			}		} else {			$list = [];			$show = '';		}		$this->pages = ["list" => $list, "show" => $show];		$this->display();	}} 