<?phpnamespace app;use home\coin;use Move\db;class duobao extends home{	public function __construct()	{		parent::__construct();	}	public function index()	{		$data = [];		$i = 0;		$m = 0;		$n = 0;		$typelist = db::table("duobao_type")->where(["status#!=" => 0, "pid" => 0])->order("sort asc ,id asc")->select();		foreach ($typelist as $v) {			$typelist2 = db::table("duobao_type")->where(["status" => 1, "pid" => $v["id"]])->limit(2)->field("id ,title")->select();			$data[$m++]["a"] = $v["title"];			$data[$i++]["b"] = $typelist2;			$data2 = db::table("duobao_type")->where(["status" => 1, "pid" => $v["id"]])->select();			if ($data2) {				$id_arr = [];				foreach ($data2 as $vv) {					$id_arr[] = $vv["id"];				}				$data3 = db::table("duobao_type")->where(["status" => 1, "pid#in" => $id_arr])->select();				if ($data3) {					$id_arr2 = [];					foreach ($data3 as $vv) {						$id_arr2[] = $vv["id"];					}					$duobaolist = db::table("duobao")->where(["status#!=" => 0, "type#in" => $id_arr2])->limit(3)->field("id,name,title,getnum,neednum,mum,status,img")->select();					foreach ($duobaolist as $key => $val) {						$duobaolist[$key]["bili"] = round($duobaolist[$key]["getnum"] / $duobaolist[$key]["mum"] * 100, 2);						$duobaolist[$key]["img"] = "/upload/" . $val['img'];					}				} else {					$duobaolist = [];				}			} else {				$duobaolist = [];			}			$data[$n++]["c"] = $duobaolist;		}		$data["new"] = db::table("duobao")->where(["kaitime#<" => time()])->order("kaitime desc")->field("id,name,title,getnum,neednum,mum,img,jianghao")->limit(3)->select();		foreach ($data["new"] as $key => $val) {			$data["new"][$key]["img"] = "/upload/" . $val['img'];		}		ajax($data, 1);	}	public function type_list()	{		$typelist1 = db::table("duobao_type")->where(["status#!=" => 0, "pid" => 0])->order("sort asc ,id desc")->field("id,title,pid")->select();		if ($typelist1) {			foreach ($typelist1 as $k => $v) {				$typelist1[$k]['pid_list'] = db::table("duobao_type")->where(["status" => 1, "pid" => $v['id']])->order("sort asc , id desc")->field("id,title,pid")->select();				if ($typelist1[$k]['pid_list']) {					foreach ($typelist1[$k]['pid_list'] as $kk => $vv) {						$typelist1[$k]['pid_list'][$kk]['pid_list'] = db::table("duobao_type")->where(["status" => 1, "pid" => $vv['id']])->order("sort asc , id desc")->field("id,title,pid")->select();						if ($typelist1[$k]['pid_list'][$kk]['pid_list']) {							foreach ($typelist1[$k]['pid_list'][$kk]['pid_list'] as $kkk => $vvv) {								$typelist1[$k]['pid_list'][$kk]['pid_list'][$kkk]['pid_list'] = db::table("duobao_type")->where(["status" => 1, "pid" => $vvv['id']])->field("id,title,pid")->order("sort asc , id desc")->select();							}						}					}				}			}		}		ajax($typelist1, 1);	}	public function lists()	{		$type2 = iv('get.type2', "d", "类型参数错误", 1);		$keyword = iv('get.keyword', "a", "关键字参数错误", 1);		$where = [];		if ($keyword) {			$where["title#like"] = "%" . $keyword . "%";		} else {			$keyword = "";		}		if ($type2) {			$where["type"] = $type2;		} else {			$type2 = 0;		}		$status = iv('get.status', "d", "状态参数错误", 1);		if ($status) {			if ($status == 1) {				$where["status"] = 1;			}			if ($status == 2) {				$where["status"] = 2;			}			if ($status == 3) {				$where["status"] = 3;			} else {				$where["status#!="] = 0;			}		} else {			$where["status#!="] = 0;		}		$sort = iv('get.sort', "d", "参数错误", 1);		if ($sort) {			if ($sort == 1) {				$order = "getnum";			}			if ($sort == 2) {				$order = "neednum";			}			if ($sort == 3) {				$order = "addtime";			}			if ($sort == 4) {				$order = "mum";			}		} else {			$sort = 0;			$order = "id";		}		$res["data"] = parent::pullpage("duobao", $where, "$order desc");		foreach ($res["data"]["list"] as $k => $v) {			$res["data"]["list"][$k]["bili"] = round($res["data"]["list"][$k]["getnum"] / $res["data"]["list"][$k]["mum"] * 100, 2);			$res["data"]["list"][$k]['img'] = "/upload/" . $res["data"]["list"][$k]['img'];		}		$res["get"]["type2"] = $type2;		$res["get"]["status"] = $status;		$res["get"]["sort"] = $sort;		$res["get"]["keyword"] = $keyword;		ajax($res, 1);	}	public function details()	{		$id = iv("id", "d", "宝贝编号 格式错误");		$duobao1 = db::table("duobao")->where(["id" => $id])->find();		if ($duobao1["endtime"] <= time() && $duobao1["status"] == 1) {			db::table("duobao")->where(["id" => $id])->save(["status" => 2]);		}		if ($duobao1["getnum"] == $duobao1["mum"] && $duobao1["status"] == 1) {			$endtime = time();			db::table("duobao")->where(["id" => $id])->save(["endtime" => $endtime, "status" => 2]);		}		$res["duobao"] = db::table("duobao")->where(["id" => $id])->find();		$res["duobao"]["addtime-ymd"] = addtime($res["duobao"]["addtime"], "y-m-d");		$res["duobao"]["addtime"] = addtime($res["duobao"]["addtime"]);		$res["duobao"]["kaitime-ymd"] = addtime($res["duobao"]["kaitime"], "y-m-d");		$res["duobao"]["kaitime"] = addtime($res["duobao"]["kaitime"]);		$res["duobao"]["endtime-ymd"] = addtime($res["duobao"]["endtime"], "y-m-d");		$res["duobao"]["endtime"] = addtime($res["duobao"]["endtime"]);		if (isset($res["duobao"]["coindata"])) {			$coinpay = explode(",", rtrim($res["duobao"]["coindata"], ","));			foreach ($coinpay as $k => $v) {				$mymc_coin = new \mymc\coin();				$duobao_coinpay = $mymc_coin->find(['id' => $v]);				$coinpay[$k] = $duobao_coinpay["title"];			}		} else {			$duobao_coinpay = '';			$coinpay = '';		}		$res["duobao"]["coinlist"] = $coinpay;		$res["duobao"]["imgarr"] = db::table("duobao_img")->where(["dbid" => $id, "status" => 1])->field("id ,img")->select();		foreach ($res["duobao"]["imgarr"] as $k => $v) {			$res["duobao"]["imgarr"][$k]["img"] = "/upload/" . $res["duobao"]["imgarr"][$k]["img"];		}		if ($res["duobao"]["logid"]) {			$duobao_log = db::table("duobao_log")->where(["id" => $res["duobao"]["logid"]])->find();			$user_name = db::table("user")->where(["id" => $duobao_log["userid"]])->find();			$res["duobao"]["lucky"] = substr_replace(str_replace("|", " ", $user_name["username"]), '**', 3, 2);;		}		if ($res["duobao"]["status"] > 1) {			$where["addtime#<="] = strtotime($res["duobao"]["endtime"]);			$limit = 7;			$order = "addtime desc";			$count = db::table('duobao_log')->where($where)->count();			if ($count >= 50) {				$res["last"]['count'] = 50;			} else {				$res["last"]['count'] = $count;			}			$pid = isset($_GET['pid']) ? (intval($_GET['pid']) > 1 ? intval($_GET['pid']) : 1) : 1;			$start = ($pid - 1) * $limit;			$res["last"]['pageSize'] = $limit;			$res["last"]['curPage'] = $pid;			$res["last"]['list'] = db::table('duobao_log')->where($where)->limit($start, $limit)->order($order)->field("id,userid,num,addtime")->select();			foreach ($res["last"]['list'] as $k => $v) {				$username = db::table("user")->where(['id' => $v["userid"]])->find();				$res["last"]['list'][$k]["username"] = substr_replace(str_replace("|", " ", $username['username']), '**', 3, 2);;				$res["last"]['list'][$k]["addtime"] = addtime($v["addtime"]);			}		}		if ($res["duobao"]["status"] == 1) {			$where = ["dbid" => $id];			$res["log"] = parent::pullpage("duobao_log", $where, "id desc", 7);			foreach ($res["log"]["list"] as $k => $v) {				$res["log"]["list"][$k]["addtime"] = addtime($res["log"]["list"][$k]["addtime"]);				$username = db::table("user")->where(['id' => $v["userid"]])->find();				$res["log"]["list"][$k]["username"] = substr_replace(str_replace("|", " ", $username["username"]), '**', 3, 2);;			}		}		ajax($res, 1);	}	public function buy()	{		$this->userid = $this->userid();		if (!$this->userid) {			ajax('请登录', -99);		}		$num = iv('post.num', 'd', '数量错误');		$coinname = iv('post.coinpay', 'a', '币种错误');		$coin = db::table("coin")->where(["title" => $coinname])->find();		$coinpay = strtolower($coin["name"]);		$dbid = iv('post.id', 'd', '商品错误');		$paypassword = iv("post.paypassword", "password", "交易密码格式错误");		$user_coin = db::table("user_coin")->where(["userid" => $this->userid])->find();		if (!$user_coin[$coinpay]) {			ajax("币种错误");		}		$user1 = db::table("user")->where(["id" => $this->userid])->find();		if (hashs($paypassword) != $user1["paypassword"]) {			ajax("交易密码错误");		}		$res = md('duobao')->buy($this->userid, $num, $coinpay, $dbid);		if (isset($res[1]) && $res[1]) {			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}	public function log()	{		$this->userid = $this->userid();		if (!$this->userid) {			ajax('请登录', -99);		}		$where["userid"] = $this->userid;		$where["status#!="] = 0;		$res = parent::pullpage("duobao_log", $where, "id desc");		foreach ($res["list"] as $k => $v) {			$res["list"][$k]["addtime"] = addtime($v["addtime"]);		}		ajax($res, 1);	}}