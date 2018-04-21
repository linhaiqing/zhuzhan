<?phpnamespace home;use Move\db;use Move\ext\page;class charge extends home{	public function __construct()	{		parent::__construct();		// $this->is_game('charge');	}	public function index()	{		$this->set_title('卡密充值');		$this->display();	}	public function log()	{		$this->set_title('充值记录');		if ($this->userid) {			$where = ["userid" => $this->userid, "status#!=" => 0];			$count = db::table("charge_log")->where($where)->count();			$PageObj = new page($count, 15);			$show = $PageObj->show();			$list = db::table("charge_log")->where($where)->order("`id` desc")->limit($PageObj->firstRow, $PageObj->listRows)->select();			foreach ($list as $k => $v) {				$username = db::table("user")->where(["id" => $v["userid"]])->find();				$list[$k]["username"] = substr_replace(str_replace("|", " ", $username['username']), '**', 3, 2);			}		} else {			$show = [];			$list = [];		}		$pages = ['show' => $show, 'list' => $list];		$this->assign('pages', $pages);		$this->display();	}	public function up()	{		$this->check_up('话费充值提交');		$cardnum = iv('post.cardnum', "dw", "卡号格式错误");		$password = iv('post.paypassword', "dw", "密码格式错误");		if (!$this->userid) {			ajax("请先登录");		}		$user = db::table('user')->where(['id' => $this->userid])->find();		if (!$user) {			ajax('用户异常');		}		$res = md('charge')->up($this->userid, $cardnum, $password);		if (isset($res[1]) && $res[1]) {			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}} 