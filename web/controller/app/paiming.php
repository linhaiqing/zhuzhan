<?phpnamespace app;use Move\db;use Move\ext\speal;class paiming extends home{	public function __construct()	{		parent::__construct();	}	public function index()	{		$this->set_title('总资产排名');		$type = iv("get.type");		$lists = [];		if ($type == 0) {			$where = ['status' => 1, 'zzc_pc#<>' => 1, 'zzc#>' => '0.01'];			$res = parent::pullpage("user", $where, "zzc desc", 10);			$lists = $res['list'];			if ($lists) {				foreach ($lists as $k => $v) {					$lists[$k]['username'] = substr_replace(str_replace("|", " ", $v['username']), '**', 3, 2);				}			}			$img = "";		} else {			$mymc_coin = new \mymc\coin();			$coin_info = $mymc_coin->find(['id' => $type]);			$username_null = db::table('user')->where(['username' => ""])->select();			$username_data = [];			if ($username_null) {				foreach ($username_null as $k => $v) {					$username_data[] = $v['id'];				}			}			$status_null = db::table('user')->where(['status' => 0])->select();			if ($status_null) {				foreach ($status_null as $k => $v) {					$username_data[] = $v['id'];				}			}			if (!$username_data) {				$username_data = [0];			}			$where = ['id#not in' => $username_data];			$res = parent::pullpage("user_coin", $where, $coin_info['name'] . " desc", 10);			$lists = $res['list'];			$user = db::table('user')->where(['status' => 1])->select();			foreach ($lists as $k => $v) {				$lists[$k]["zzc"] = $v[$coin_info['name']];				$lists[$k]["dongjie"] = $v[$coin_info['name'] . "d"];				$lists[$k]["keyong"] = $v[$coin_info['name']] - $v[$coin_info['name'] . "d"];				foreach ($user as $k2 => $v2) {					if ($v['userid'] == $v2['id']) {						$username = $v2['username'];						$lists[$k]['username'] = substr_replace(str_replace("|", " ", $username), '***', 3, 2);					}				}			}		}		$res['list'] = $lists;		ajax($res, 1);	}	public function type()	{		$coin = db::table('coin')->where(["status" => 1])->field('id,name,title,img')->select();		foreach ($coin as $k => $v) {			$coin[$k]['img'] = '/upload/' . $v['img'];		}		ajax($coin, 1);	}}