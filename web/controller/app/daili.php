<?phpnamespace app;use Move\db;use Move\ext\page;class daili extends home{	public function __construct()	{		parent::__construct();		$this->userid = $this->userid();	}	public function index()	{		$mymc_coin = new \mymc\coin();		$coin_list = $mymc_coin->select(1);		$res = [];		$res['coin_list'] = $coin_list;		ajax($res, 1);	}	public function log()	{		$this->set_title('充值记录');		$this->set_title('充值记录');		if ($this->userid) {			$where = ["dailiid" => $this->userid];			$res = parent::pullpage("daili_log", $where, "id desc");			$list = $res['list'];			$userList = db::table("user")->select();			foreach ($list as $k => $v) {				foreach ($userList as $k2 => $v2) {					if ($v2['id'] == $v['userid']) {						$list[$k]["username"] = $v2['username'];					}				}				$list[$k]["addtime"] = addtime($list[$k]["addtime"]);			}		} else {			$list = [];		}		$res['list'] = $list;		ajax($res, 1);	}	public function invit()	{		$this->set_title('充值赠送');		if ($this->userid) {			$where = ["userid" => $this->userid];			$res = parent::pullpage("daili_invit", $where, "id desc");			$list = $res['list'];			if ($list) {				foreach ($list as $k => $v) {					if ($v['invit'] != 0) {						$username = db::table('user')->where(["id" => $v['id']])->find("username");						$coin = md('coin')->getById($v['feecoin']);						$list[$k]['coin_title'] = isset($coin['title']) ? $coin['title'] : '';						$list[$k]['invitname'] = substr_replace(str_replace("|", " ", $username), '**', 3, 2);					} else {						$list[$k]['invitname'] = '系统';					}				}			}		} else {			$list = [];		}		$res['list'] = $list;		ajax($res, 1);	}	public function up()	{		$this->userid = $this->userid();		$coinname = iv('post.coinname', 'w', '币种类型错误');		$num = iv('post.num', 'xnb', '充值数量格式错误');		$paypassword = iv('post.paypassword', 'password', '交易密码格式错误');		$username = iv('post.username', 'username', '充值用户名格式错误');		if (!$this->userid) {			ajax("请先登录");		}		$res = md('daili')->up($this->userid, $coinname, $num, $paypassword, $username);		if (isset($res[1]) && $res[1]) {			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}}