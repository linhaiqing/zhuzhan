<?phpnamespace app;use Move\db;class fenhong extends home{	public function __construct()	{		parent::__construct();	}	public function index()	{		$where["status"] = 1;		$res = parent::pullpage("fenhong", $where, "id desc");		foreach ($res["list"] as $k => $v) {			$res["list"][$k]["addtime"] = addtime($res["list"][$k]["addtime"]);		}		ajax($res, 1);	}	public function buy()	{		$id = iv('get.id', 'd', '参数错误');		$res = db::table("fenhong")->where(["id" => $id])->find();		ajax($res, 1);	}	public function log()	{		$this->userid = $this->userid();		if (!$this->userid) {			ajax('请登录', -99);		}		$where["userid"] = $this->userid;		$res = parent::pullpage("fenhong_log", $where, "id desc");		foreach ($res["list"] as $k => $v) {			$res["list"][$k]["addtime"] = addtime($res["list"][$k]["addtime"]);		}		ajax($res, 1);	}}