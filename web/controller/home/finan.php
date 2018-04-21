<?phpnamespace home;use Move\db;use Move\ext\client;use Move\ext\page;use function PHPSTORM_META\elementType;class finan extends home{	public $all_xnb = [];	public $cnyin_type = [];	public $status = [];	public $xnb = [];	public $user_bank = [];	public $coinname = [];	public $market_list = [];	public $paiming = [];	public $zjc = [];	public $random = [];	public $type = [];	public $out_fee = [];	public $coin = [];	public $all_gjb = [];	public function __construct()	{		parent::__construct();		$this->is_login();		$this->is_goole();	}	public function index()	{		$this->set_title('财务中心');		if ($this->userid) {			$user_coin = db::table("user_coin")->where(['userid' => $this->userid])->find();			$paiming = md('user')->paiming($this->userid);		} else {			$user_coin = [];			$paiming = [];		}		$mymc_coin = new \mymc\coin();		$all_xnb = $mymc_coin->all_xnb(1);		$all_gjb = $mymc_coin->all_rmb(1);		$market_list = md('trade')->market_list();		foreach ($all_xnb as $k => $v) {			$all_xnb[$k]['price'] = isset($market_list[$v['name'] . '_cny']['new_price']) ? $market_list[$v['name'] . '_cny']['new_price'] : 1;			if ($this->userid) {				$all_xnb[$k]['price'] = $all_xnb[$k]['price'] * 1;				$all_xnb[$k]['dongjie'] = $user_coin[$v['name'] . 'd'];				$all_xnb[$k]['zongji'] = $user_coin[$v['name']] + $user_coin[$v['name'] . 'd'];				$user_coin[$v['name']] = $user_coin[$v['name']] * 1;			} else {				$all_xnb[$k]['price'] = 0;				$all_xnb[$k]['dongjie'] = 0;				$all_xnb[$k]['zongji'] = 0;				$user_coin[$v['name']] = 0;			}			$all_xnb[$k]['zhehe'] = $all_xnb[$k]['zongji'] * $all_xnb[$k]['price'];		}		$this->assign('user_coin', $user_coin);		$this->assign('all_xnb', $all_xnb);		$this->assign('all_gjb', $all_gjb);		$this->assign('paiming', $paiming);		$this->display();	}	public function pay()	{		$this->set_title('人民币充值');		$status = iv('get.status', 'd', $this->ajax_lang('状态错误'), 1);		$coin = iv("get.coin", 'w', $this->ajax_lang('参数错误'), 1);		if (!$coin) {			$coin = db::table('coin')->where(['status' => 1, 'wallet' => 'rmb'])->order('sort asc,id desc')->find('name');		}		$mymc_coin = new \mymc\coin();		$coin_select = $mymc_coin->all_rmb(1);		$user_coin = db::table("user_coin")->where(["userid" => $this->userid])->find();		$cnyin_type_data = db::table("finan_type")->where(["status" => 1])->select();		if ($cnyin_type_data) {			foreach ($cnyin_type_data as $k => $v) {				if ($v ['coinname'] == $coin) {					$cnyin_type[$k]['name'] = $v["name"];					$cnyin_type[$k]['img'] = $v["img"];				} else {					$cnyin_type = [];				}				$cnyin_list[$v["name"]] = $v;			}		} else {			$cnyin_list = [];		}		$where['userid'] = $this->userid;		if ($status > 0) {			$where['status'] = $status - 1;		}		$count = db::table('finan_pay')->where($where)->count();		$page_obj = new page($count, 10);		$show = $page_obj->show();		$list = db::table('finan_pay')->where($where)->order('`sort` asc ,id desc')->limit($page_obj->firstRow, $page_obj->listRows)->select();		$pages = ["list" => $list, "show" => $show];		if ($pages['list']) {			foreach ($pages['list'] as $k => $v) {				if ($v['type']) {					if (strpos($v['tradeno'], "MO") === 0) {						$pages['list'][$k]['online'] = 1;					} else {						$pages['list'][$k]['online'] = 0;					}					$pages['list'][$k]['types'] = $v['type'];					$pages['list'][$k]['type'] = isset($cnyin_list[$v['type']]['title']) ? $cnyin_list[$v['type']]['title'] : '未知类型';				}			}		}		$random = rand(10, 99);		$this->assign('coin_select', $coin_select);		$this->assign('cnyin_list', $cnyin_list);		$this->assign('user_coin', $user_coin);		$this->assign('coin', $coin);		$this->assign('status', $status);		$this->assign('random', $random);		$this->display();	}	public function pay_up()	{		$this->check_up("立即充值");		$type = iv("post.type", 'w', $this->ajax_lang('充值方式格式错误'));		$num = iv("post.num", 'cny', $this->ajax_lang('充值金额格式错误'));		$cnyin_type = db::table("finan_type")->where(["name" => $type])->find();		if (!$cnyin_type) {			ajax($this->ajax_lang('充值方式不存在'));		}		if ($cnyin_type['status'] != 1) {			ajax($this->ajax_lang('充值方式没有开通'));		}		$cnyin_min = $cnyin_type['min'] ? $cnyin_type['min'] : 1;		$cnyin_max = $cnyin_type['max'] ? $cnyin_type['max'] : 100000;		if ($num < $cnyin_min) {			ajax($this->ajax_lang('充值金额不能小于') . $cnyin_min);		}		if ($num > $cnyin_max) {			ajax($this->ajax_lang('充值金额不能大于') . $cnyin_max);		}		$tradeno = md('finan')->tradeno();		if ($cnyin_type['type'] == "online") {			$tradeno = "MO" . $cnyin_type['id'] . $tradeno;		}		if (!$tradeno) {			ajax($this->ajax_lang('订单号创建失败'));		}		if ($type == 'yinqihulian' || $type == 'ipay') {			$tradeno = $tradeno . '1234';		}		$data = ['userid' => $this->userid, 'num' => $num, 'coinname' => $cnyin_type['coinname'], 'type' => $type, 'tradeno' => $tradeno, 'addtime' => time(), 'status' => 0];		$res = db::table('finan_pay')->add($data);		if ($res) {			ajax($this->ajax_lang('创建成功'), 1, $res);		} else {			ajax($this->ajax_lang('订单创建失败'));		}	}	public function pay_status()	{		$id = iv("get.id", 'd', $this->ajax_lang('订单id错误'));		if (db::table('finan_pay')->where(['id' => $id, 'status' => 1, "userid" => $this->userid])->find()) {			ajax("已付款", 1);		} else {			ajax("未付款", -1);		}	}	public function pay_huikuan()	{		$this->check_up('已汇款');		$user = db::table("user")->where(["id" => $this->userid])->find();		$id = iv("post.id");		if (!check($id, 'd')) {			ajax('参数错误');		}		$cnyin = md('finan_pay')->get_info($id);		if (!$cnyin) {			ajax($this->ajax_lang('充值订单不存在'));		}		if ($cnyin['userid'] != $user['id']) {			ajax($this->ajax_lang('非法操作'));		}		if ($cnyin['status'] != 0) {			ajax($this->ajax_lang('订单已经处理过'));		}		if (isset($cnyin['type']) && $cnyin['type'] == 'tenpay') {			$remark = iv("post.remark", 'd', $this->ajax_lang('订单编号必须是数字'));		} else {			$remark = '';		}		$res = db::table('finan_pay')->where(['id' => $id])->save(['status' => 3, 'remark' => $remark]);		if ($res) {			ajax($this->ajax_lang('操作成功'), 1);		} else {			ajax($this->ajax_lang('操场失败'));		}	}	public function out()	{		$this->set_title('人民币提现');		$type = iv("type", 'd', '', 1);		if ($this->userid) {			$user_coin = db::table("user_coin")->where(['userid' => $this->userid])->find();		} else {			$user_coin = [];		}		$mymc_coin = new \mymc\coin();		$coins = $mymc_coin->all_rmb(1);		$coin_select = [];		if ($coins) {			foreach ($coins as $k => $v) {				$coin_select[$v['name']] = $v;			}		}		$coin = iv("get.coin", 'w', $this->ajax_lang('参数错误'), 1);		if (!$coin) {			$coin = $coin = db::table('coin')->where(['status' => 1, 'wallet' => 'rmb'])->order('sort asc,id desc')->find('name');;		}		if ($this->userid) {			$user_bank = db::table('user_bank')->where(['userid' => $this->userid, 'coinname' => $coin])->select();		} else {			$user_bank = [];		}		$out_fee = md('sys_config')->get('out_fee');		if (isset($this->user['moble']) && $this->user['moble']) {			$this->user['moble'] = substr_replace(str_replace("|", " ", $this->user['moble']), '****', 7, 4);		}		$goole = 0;		if (isset($this->user['google']) && $this->user['google']) {			$arr = explode('|', $this->user['google']);			if ($arr[2]) {				$goole = 1;			} else {				$goole = 0;			}		}		$sys_sms = db::table('sys_sms')->where(['status' => 1])->find();		if (!$sys_sms) {			$sys_sms['sms'] = 0;			$sys_sms['tel'] = 0;		}		$this->assign('goole', $goole);		$this->assign('out_fee', $out_fee);		$this->assign('user_bank', $user_bank);		$this->assign('sys_sms', $sys_sms);		$this->assign('type', $type);		$this->assign('user_coin', $user_coin);		$this->assign('coin_select', $coin_select);		$this->assign('coin', $coin);		$this->display();	}	public function outlog()	{		$this->set_title('人民币提现');		$status = iv('get.status', 'd', $this->ajax_lang('状态错误'), true);		$where['userid'] = $this->userid;		if ($status > 0) {			$where['status'] = $status - 1;		}		$count = db::table("finan_out")->where($where)->count();		$PageObj = new page($count, 15);		$show = $PageObj->show();		$list = db::table("finan_out")->where($where)->order("`addtime` desc")->limit($PageObj->firstRow, $PageObj->listRows)->select();		$pages = ['show' => $show, 'list' => $list];		$this->assign("pages", $pages);		$this->assign("status", $status);		$this->display();	}	public function out_up()	{		$this->check_up('提现');		$type = iv("post.bank", 'd', $this->ajax_lang('提现地址错误'));		$num = iv("post.num", 'cny', $this->ajax_lang('提现金额格式错误'));		$moble_verify = iv("post.moble_verify");		$goole = iv("post.goole", 'd', $this->ajax_lang('谷歌验证码格式错误'), 1);		$paypassword = iv("post.paypassword", 'password', $this->ajax_lang('交易密码格式错误'));		$coinname = iv("post.coinname", 'w', $this->ajax_lang('币种格式错误'));		if (!$moble_verify || $moble_verify != session('moble_verify_cnyout')) {			ajax($this->ajax_lang('短信验证码错误'));		}		$num = num($num, 0);		if (!$num) {			ajax($this->ajax_lang('提现金额错误'));		}		$res = md('finan')->out_up($this->userid, $type, $num, $goole, $paypassword, $coinname);		if (isset($res[1]) && $res[1]) {			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}	public function email_out_up()	{		$this->check_up('提现');		$type = iv("post.bank", 'd', $this->ajax_lang('提现地址错误'));		$num = iv("post.num", 'cny', $this->ajax_lang('提现金额格式错误'));		$email_verify = iv("post.moble_verify");		$goole = iv("post.goole", 'd', $this->ajax_lang('谷歌验证码格式错误'), 1);		$paypassword = iv("post.paypassword", 'password', $this->ajax_lang('交易密码格式错误'));		$coinname = iv("post.coinname", 'w', $this->ajax_lang('币种格式错误'));		if (!$email_verify || $email_verify != session('email_out_verify_code')) {			ajax($this->ajax_lang('邮箱验证码错误'));		}		$num = num($num, 0);		if (!$num) {			ajax($this->ajax_lang('提现金额错误'));		}		$res = md('finan')->out_up($this->userid, $type, $num, $goole, $paypassword, $coinname);		if (isset($res[1]) && $res[1]) {			ajax($res[0], 1);		} else {			ajax($res[0]);		}	}	public function out_chexiao()	{		$this->check_up('撤销提现');		$id = iv("post.id", 'd', $this->ajax_lang('参数错误'));		$out = db::table("finan_out")->where(["id" => $id])->find();		if ($out["status"] != 0) {			ajax($this->ajax_lang("订单不能撤销"));		}		$res = md('finan')->pay_chexiao($id, $this->userid);		if (isset($res[1]) && $res[1]) {			ajax($this->ajax_lang($res[0]), 1);		} else {			ajax($this->ajax_lang($res[0]));		}	}	public function bank()	{		$this->set_title('银行卡管理');		$coin = iv("get.coin", 'w', $this->ajax_lang('参数错误'), 1);		$mymc_coin = new \mymc\coin();		$coins = $mymc_coin->all_rmb(1);		$coin_select = [];		if ($coins) {			foreach ($coins as $k => $v) {				$coin_select[$v['name']] = $v;			}		}		if (!$coin) {			$coin = db::table('coin')->where(['status' => 1, 'wallet' => 'rmb'])->order('sort asc,id desc')->find('name');		}		$user_bank = db::table('user_bank')->where(['userid' => $this->userid, 'coinname' => $coin])->select();		foreach ($user_bank as $k => $v) {			$user_bank[$k]['bankcard'] = substr_replace($v['bankcard'], '****', 4, 4);		}		$bank_list = md('sys_config')->get('out_bank');		$bank_list = explode("|", $bank_list);		$this->assign('bank_list', $bank_list);		$this->assign('coin', $coin);		$this->assign('coin_select', $coin_select);		$this->assign('user_bank', $user_bank);		$this->display();	}	public function bank_up()	{		$this->check_up('添加银行卡');		$name = iv('post.name', 'a', $this->ajax_lang('备注名称格式错误'));		$bank = iv('post.bank', 'c', $this->ajax_lang('开户银行格式错误'));		$bankprov = iv('post.bankprov', 'c', $this->ajax_lang('开户省份格式错误'));		$bankcity = iv('post.bankcity', 'c', $this->ajax_lang('开户城市格式错误'), 1);		$bankaddr = iv('post.bankaddr', 'a', $this->ajax_lang('开户行地址格式错误'));		$bankcard = iv('post.bankcard', 'a', $this->ajax_lang('银行账号格式错误'));		$coinname = iv('post.bank_coinname', 'w', $this->ajax_lang('币种格式错误'));		$paypassword = iv('post.paypassword', 'password', $this->ajax_lang('交易密码格式错误'));		$check_card = $this->luhm($bankcard);		if (!$check_card) {			ajax($this->ajax_lang('银行卡号错误'));		}		$user = db::table("user")->where(["id" => $this->userid])->find();		if ($user['paypassword'] != hashs($paypassword)) {			ajax($this->ajax_lang('交易密码错误'));		}		if (isset($user['truename']) && !$user['truename']) {			ajax($this->ajax_lang('请完善实名信息'));		}		$user_bank = db::table('user_bank')->where(['userid' => $this->userid, 'coinname' => $coinname])->select();		foreach ($user_bank as $k => $v) {			if ($v['name'] == $name) {				ajax($this->ajax_lang('请不要使用相同的备注名称'));			}			if ($v['bankcard'] == $bankcard) {				ajax($this->ajax_lang('银行卡号已存在'));			}		}		if (count($user_bank) >= 10) {			ajax($this->ajax_lang('每个用户每个币种最多只能添加10个地址'));		}		$res = db::table("user_bank")->add(['userid' => session('userid'), 'name' => $name, 'bank' => $bank, 'bankprov' => $bankprov, 'coinname' => $coinname, 'bankcity' => $bankcity, 'truename' => $user['truename'], 'bankaddr' => $bankaddr, 'bankcard' => $bankcard, 'addtime' => time(), 'status' => 1,]);		if ($res[0]) {			ajax($this->ajax_lang('操作成功'), 1);		} else {			ajax($this->ajax_lang($res[0]));		}	}	function luhm($no)	{		$arr_no = str_split($no);		$last_n = $arr_no[count($arr_no) - 1];		krsort($arr_no);		$i = 1;		$total = 0;		foreach ($arr_no as $n) {			if ($i % 2 == 0) {				$ix = $n * 2;				if ($ix >= 10) {					$nx = 1 + ($ix % 10);					$total += $nx;				} else {					$total += $ix;				}			} else {				$total += $n;			}			$i++;		}		$total -= $last_n;		$total *= 9;		if ($last_n == ($total % 10)) {			return 1;		} else {			return 0;		}	}	public function bank_del()	{		$this->check_up('删除银行卡');		$id = iv('post.id', 'd', $this->ajax_lang('参数错误'));		$paypassword = iv('post.paypassword', 'password', $this->ajax_lang('交易密码格式错误'));		$user = db::table("user")->where(["id" => $this->userid])->find();		if (!$user) {			ajax($this->ajax_lang('用户异常'));		}		($user['paypassword'] != hashs($paypassword)) && ajax($this->ajax_lang('交易密码错误'));		$user_bank = db::table('user_bank')->where(["id" => $id])->find();		if (!$user_bank || $user_bank["userid"] != $user['id']) {			ajax($this->ajax_lang('非法访问'));		}		$res = db::table("user_bank")->where(["id" => $id])->delete();		if ($res) {			ajax($this->ajax_lang('操作成功'), 1);		} else {			ajax($this->ajax_lang('操作失败'));		}	}	public function coinin()	{		$this->set_title('转入虚拟币');		$coin = iv("get.coin", 'w', $this->ajax_lang('参数错误'), 1);		if (!$coin) {			$coin = md('sys_config')->get('mr_coin');		}		$mymc_coin = new \mymc\coin();		$coin_info = $mymc_coin->find($coin);		if (!$coin_info) {			show_msg($this->ajax_lang("币种不存在"));		}		$user_wallet = db::table('user_wallet')->where(['userid' => $this->userid])->find();		if (!isset($user_wallet[$coin]) && $coin_info['wallet'] != 'deth') {			md('common')->execute("ALTER TABLE  `move_user_wallet` ADD  `" . $coin . "` VARCHAR(255) NOT NULL");		}		$mymc_coin = new \mymc\coin();		$coin_list_tmp = $mymc_coin->all_xnb(1);		if ($coin_list_tmp) {			foreach ($coin_list_tmp as $k => $v) {				$coin_list[$v['name']] = $v;			}		} else {			$coin_list = [];		}		$user_coin = md('user')->get_coin_info();		$check_sure = '';		$check_address = [];		if ($coin_info['wallet'] == 'newcoin') {			$check_address = db::table('user_qianbao')->where(['userid' => $this->userid, 'coinname' => $coin, 'type' => 1])->select();			if (empty($check_address)) {				$check_sure = 1;				$this->assign('check_sure', $check_sure);			} else {				$this->assign('check_address', $check_address);			}		}		$token = token('home_finan_coinin');		$memo = null;		if ($coin_info['wallet'] == "gxb") {			$qianbao = db::table("user_wallet")->where(["userid" => session("userid")])->find($coin);			$memo = session("userid");			if (!$qianbao) {				$address = $coin_info['dj_yh'];				db::table("user_wallet")->where(["userid" => session("userid")])->save([$coin => $address]);			}		} else if ($coin_info['wallet'] == "eth" || $coin_info['wallet'] == "deth") {			$qianbao = db::table("user_wallet")->where(["userid" => session("userid")])->find('eth');			if (!$qianbao) {				$host = $coin_info['dj_zj'];				$port = $coin_info["dj_dk"];				$password = $coin_info['dj_jm'];				$pararm = 'coin=eth&action=newAccount';				if ($coin_info['wallet'] == "wkc") {					$pararm = 'coin=wkc&action=newAccount';				}				$qianbao = \ext\coin\mscoin::mycurl($host, $port, $password, $pararm);				if (isset($qianbao['status']) && $qianbao['status'] == 1) {					$qianbao = $qianbao['msg'];					if ($coin_info['wallet'] == 'wkc') {						$res = db::table("user_wallet")->where(["userid" => session("userid")])->save([$coin => $qianbao]);					} else {						$res = db::table("user_wallet")->where(["userid" => session("userid")])->save(['eth' => $qianbao]);					}					if (!$res) {						$qianbao = $this->ajax_lang('钱包地址创建失败');					}				} else {					$qianbao = $this->ajax_lang('获取钱包地址失败');				}			}		} else {			if ($coin_info['wallet'] == "tsb" || $coin_info['wallet'] == "wyc" || $coin_info['wallet'] == "asch" || $coin_info['wallet'] == "newcoin") {				$qianbao = $coin_info['tsb_addr'];				if ($coin_info['name'] == 'cxc') {					$qianbao = db::table("user_wallet")->where(["userid" => session("userid")])->find($coin);					if (!$qianbao) {						$secret = \ext\coin\cxc::calSecret();						$account = \ext\coin\cxc::createAddress($secret);						$account = json_decode($account, true);						if (isset($account['success']) && $account['success']) {							$res_account = $account['account'];							$wallet = [];							$wallet['secret'] = $secret;							$wallet['addr'] = $res_account['address'];							$wallet['publicKey'] = $res_account['publicKey'];							$walletString = json_encode($wallet);							db::exec("set autocommit=0");							db::exec("lock tables  #pre#user_wallet write");							$res = [];							$res[] = db::table("user_wallet")->where(["userid" => session("userid")])->save([$coin . '_account' => $walletString]);							$res[] = db::table('user_wallet')->where(['userid' => session('userid')])->save([$coin => $wallet['addr']]);							if (check_arr($res)) {								db::exec("commit");								db::exec("unlock tables");								$qianbao = $wallet['addr'];							} else {								db::exec("rollback");								db::exec("unlock tables");								$qianbao = "";							}						} else {							$qianbao = "";						}					}				}			} else {				$qianbao = md('user')->get_wallet_addr($coin);			}		}		$where = ["userid" => $this->userid, "coinname" => $coin];		$count = db::table('coin_in')->where($where)->count();		$page_obj = new page($count, 5);		$show = $page_obj->show();		$list = db::table('coin_in')->where($where)->order('`sort` asc ,id desc')->limit($page_obj->firstRow, $page_obj->listRows)->select();		$this->assign('qianbao', $qianbao);		$this->assign('memo', $memo);		$this->pages = ["list" => $list, "show" => $show];		$this->assign("coin", $coin);		$this->assign('coin_list', $coin_list);		$this->assign('user_coin', $user_coin);		$this->assign('coin_info', $coin_info);		$this->assign('token', $token);		$this->display();	}	public function my_up()	{		$this->check_up('转入虚拟币');		if (!session('home_finan_coinin')) {			ajax('非法访问');		}		$token = iv("post.token", 'dw', $this->ajax_lang('令牌错误'));		if (!$token || $token != session('home_finan_coinin')) {			ajax($this->ajax_lang('非法访问'));		}		$num = iv("post.num", 'xnb', $this->ajax_lang('转入数量格式错误'));		$addr = iv("post.addr", 'dw', $this->ajax_lang('钱包地址格式错误'));		$coin = iv("post.coin", 'w', $this->ajax_lang('转入币种参数错误'));		if (!session("userid")) {			ajax($this->ajax_lang("请先登录"));		}		$mymc_coin = new \mymc\coin();		$coin_info = $mymc_coin->find($coin);		if (!$coin_info) {			ajax($this->ajax_lang('币种错误'));		}		if ($coin_info['status'] != 1) {			ajax($this->ajax_lang('币种已经禁用'));		}		if ($coin_info['zr_jz'] != 1) {			ajax($this->ajax_lang('币种禁止转入'));		}		$res = db::table('coin_in')->add(['userid' => session("userid"), 'coinname' => $coin, 'zhuanchu' => $addr, 'type' => "站外", 'num' => $num, 'addtime' => time(), "status" => 0]);		if ($res) {			ajax($this->ajax_lang('订单创建成功'), 1);		} else {			ajax($this->ajax_lang('订单创建失败'));		}	}	public function qianbao_in()	{		$this->set_title('钱包转入地址管理');		$coinname = iv('get.coinname');		$this->assign('coinname', $coinname);		$mymc_coin = new \mymc\coin();		$coin_list_tmp = $mymc_coin->all_xnb();		$coin_list = [];		if ($coin_list_tmp) {			foreach ($coin_list_tmp as $k => $v) {				if ($v['wallet'] == 'newcoin') {					$coin_list[$v['name']] = $v;				}			}		}		$where['userid'] = $this->userid;		$where['type'] = 1;		if ($coinname) {			$where['coinname'] = $coinname;		}		$count = db::table("user_qianbao")->where($where)->count();		$PageObj = new page($count, 15);		$show = $PageObj->show();		$list = db::table("user_qianbao")->where($where)->order("`id` desc")->limit($PageObj->firstRow, $PageObj->listRows)->select();		$pages = ['show' => $show, 'list' => $list];		$this->assign('pages', $pages);		$this->assign('coin_list', $coin_list);		$this->display();	}	public function coinout()	{		$this->set_title('转出虚拟币');		$coin = iv("get.coin", 'w', $this->ajax_lang('参数错误'), true);		if (!$coin) {			$coin = md('sys_config')->get('mr_coin');		}		$mymc_coin = new \mymc\coin();		$coin_list_tmp = $mymc_coin->all_xnb(1);		if ($coin_list_tmp) {			foreach ($coin_list_tmp as $k => $v) {				$coin_list[$v['name']] = $v;			}		} else {			$coin_list = [];		}		if ($this->userid) {			$where['userid'] = $this->userid;			$where['coinname'] = $coin;			$user_qianbao = db::table('user_qianbao')->where($where)->select();		} else {			$user_qianbao = [];		}		if (isset($this->user['moble']) && $this->user['moble']) {			$this->user['moble'] = substr_replace(str_replace("|", " ", $this->user['moble']), '****', 7, 4);		}		$goole = 0;		if (isset($this->user['google']) && $this->user['google']) {			$arr = explode('|', $this->user['google']);			if ($arr[2]) {				$goole = 1;			} else {				$goole = 0;			}		}		if ($this->userid) {			$user_wallet = db::table("user_wallet")->where(["userid" => $this->userid])->find();			if (!$user_wallet) {				if (!db::table('user_wallet')->add(['userid' => $this->userid])) {					show_msg($this->ajax_lang('钱包地址不存在'));				}				$user_wallet = db::table("user_wallet")->where(["userid" => $this->userid])->find();			}		} else {			$user_wallet = [];		}		$mymc_coin = new \mymc\coin();		$coin_info = $mymc_coin->find($coin);		if (!$coin_info) {			show_msg($this->ajax_lang("币种不存在"));		}		$sys_sms = db::table('sys_sms')->where(['status' => 1])->find();		if (!$sys_sms) {			$sys_sms['sms'] = 0;			$sys_sms['tel'] = 0;		}		$this->assign('goole', $goole);		$this->assign('user_wallet', $user_wallet);		$this->assign('user_qianbao', $user_qianbao);		$this->assign('coin_list', $coin_list);		$this->assign('coin', $coin);		$this->assign('sys_sms', $sys_sms);		$this->display();	}	public function coinout_up()	{		$this->check_up('转出虚拟币');		$coinname = iv("post.coin", 'w', $this->ajax_lang('币种格式错误'));		$num = iv("post.num", 'xnb', $this->ajax_lang('转出数量格式错误'));		$moble_verify = iv("post.moble_verify", 'd', $this->ajax_lang('验证码格式错误'));		$type = iv("post.bank", 'd', $this->ajax_lang('提现地址格式错误'));		$goole = iv("post.goole");		$paypassword = iv("post.paypassword", 'password', $this->ajax_lang('交易密码格式错误'));		if (!$moble_verify || $moble_verify != session('moble_verify_coinout')) {			ajax($this->ajax_lang('验证码错误'));		}		if (!$goole) {			$goole = null;		}		$res = md("finan")->coin_out_up($this->userid, $coinname, $num, $type, $goole, $paypassword);		if (isset($res[1])) {			session('moble_verify_coinout', null);			ajax($this->ajax_lang($res[0]), 1);		} else {			ajax($this->ajax_lang($res[0]));		}	}	public function email_coinout_up()	{		$this->check_up('转出虚拟币');		$coinname = iv("post.coin", 'w', $this->ajax_lang('币种格式错误'));		$num = iv("post.num", 'xnb', $this->ajax_lang('转出数量格式错误'));		$moble_verify = iv("post.moble_verify", 'd', $this->ajax_lang('验证码格式错误'));		$type = iv("post.bank", 'd', $this->ajax_lang('提现地址格式错误'));		$goole = iv("post.goole");		$paypassword = iv("post.paypassword", 'password', $this->ajax_lang('交易密码格式错误'));		if (!$moble_verify || $moble_verify != session('moble_verify_coinout')) {			ajax($this->ajax_lang('验证码错误'));		}		if (!$goole) {			$goole = null;		}		$res = md("finan")->coin_out_up($this->userid, $coinname, $num, $type, $goole, $paypassword);		if (isset($res[1])) {			session('moble_verify_coinout', null);			ajax($this->ajax_lang($res[0]), 1);		} else {			ajax($this->ajax_lang($res[0]));		}	}	public function coinoutlog()	{		$this->set_title('转出虚拟币记录');		$coinname = iv('get.coinname');		$mymc_coin = new \mymc\coin();		$coin_list_tmp = $mymc_coin->all_xnb(1);		if ($coin_list_tmp) {			foreach ($coin_list_tmp as $k => $v) {				$coin_list[$v['name']] = $v;			}		} else {			$coin_list = [];		}		$where ['userid'] = $this->userid;		if ($coinname) {			$where ['coinname'] = $coinname;		}		$count = db::table('coin_out')->where($where)->count();		$page_obj = new page($count, 15);		$show = $page_obj->show();		$list = db::table('coin_out')->where($where)->order('`sort` asc ,id desc')->limit($page_obj->firstRow, $page_obj->listRows)->select();		$pages = ["list" => $list, "show" => $show];		$this->assign('pages', $pages);		$this->assign('coin_list', $coin_list);		$this->assign('coinname', $coinname);		$this->display();	}	public function qianbao()	{		$this->set_title('钱包地址管理');		$coinname = iv('get.coinname');		$this->assign('coinname', $coinname);		$mymc_coin = new \mymc\coin();		$coin_list_tmp = $mymc_coin->all_xnb();		$coin_list = [];		if ($coin_list_tmp) {			foreach ($coin_list_tmp as $k => $v) {				$coin_list[$v['name']] = $v;			}		}		$where['userid'] = $this->userid;		if ($coinname) {			$where['coinname'] = $coinname;		}		$count = db::table("user_qianbao")->where($where)->count();		$PageObj = new page($count, 15);		$show = $PageObj->show();		$list = db::table("user_qianbao")->where($where)->order("`id` desc")->limit($PageObj->firstRow, $PageObj->listRows)->select();		$pages = ['show' => $show, 'list' => $list];		$this->assign('pages', $pages);		$this->assign('coin_list', $coin_list);		$this->display();	}	public function qianbao_up()	{		$this->check_up('添加钱包地址');		$name = iv('post.name', 'a', $this->ajax_lang('钱包标识格式错误'));		$coinname = iv('post.coinname', 'w', $this->ajax_lang('币种格式错误'));		$addr = iv('post.addr');		$memo = iv('post.memo', 'a', $this->ajax_lang('钱包备注格式错误'), true);		$type = iv('post.type', 'd', $this->ajax_lang('钱包类型错误'), 1);		$paypassword = iv('post.paypassword', 'password', $this->ajax_lang('交易密码格式错误'));		$res = md('user')->add_qianbao($this->userid, $name, $coinname, $addr, $paypassword, $memo, $type);		if (isset($res[1]) && $res[1]) {			ajax($this->ajax_lang($res[0]), 1, '请转入' . $res[2] . '个币认证该地址');		} else {			ajax($this->ajax_lang($res[0]));		}	}	public function qianbao_del()	{		$this->check_up('删除钱包地址');		$id = iv('post.id', 'd', $this->ajax_lang('参数错误'));		$paypassword = iv('post.paypassword', 'password', $this->ajax_lang('交易密码格式错误'));		$res = md('user')->del_qianbao($this->userid, $id, $paypassword);		if (isset($res[1])) {			ajax($this->ajax_lang($res[0]), 1);		} else {			ajax($this->ajax_lang($res[0]));		}	}	public function trades()	{		$this->set_title('委托管理');		$markets = iv('get.markets', 'w_', $this->ajax_lang('市场错误'), true);		$type = iv('get.type', 'd', $this->ajax_lang('类型错误'), true);		$status = iv('get.status', 'd', $this->ajax_lang('状态错误'), true);		$where['userid'] = $this->userid;		if ($markets) {			$where['market'] = $markets;		}		if ($type > 0) {			$where['type'] = $type;		}		if ($status > 0) {			$where['status'] = $status - 1;		}		$pages = md('trade')->page_log('trade', $where);		$this->assign('pages', $pages);		$this->assign('type', $type);		$this->assign('markets', $markets);		$this->assign('status', $status);		$this->display();	}	public function order()	{		$this->set_title('成交记录');		$market = iv('get.market', 'w_', $this->ajax_lang('市场错误'), true);		$type = iv('get.type', 'd', $this->ajax_lang('类型错误'), true);		$status = iv('get.status', 'd', $this->ajax_lang('状态错误'), true);		if ($market) {			$where['market'] = $market;		}		if ($type == 1) {			$where['userid'] = $this->userid;		} elseif ($type == 2) {			$where['peerid'] = $this->userid;		} else {			$where['userid|peerid'] = $this->userid;		}		if ($type > 0) {			$where['type'] = $type;		}		$pages = md('trade')->page_log('trade_log', $where);		$this->assign('pages', $pages);		$this->assign('status', $status);		$this->assign('market', $market);		$this->assign('type', $type);		$this->display();	}	public function invit()	{		$this->set_title('推荐用户');		$web_invit_text = md('sys_config')->get('web_invit_text');		if ((!isset($this->user['invit']) || !$this->user['invit']) && $this->userid) {			$tradeno = md('user')->tradeno();			db::table('user')->where(['id' => $this->user['id']])->save(['invit' => $tradeno]);			$this->user['invit'] = $tradeno;		}		$this->assign('web_invit_text', $web_invit_text);		$this->display();	}	public function invitlog()	{		$this->set_title('我的推荐');		if ($this->userid) {			$where['invit_1'] = $this->userid;;			$count = db::table('user')->where($where)->count();			$page_obj = new page($count, 10);			$show = $page_obj->show();			$list = db::table('user')->where($where)->order('`sort` asc ,id desc')->limit($page_obj->firstRow, $page_obj->listRows)->select();			foreach ($list as $k => $v) {				$list[$k]['invits'] = md('user')->invit(['invit_1' => $v['id']], 'id asc', 'id,username,moble,addtime,invit_1,idcard_rz');				$list[$k]['invitss'] = count($list[$k]['invits']);				foreach ($list[$k]['invits'] as $kk => $vv) {					$list[$k]['invits'][$kk]['invits'] = md('user')->invit(['invit_1' => $vv['id']], 'id asc', 'id,username,moble,addtime,invit_1,idcard_rz');					$list[$k]['invits'][$kk]['invitss'] = count($list[$k]['invits'][$kk]['invits']);				}			}		} else {			$list = [];			$show = [];		}		$this->pages = ["list" => $list, "show" => $show];		$this->display();	}	public function award()	{		$this->set_title('推荐奖励');		if ($this->userid) {			$where = ["userid" => $this->userid];			$count = db::table("user_invit")->where($where)->count();			$PageObj = new page($count, 15);			$show = $PageObj->show();			$list = db::table("user_invit")->where($where)->order("`id` desc")->limit($PageObj->firstRow, $PageObj->listRows)->select();			foreach ($list as $k => $v) {				if ($v['invit'] != 0) {					$list[$k]['invitname'] = db::table("user")->where(["id" => $v["invit"]])->find("username");				} else {					$list[$k]['invitname'] = $this->ajax_lang('系统');				}			}		} else {			$list = [];			$show = [];		}		$this->pages = ["list" => $list, "show" => $show];		$this->display();	}} 