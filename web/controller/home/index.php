<?phpnamespace home;use Move\db;use Move\ext\auths;class index extends home{	public function __construct()	{		parent::__construct();	}	public function index()	{		$this->set_title('前台首页');		if (isset($_GET['invit']) && $_GET['invit']) {			if (check($_GET['invit'], 'dw')) {				session("invit", iv('get.invit'));				if (!session("userid")) {					if (isset($this->config['invit_ym']) && $this->config['invit_ym'] == 1) {						redirect('/reg');					}				}			}		}		$template_file = APP_PATH . '/backup/template.ini';		$template = '';		if (@file_exists($template_file)) {			$template = @file_get_contents($template_file);		}		if (isset($_GET['template'])) {			$template = $_GET['template'];		}		$sys_ad = mc('index_sys_ad');		if (!$sys_ad) {			$sys_ad = db::table('sys_ad')->where(['status' => 1, 'type' => 'index'])->order('sort asc ,id asc')->select();			mc('index_sys_ad', $sys_ad);		}		$this->assign('banner', $sys_ad);		$trade_qu = mc('index_trade_qu');		if (!$trade_qu) {			$trade_qu = db::table('trade_qu')->where(['status' => 1])->order('sort asc ,id asc')->select();			mc('index_trade_qu', $trade_qu);		}		$this->assign('trade_qu', $trade_qu);		$news_type_list = mc('index_index_news_type_list');		if (!$news_type_list) {			$news_type_list = db::table('article_type')->where(['type' => 1, "status" => 1])->limit(3)->order("`sort` ASC ,`id` desc")->select();			if ($news_type_list) {				foreach ($news_type_list as $k => $v) {					$news_type_list[$k]['list'] = db::table('article')->where(['type_id' => $v['id'], "status" => 1])->limit(7)->order("`sort` ASC ,`id` desc")->select();				}				mc('index_index_news_type_list', $news_type_list);			}		}		$this->assign('news_type_list', $news_type_list);		$sys_link = mc('index_sys_link');		if (!$sys_link) {			$sys_link = db::table('sys_link')->where(['status' => 1])->order('sort asc ,id asc')->select();			if ($sys_link) {				mc('index_sys_link', $sys_link);			}		}		$this->assign('sys_link', $sys_link);		$mm = mm();		if (!$mm) {		}		if (isset($mm['game']) && in_array("luck", explode("|", $mm["game"]))) {			$luck = 1;		} else {			$luck = 0;		}		$this->assign('luck', $luck);		$mm = mm();		if (!$mm) {			$this->show_run('授权获取失败', '', 1);		}		if (isset($mm['game']) && in_array("luck", explode("|", $mm["game"]))) {			$luck = 1;		} else {			$luck = 0;		}		$this->assign('luck', $luck);		$trade_market_list = db::table('trade_market')->where(['status' => 1])->order('sort asc,id desc')->select();				     		$this->assign('market1', $trade_market_list);		if (!$template || $template == "default" || $template == "blockorient") {			$this->default_index();		}		if ($template == "jubi") {			$this->jubi_index();		}		if ($template == "yuanbao") {			$this->yuanbao_index();		}		if ($template == "zhong") {			$this->zhong_index();		}		if ($template == "bitbank") {			$this->bitbank_index();		}		if ($template == "move_com") {			$this->movecom_index();		}		if ($template == "dinzhi") {			$this->dinzhi_index();		}		if ($template == "bichuang") {			$this->bichuang_index();		}		if ($template == "chbtc") {			$this->chbtc_index();		}		if ($template == "delphi") {			$this->delphi_index();		}		if ($template == "firecoin") {			$this->firecoin_index();		}		if ($template == "exx") {			$this->exx_index();		}		if ($template == "movenet") {			$this->movenet_index();		}		if ($template == "press") {			$this->press_index();		}		if ($template == "pro") {			$this->pro_index();		}		$this->display();	}	public function press_index()	{	}	public function default_index()	{		$zong_num = mc("index_zong_num");		if (!$zong_num) {			$zong_num = db::table('trade_log')->sum('mum');			mc("index_zong_num", $zong_num, 60);		}		$zong_num_uc = '<em class="red">0000</em>万';		$zong_num_login = '0000万';		if ($zong_num > 10000) {			$zong_num_uc = '<em class="red">' . round($zong_num / 10000, 2) . '</em>万';			$zong_num_login = round($zong_num / 10000) . '万';		}		if ($zong_num > 100000000) {			$zong_num_uc = '<em class="red">' . round($zong_num / 100000000, 2) . '</em>亿';			$zong_num_login = round($zong_num / 100000000) . '亿';		}		$this->assign('zong_num', $zong_num);		$this->assign('zong_num_uc', $zong_num_uc);		$this->assign('zong_num_login', $zong_num_login);		$links = mc("index_links");		if (!$links) {			$links = md('links')->index_lists();			mc("index_links", $links, 600);		}		$this->assign('links', $links);		$lang = cookie("lang");		$article_arr = mc(md5('index_default_index' . $lang));  		     		if (!$article_arr) {			$article_arr = db::table("article_type")->where(['type' => 1, 'sy' => 1, 'status' => 1])->order("sort asc ,id desc ,addtime desc")->limit(3)->select();			if ($article_arr) {				foreach ($article_arr as $k => $v) {					if (cookie("lang")) {						$lang_where = ["type_id" => $v['id'], 'lang' => $lang, 'status' => 1];					} else {						$lang_where = ["type_id" => $v['id'], 'status' => 1];					}					$article_arr[$k]['list'] = db::table("article")->where($lang_where)->order("sort asc,id desc ,addtime desc")->limit(10)->select();					if ($article_arr[$k]['list']) {						foreach ($article_arr[$k]['list'] as $kk => $vv) {							$pattern = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";							preg_match($pattern, $vv['value'], $match);							if ($match) {								$article_arr[$k]['list'][$kk]['img'] = $match[1];							} else {								$article_arr[$k]['list'][$kk]['img'] = '/static/default/home/img/noimg.png';							}							$article_arr[$k]['list'][$kk]["value"] = $this->cutstr_html($vv["value"]);						}					}				}				mc(md5('index_default_index' . $lang), $article_arr);			}		}		$this->assign('article_arr', $article_arr);		$data = mc("index_taa_data");		if (!$data) {			$data = db::table("trade_log")->limit(30)->order("id desc")->select();			mc("index_taa_data", $data, 60);		}		krsort($data);		$flot_json_price = [];		$flot_json_num = [];		$i = 0;		foreach ($data as $val) {			$flot_json_price[] = [$i++, $val["price"]];			$flot_json_num[] = [$i++, $val["num"]];		}		$flotjson_price = json_encode($flot_json_price);		$this->assign('flotjson_price', $flotjson_price);		$flotjson_num = json_encode($flot_json_num);		$this->assign('flotjson_num', $flotjson_num);		$app_issue = mc("index_app_issue");		if (!$app_issue) {			$app_issue = db::table("issue")->where(['status' => 1])->order("sort asc,id desc")->find();			mc("index_app_issue", $app_issue, 60);		}		if ($app_issue) {			$app_issue["fen"] = num($app_issue["deal"] / $app_issue["num"] * 100);			$app_issue["show_deal"] = $app_issue["deal"];			$app_issue["deal"] = num($app_issue["deal"] / $app_issue["num"] * 100);			$app_issue["deadline"] = time() - $app_issue["endtime"] ? round((time() - $app_issue["endtime"]) / 86400) : 0;		} else {			$app_issue["fen"] = 0;			$app_issue["show_deal"] = 0;			$app_issue["deal"] = 0;			$app_issue["deadline"] = 0;		}		if (isset($app_issue['price']) && $app_issue['price'] && isset($app_issue["coinname"])) {			$app_issue['price'] = num($app_issue['price'], $this->coin_list[$app_issue["coinname"]]['round']);		}		$this->assign('app_issue', $app_issue);		$mm = mm();		if (in_array('money', explode("|", $mm['game']))) {			$app_money = mc('index_app_money');			if (!$app_money) {				$app_money = db::table("money")->where(['status' => 1])->order("sort asc,id desc")->find();				mc('index_app_money', $app_money);			}			if ($app_money && $app_money["num"]) {				$app_money["fen"] = num($app_money["deal"] / $app_money["num"] * 100);			} else {				$app_money["fen"] = 0;			}		} else {			$app_money = [];		}		$this->assign('app_money', $app_money);		$trade_market = mc('index_trade_market_index');		if (!$trade_market) {			$trade_market = db::table("trade_market")->where(['status' => 1])->order("sort asc")->find();			mc('index_trade_market_index', $trade_market);		}		$this->assign('app_trade_market', $trade_market);	}	public function jubi_index()	{		$zong_num = mc("index_zong_num");		if (!$zong_num) {			$zong_num = db::table('trade_log')->sum('mum');			mc("index_zong_num", $zong_num, 60);		}		$zong_num_uc = '<em class="red">0000</em>万';		$zong_num_login = '0000万';		if ($zong_num > 10000) {			$zong_num_uc = '<em class="red">' . round($zong_num / 10000, 2) . '</em>万';			$zong_num_login = round($zong_num / 10000) . '万';		}		if ($zong_num > 100000000) {			$this->zong_num_uc = '<em class="red">' . round($zong_num / 100000000, 2) . '</em>亿';			$this->zong_num_login = round($zong_num / 100000000) . '亿';		}		$this->assign('zong_num', $zong_num);		$this->assign('zong_num_uc', $zong_num_uc);		$this->assign('zong_num_login', $zong_num_login);		$links = mc("index_links");		if (!$links) {			$links = md('links')->index_lists();			mc("index_links", $links, 600);		}		$this->assign('links', $links);		$article_type_list = db::table('article_type')->where(['type' => 1, "status" => 1])->field('id,title')->limit(3)->order("`sort` ASC ,`id` desc")->select();		if ($article_type_list) {			foreach ($article_type_list as $k => $v) {				$article_type_list[$k]['list'] = db::table('article')->where(['type_id' => $v['id'], "status" => 1])->field('id,title,addtime')->limit(7)->order("`sort` ASC ,`id` desc")->select();			}		}		$this->assign('index_article', $article_type_list);	}	public function yuanbao_index()	{		$zong_num = mc("index_zong_num");		if (!$zong_num) {			$zong_num = db::table('trade_log')->sum('mum');			mc("index_zong_num", $zong_num, 60);		}		$zong_num_uc = '<em class="red">0000</em>万';		$zong_num_login = '0000万';		if ($zong_num > 10000) {			$zong_num_uc = '<em class="red">' . round($zong_num / 10000, 2) . '</em>万';			$zong_num_login = round($zong_num / 10000) . '万';		}		if ($zong_num > 100000000) {			$zong_num_uc = '<em class="red">' . round($zong_num / 100000000, 2) . '</em>亿';			$zong_num_login = round($zong_num / 100000000) . '亿';		}		$this->assign('zong_num', $zong_num);		$this->assign('zong_num_uc', $zong_num_uc);		$this->assign('zong_num_login', $zong_num_login);		$trade_qu = db::table('trade_qu')->where(['status' => 1])->order('sort asc ,id asc')->select();		$this->assign('trade_qu', $trade_qu);		$article_type_list = db::table('article_type')->where(['type' => 1, "status" => 1])->field('id,title')->limit(3)->order("`sort` ASC ,`id` desc")->select();		if ($article_type_list) {			foreach ($article_type_list as $k => $v) {				$article_type_list[$k]['list'] = db::table('article')->where(['type_id' => $v['id'], "status" => 1])->field('id,title,addtime')->limit(7)->order("`sort` ASC ,`id` desc")->select();			}		}		$this->assign('index_article', $article_type_list);		$links = db::table("sys_link")->where(["status" => 1])->order("sort asc")->limit(20)->select();		$this->assign('links', $links);	}	public function zhong_index()	{		$links = mc("index_links");		if (!$links) {			$links = md('links')->index_lists();			mc("index_links", $links, 600);		}		$this->assign('links', $links);	}	public function bitbank_index()	{		$zong_num = mc("index_zong_num");		if (!$zong_num) {			$zong_num = db::table('trade_log')->sum('mum');			mc("index_zong_num", $zong_num, 60);		}		$this->zong_num = round($zong_num);		$str = (string)$this->zong_num;		for ($i = 0; $i < strlen($str); $i++) {			$zongArr[$i] = substr($str, $i, 1);		}		$this->zongArr = $zongArr;		$trade_qu = db::table('trade_qu')->where(['status' => 1])->order('sort asc ,id asc')->select();		$this->assign('trade_qu', $trade_qu);		$article_type_list = db::table('article_type')->where(['type' => 1, "status" => 1])->field('id,title')->limit(3)->order("`sort` ASC ,`id` desc")->select();		if ($article_type_list) {			foreach ($article_type_list as $k => $v) {				$article_type_list[$k]['list'] = db::table('article')->where(['type_id' => $v['id'], "status" => 1])->field('id,title,addtime')->limit(7)->order("`sort` ASC ,`id` desc")->select();			}		}		$this->index_article = $article_type_list;		$this->links = db::table("sys_link")->where(["status" => 1])->order("sort asc")->limit(20)->select();	}	public function movecom_index()	{		$type_arr = db::table("article_type")->where(["status" => 1, 'type' => 1])->order("sort asc,id desc ,addtime desc")->select();		if ($type_arr) {			foreach ($type_arr as $k => $v) {				$type_arr[$k]['list_data'] = db::table("article")->where(["status" => 1, 'type_id' => $v['id']])->order("sort asc ,id desc ,addtime desc")->limit(11)->select();			}		}		$this->assign('type_arr', $type_arr);	}	public function movenet_index()	{		$article_list = db::table("article")->where(["status" => 1])->order("sort asc ,id desc ,addtime desc")->limit(100)->select();		$this->assign('article_list', $article_list);		$type_arr = db::table("article_type")->where(["status" => 1, 'type' => 1])->order("sort asc,id desc ,addtime desc")->limit(4)->select();		if ($type_arr) {			foreach ($type_arr as $k => $v) {				$type_arr[$k]['list_data'] = db::table("article")->where(["status" => 1, 'type_id' => $v['id']])->order("sort asc ,id desc ,addtime desc")->limit(5)->select();				if ($type_arr[$k]['list_data']) {					foreach ($type_arr[$k]['list_data'] as $kk => $vv) {						$pattern = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";						preg_match($pattern, $vv['value'], $match);						if ($match) {							$type_arr[$k]['list_data'][$kk]['img'] = $match[1];						} else {							$type_arr[$k]['list_data'][$kk]['img'] = '/static/default/home/img/noimg.png';						}					}				}			}		}		$this->assign('type_arr', $type_arr);	}	public function dinzhi_index()	{		$this->user_count = db::table("user")->count();		if ($this->user_count > 10000) {			$this->user_count = round($this->user_count / 10000) . '万';		}		if ($this->user_count > 100000000) {			$this->user_count = round($this->user_count / 100000000) . '亿';		}		$zong_num = mc("index_zong_num");		$btc_num = mc("index_btc_num");		if (!$zong_num) {			$zong_num = db::table('trade_log')->sum('mum');			mc("index_zong_num", $zong_num, 60);		}		if (!$btc_num) {			$zhiding_qudb = db::table('trade_qu')->where(["id" => 2])->find();			$btc_num = 0;			if ($zhiding_qudb && $zhiding_qudb['data']) {				foreach (explode(",", $zhiding_qudb['data']) as $k => $v) {					if ($v) {						$market = db::table('trade_market')->where(["id" => $v])->find();						$btc_num = $btc_num + db::table('trade_log')->where(["market" => $market['name']])->sum('num');					}				}			}			mc("index_btc_num", $btc_num, 60);		}		$this->zong_num = $zong_num;		$this->zong_num_uc = '<em class="red">0000</em>万';		$this->zong_num_login = '0000万';		if ($this->zong_num > 10000) {			$this->zong_num_uc = '<em class="red">' . round($this->zong_num / 10000, 2) . '</em>万';			$this->zong_num_login = round($this->zong_num / 10000) . '万';		}		if ($this->zong_num > 100000000) {			$this->zong_num_uc = round($this->zong_num / 100000000, 2) . '亿';			$this->zong_num_login = round($this->zong_num / 100000000) . '亿';		}		$this->btc_num = $btc_num;		$this->btc_num_uc = '<em class="red">0</em>';		$this->btc_num_login = '0000万';		if ($this->btc_num > 0) {			$this->btc_num_uc = '<em class="red">' . round($this->btc_num, 2) . '</em>';			$this->btc_num_login = round($this->btc_num);		}		if ($this->btc_num > 10000) {			$this->btc_num_uc = '<em class="red">' . round($this->btc_num / 10000, 2) . '</em>万';			$this->btc_num_login = round($this->btc_num / 10000) . '万';		}		if ($this->btc_num > 100000000) {			$this->btc_num_uc = round($this->btc_num / 100000000, 2) . '亿';			$this->btc_num_login = round($this->btc_num / 100000000) . '亿';		}		$links = mc("index_links");		if (!$links) {			$links = md('links')->index_lists();			mc("index_links", $links, 600);		}		$this->links = $links;		$lang = cookie("lang");		$article_arr = db::table("article_type")->where(['type' => 1, 'sy' => 1, 'status' => 1])->order("sort asc ,id desc ,addtime desc")->limit(3)->select();		if ($article_arr) {			foreach ($article_arr as $k => $v) {				if (cookie("lang")) {					$lang_where = ["type_id" => $v['id'], 'lang' => $lang, 'status' => 1];				} else {					$lang_where = ["type_id" => $v['id'], 'status' => 1];				}				$article_arr[$k]['list'] = db::table("article")->where($lang_where)->order("sort asc,id desc ,addtime desc")->limit(10)->select();				if ($article_arr[$k]['list']) {					foreach ($article_arr[$k]['list'] as $kk => $vv) {						$pattern = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";						preg_match($pattern, $vv['value'], $match);						if ($match) {							$article_arr[$k]['list'][$kk]['img'] = $match[1];						} else {							$article_arr[$k]['list'][$kk]['img'] = '/static/default/home/img/noimg.png';						}					}				}			}		}		$this->assign('article_arr', $article_arr);		$data = mc("index_taa_data");		if (!$data) {			$data = db::table("trade_log")->limit(30)->order("id desc")->select();			mc("index_taa_data", $data, 60);		}		krsort($data);		$flot_json_price = [];		$flot_json_num = [];		$i = 0;		foreach ($data as $val) {			$flot_json_price[] = [$i++, $val["price"]];			$flot_json_num[] = [$i++, $val["num"]];		}		$this->flotjson_price = json_encode($flot_json_price);		$this->flotjson_num = json_encode($flot_json_num);		$trade_market = db::table("trade_market")->where(['status' => 1])->order("sort asc")->find();		$this->app_trade_market = $trade_market;		$new_lucky = db::table("duobao")->where(["status" => 3])->order("kaitime desc")->limit(5)->select();		$lucky = [];		$i = 0;		foreach ($new_lucky as $k => $v) {			$duobao_log = db::table("duobao_log")->where(["id" => $v["logid"]])->find();			$username = db::table("user")->where(["id" => $duobao_log["userid"]])->find();			$lucky[$i]["username"] = substr_replace(str_replace("|", " ", $username['username']), '**', 3, 2);			$lucky[$i]["hao"] = $v["jianghao"];			$lucky[$i]["duobaoid"] = $v["id"];			$lucky[$i++]["title"] = $v["title"];		}		$this->lucky = $lucky;		$article_type_list = db::table('article')->where(['type_id' => 1])->field('id,title,addtime,value')->limit(3)->order("`sort` ASC ,`id` desc")->select();		foreach ($article_type_list as $kk => $vv) {			$article_type_list[$kk]["addtime"] = addtime($vv["addtime"], "m" . "月" . "d" . "日" . " " . "h:00");			$article_type_list[$kk]["value"] = $this->cutstr_html($vv["value"]);		}		$this->assign("article_type_list", $article_type_list);		$crowd = db::table("crowd")->where(["status#!=" => 0])->order("addtime desc")->find();		$crowd["bili"] = $crowd["deal"] / $crowd["num"] * 100;		$this->assign("crowd", $crowd);	}	public function bichuang_index()	{		$zong_num = mc("index_zong_num");		if (!$zong_num) {			$zong_num = db::table('trade_log')->sum('mum');			mc("index_zong_num", $zong_num, 60);		}		$this->zong_num = $zong_num;		$this->zong_num_uc = '<em class="red">0000</em>万';		$this->zong_num_login = '0000万';		if ($this->zong_num > 10000) {			$this->zong_num_uc = '<em class="red">' . round($this->zong_num / 10000, 2) . '</em>万';			$this->zong_num_login = round($this->zong_num / 10000) . '万';		}		if ($this->zong_num > 100000000) {			$this->zong_num_uc = '<em class="red">' . round($this->zong_num / 100000000, 2) . '</em>亿';			$this->zong_num_login = round($this->zong_num / 100000000) . '亿';		}		$links = mc("index_links");		if (!$links) {			$links = md('links')->index_lists();			mc("index_links", $links, 600);		}		$this->links = $links;		$bichuang_news = db::table('article_type')->where(['type' => 1, "status" => 1])->limit(3)->order("`sort` ASC ,`id` desc")->select();		if ($bichuang_news) {			foreach ($bichuang_news as $k => $v) {				$bichuang_news[$k]['list'] = db::table('article')->where(['type_id' => $v['id'], "status" => 1])->limit(7)->order("`sort` ASC ,`id` desc")->select();				foreach ($bichuang_news[$k]['list'] as $key => $value) {					$bichuang_news[$k]['list'][$key]["value"] = $this->cutstr_html($value["value"]);				}			}		}		$this->assign('bichuang_news', $bichuang_news);	}	public function chbtc_index()	{		$mymz_trade = new \mymc\trade();		$this->assign('menu', $mymz_trade->daohang());		$this->market = iv('get.market', 'w_', '', 1);		if (empty($this->market) || !$this->market_list[$this->market]) {			$this->market = $this->config['mr_market'];		}		if (!$this->market && $this->market_list) {			$this->market = reset($this->market_list)['name'];		}		if ($this->market) {			if (!$this->market_list[$this->market]['status']) {				ajax('交易市场禁止访问' . $this->market);			}		}		$mymz_trade = new \mymc\trade();		$this->assign('menu', $mymz_trade->daohang());	}	public function delphi_index()	{		$article_arr = db::table("article")->where(['type_id' => 1, 'status' => 1])->select();		$article_type = db::table("article_type")->where(['id' => 1])->find();		$this->assign('article_arr', $article_arr);		$this->assign('article_type', $article_type);	}	public function pro_index()	{		$zong_num = mc("index_zong_num");		if (!$zong_num) {			$zong_num = db::table('trade_log')->sum('mum');			mc("index_zong_num", $zong_num, 60);		}		$this->zong_num = $zong_num;		$this->zong_num_uc = '<em class="red">0000</em>万';		$this->zong_num_login = '0000万';		if ($this->zong_num > 10000) {			$this->zong_num_uc = '<em class="red">' . round($this->zong_num / 10000, 2) . '</em>万';			$this->zong_num_login = round($this->zong_num / 10000) . '万';		}		if ($this->zong_num > 100000000) {			$this->zong_num_uc = '<em class="red">' . round($this->zong_num / 100000000, 2) . '</em>亿';			$this->zong_num_login = round($this->zong_num / 100000000) . '亿';		}		$links = mc("index_links");		if (!$links) {			$links = md('links')->index_lists();			mc("index_links", $links, 600);		}		$this->links = $links;		$article_arr = db::table("article")->where(['type_id' => 1, 'status' => 1])->select();		$this->assign('article_arr', $article_arr);		$banner = db::table('sys_ad')->where(['type' => 'index', 'status' => 1])->select();		$this->assign('banner', $banner);	}	public function firecoin_index()	{		$zong_num = mc("index_zong_num");		if (!$zong_num) {			$zong_num = db::table('trade_log')->sum('mum');			mc("index_zong_num", $zong_num, 60);		}		$this->zong_num = $zong_num;		$this->zong_num_uc = '<em class="red">0000</em>万';		$this->zong_num_login = '0000万';		if ($this->zong_num > 10000) {			$this->zong_num_uc = '<em class="red">' . round($this->zong_num / 10000, 2) . '</em>万';			$this->zong_num_login = round($this->zong_num / 10000) . '万';		}		if ($this->zong_num > 100000000) {			$this->zong_num_uc = '<em class="red">' . round($this->zong_num / 100000000, 2) . '</em>亿';			$this->zong_num_login = round($this->zong_num / 100000000) . '亿';		}		$links = mc("index_links");		if (!$links) {			$links = md('links')->index_lists();			mc("index_links", $links, 600);		}		$this->links = $links;		$article_arr = db::table("article")->where(['type_id' => 1, 'status' => 1])->select();		$this->assign('article_arr', $article_arr);		$banner = db::table('sys_ad')->where(['type' => 'index', 'status' => 1])->select();		$this->assign('banner', $banner);	}	public function exx_index()	{		$zong_num = mc("index_zong_num");		if (!$zong_num) {			$zong_num = db::table('trade_log')->sum('mum');			mc("index_zong_num", $zong_num, 60);		}		$this->zong_num = $zong_num;		$this->zong_num_uc = '<em class="red">0000</em>万';		$this->zong_num_login = '0000万';		if ($this->zong_num > 10000) {			$this->zong_num_uc = '<em class="red">' . round($this->zong_num / 10000, 2) . '</em>万';			$this->zong_num_login = round($this->zong_num / 10000) . '万';		}		if ($this->zong_num > 100000000) {			$this->zong_num_uc = '<em class="red">' . round($this->zong_num / 100000000, 2) . '</em>亿';			$this->zong_num_login = round($this->zong_num / 100000000) . '亿';		}		$links = mc("index_links");		if (!$links) {			$links = md('links')->index_lists();			mc("index_links", $links, 600);		}		$this->links = $links;		$article_arr = db::table("article")->where(['type_id' => 1, 'status' => 1])->select();		$this->assign('article_arr', $article_arr);		$banner = db::table('sys_ad')->where(['type' => 'index', 'status' => 1])->select();		$this->assign('banner', $banner);	}	public function showmovesay()	{		dump(mm());		dump(murl());		die;	}	public function checkgame()	{		md('game')->check();	}	public function app()	{		$app = db::table("app")->where(["status" => 1])->select();		$this->assign("app", $app);		$this->display();	}	public function movesay()	{		$this->is_login();		$this->is_goole();		$this->display();	}	public function eyuyan()	{		$this->is_login();		$this->is_goole();		$this->display();	}	public function api()	{		$this->display();	}	public function waring()	{		$this->display();	}	public function Ourproduct()	{		$this->display();	}	public function newlist()	{		$this->display();	}	public function trypro()	{		$this->display();	}	public function aboutus()	{		$this->display();	}	public function prolist()	{		$this->display();	}	public function compaylist()	{		$this->display();	}	public function prodetail01()	{		$this->display();	}	public function prodetail02()	{		$this->display();	}	function cutstr_html($string, $length = 0, $ellipsis = '…')	{		$string = strip_tags($string);		$string = preg_replace('/\n/is', '', $string);		$string = preg_replace('/ |　/is', '', $string);		$string = preg_replace('/&nbsp;/is', '', $string);		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $string);		if (is_array($string) && !empty($string[0])) {			if (is_numeric($length) && $length) {				$string = join('', array_slice($string[0], 0, $length)) . $ellipsis;			} else {				$string = implode('', $string[0]);			}		} else {			$string = '';		}		return $string;	}	public function admin_menu()	{		return true;		$sys_conf = ['index', 'config', 'article', 'user', 'auth', 'finan', 'tools', 'cloud'];		$mm = mm();		$game = explode("|", $mm["game"]);		$my_menu = array_merge($game, $sys_conf);		$sys_menu = db::table('sys_menu')->field('id,url')->select();		foreach ($sys_menu as $vv) {			$con = strstr($vv['url'], '/', TRUE);			if (in_array($con, $my_menu)) {			} else {				db::table('sys_menu')->where(['id' => $vv['id']])->delete();			}		}		echo '操作成功';	}} 