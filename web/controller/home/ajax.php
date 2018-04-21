<?phpnamespace home;use Move\db;use Move\mvc\controller;class ajax extends controller{	public function __construct()	{		parent::__construct();	}	public function verify()	{		verify();	}	public function upload()	{		defined('APP_DEMO') or define('APP_DEMO', 0);		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		if (!session("admin_id")) {			ajax("非法访问");		}		$config = getConf("upload");		if ($config['type'] == "oss") {			require_once APP_PATH . '/controller/ext/oss/upload.php';			\moveUpload::init($config['oss'], ["imageFieldName" => "upload_file",]);		} else {			md("upload")->init(["imageFieldName" => "upload_file", "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", "scrawlPathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", "snapscreenPathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", "catcherPathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", "videoPathFormat" => "/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}", "filePathFormat" => "/upload/file/{yyyy}{mm}{dd}/{time}{rand:6}", "imageManagerListPath" => "/upload/image/", "fileManagerListPath" => "/upload/file/",]);		}	}	public function imgUpload()	{		$ret_ajax = ["code" => -1, "msg" => "", "data" => ["src" => "", "title" => ""]];		$type = @$_GET['type'];		if (!in_array($type, ['img_bbs', 'doc'])) {			$ret_ajax['msg'] = "非法上传模块类型" . $type;			echo json_encode($ret_ajax);			exit();		}		$up = new \ext\upfile();		$base_path = "/upload/" . $type . "/";		$up->set("path", APP_PATH . $base_path);		$up->set("maxsize", 2000000);		$up->set("allowtype", ["gif", "png", "jpg", "jpeg"]);		$up->set("israndname", true);		if ($up->upload("file")) {			$ret_ajax['code'] = 0;			$ret_ajax['data']['src'] = "http://" . $_SERVER['HTTP_HOST'] . $base_path . $up->getFileName();			echo json_encode($ret_ajax);			exit();		} else {			$ret_ajax['msg'] = $up->getErrorMsg();			echo json_encode($ret_ajax);			exit();		}	}	public function imgUser()	{		$up = new \ext\upfile();		$up->set("path", APP_PATH . "/upload/user/");		$up->set("maxsize", 2000000);		$up->set("allowtype", ["png", "jpg", "jpeg"]);		$up->set("israndname", true);		if (!session('userid')) {			ajax('请先登录');		}		if (!isset($_FILES['img'])) {			ajax('请选择图片');		}		if ($up->upload("img")) {			if (db::table('user')->where(['id' => session('userid')])->set(['idcard_img' => "/user/" . $up->getFileName(), 'idcard_rz' => 2])) {				ajax('上传成功', 1);			}		}	}	public function sliderSave()	{		defined('APP_DEMO') or define('APP_DEMO', 0);		if (APP_DEMO) {			ajax('测试站暂时不能修改');		}		$data_idname = iv("post.data_idname", "wd_", "非法参数1");		$data_slider_height = iv("post.data_slider_height", "d", "非法参数2");		$data_slider_arrset = iv("post.data_slider_arrset", "d", "非法参数3");		$data_slider_navigator = iv("post.data_slider_navigator", "d", "非法参数4");		$data_slider_action = iv("post.data_slider_action", "a", "非法参数5");		$data_slider_action_data = iv("post.data_slider_action_data", "a", "非法参数5");		$data_img = iv("post.data_img", "a", "非法参数6");		$res_data = db::table('sys_ad')->where(["name" => $data_idname])->find();		$data_imgs = explode("|", $data_img);		$new_img_data = [];		$tmp_data = [];		if ($res_data) {			$res = json_decode($res_data["data"], true);			foreach ($data_imgs as $data_img) {				$sub_data_img = explode("#", $data_img);				if (is_numeric($sub_data_img["0"]) && isset($res["data"][$sub_data_img["0"]]["img"])) {					$tmp_data["img"] = $res["data"][$sub_data_img["0"]]["img"];				} else {					$tmp_data["img"] = $sub_data_img["0"];				}				$tmp_data["url"] = $sub_data_img["1"];				$tmp_data["alt"] = $sub_data_img["2"];				$tmp_data["title"] = $sub_data_img["2"];				$new_img_data[] = $tmp_data;			}		} else {			foreach ($data_imgs as $data_img) {				$sub_data_img = explode("#", $data_img);				$tmp_data["img"] = $sub_data_img["0"];				$tmp_data["url"] = $sub_data_img["1"];				$tmp_data["alt"] = $sub_data_img["2"];				$tmp_data["title"] = $sub_data_img["2"];				$new_img_data[] = $tmp_data;			}		}		$data = ["data" => $new_img_data, "set" => ["data_idname" => $data_idname, "data_slider_height" => $data_slider_height, "data_slider_arrset" => $data_slider_arrset, "data_slider_navigator" => $data_slider_navigator, "data_slider_action" => $data_slider_action, "data_slider_action_data" => $data_slider_action_data,]];		if ($res_data) {			$flag = db::table('sys_ad')->where(["id" => $res_data["id"]])->save(["data" => json_encode($data), "endtime" => time()]);		} else {			$flag = db::table('sys_ad')->add(["name" => $data_idname, "data" => json_encode($data), "status" => 1, "addtime" => time()]);		}		if ($flag) {			ajax("操作成功", 1);		} else {			ajax("操作失败");		}	}	public function array_sort($arr, $keys, $orderby = 'asc')	{		$keysvalue = $new_array = [];		foreach ($arr as $k => $v) {			$keysvalue[$k] = $v[$keys];		}		if ($orderby == 'asc') {			asort($keysvalue);		} else {			arsort($keysvalue);		}		reset($keysvalue);		foreach ($keysvalue as $k => $v) {			$new_array[] = $arr[$k];		}		return $new_array;	}	public function array_sort_field($array, $field, $desc = false)	{		$fieldArr = [];		foreach ($array as $k => $v) {			$fieldArr[$k] = $v[$field];		}		$sort = $desc == false ? SORT_ASC : SORT_DESC;		array_multisort($fieldArr, $sort, $array);		return $array;	}	public function allcoin_a()	{		$id = iv('get.id', 'd', 'can');		$keyword = iv('get.keyword', 'w', '', 1);		$trade_qu = mc('home_ajax_allcoin_a_trade_qu' . $id);		if (!$trade_qu) {			$trade_qu = db::table('trade_qu')->where(['id' => $id, 'status' => 1])->find();		}		if (!$trade_qu) {			ajax('大区不存在');		} else {			mc('home_ajax_allcoin_a_trade_qu' . $id, $trade_qu, 60);		}		$ids = explode(',', $trade_qu['data']);		$where = ['id#in' => $ids, 'status' => 1];		if ($keyword) {			$where['name#like'] = '%' . $keyword . '%';		}		$trade_market = mc('home_ajax_allcoin_a_trade_market' . md5(json_encode($where)));		if (!$trade_market) {			$trade_market = db::table('trade_market')->where($where)->order('sort asc')->select();			mc('home_ajax_allcoin_a_trade_market' . md5(json_encode($where)), $trade_market, 600);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'id_asc') {			$trade_market = $this->array_sort($trade_market, 'id', 'asc');		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'id_desc') {			$trade_market = $this->array_sort($trade_market, 'id', 'desc');		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'sort_asc') {			$trade_market = $this->array_sort($trade_market, 'sort', 'asc');		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'sort_desc') {			$trade_market = $this->array_sort($trade_market, 'sort', 'desc');		}		if (!$trade_market) {			ajax('没有交易市场');		}		$data = [];		$mymc_coin = new \mymc\coin();		foreach ($trade_market as $k => $v) {			$arr = explode("_", $v["name"]);			$coin = $mymc_coin->find($arr[0]);			$round = $coin["round"];			if ($round <= 0) {				$round = 2;			}			$data[$v['name']][0] = $v['title'];			$data[$v['name']][1] = num($v['new_price'], $round);			$data[$v['name']][2] = num($v['buy_price'], $round);			$data[$v['name']][3] = num($v['sell_price'], $round);			$data[$v['name']][4] = num($v['volume'] * $v['new_price'], 2);			$data[$v['name']][5] = $v['name'];			$data[$v['name']][6] = num($v['volume'], 2);			$data[$v['name']][7] = num($v['change'], 2);			$data[$v['name']][8] = $v['name'];			$data[$v['name']][9] = $v['img'];			$data[$v['name']][10] = '';		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'price_asc') {			$data = $this->array_sort_field($data, 1);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'price_desc') {			$data = $this->array_sort_field($data, 1, 1);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'buy_asc') {			$data = $this->array_sort_field($data, 2);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'buy_desc') {			$data = $this->array_sort_field($data, 2, 1);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'sell_asc') {			$data = $this->array_sort_field($data, 3);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'sell_desc') {			$data = $this->array_sort_field($data, 3, 1);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'num_asc') {			$data = $this->array_sort_field($data, 6);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'num_desc') {			$data = $this->array_sort_field($data, 6, 1);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'mum_asc') {			$data = $this->array_sort_field($data, 4);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'mum_desc') {			$data = $this->array_sort_field($data, 4, 1);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'zhangdie_asc') {			$data = $this->array_sort_field($data, 7);		}		if (isset($trade_qu['sort_rule']) && $trade_qu['sort_rule'] == 'zhangdie_desc') {			$data = $this->array_sort_field($data, 7, 1);		}		ajax('数据正常', 1, $data);	}	public function allcoin()	{		$data = [];		$mymz_trade = new \mymc\trade();		$res = $mymz_trade->market_select();		if ($res) {			$mymc_coin = new \mymc\coin();			foreach ($res as $k => $v) {				$arr = explode("_", $v["name"]);				$coin = $mymc_coin->find($arr[0]);				$round = $coin["round"];				if ($round <= 0) {					$round = 2;				}				$data[$v['name']][0] = $v['title'];				$data[$v['name']][1] = num($v['new_price'], $round);				$data[$v['name']][2] = num($v['buy_price'], $round);				$data[$v['name']][3] = num($v['sell_price'], $round);				$data[$v['name']][4] = num($v['volume'] * $v['new_price'], 2);				$data[$v['name']][5] = $v['name'];				$data[$v['name']][6] = num($v['volume'], 2);				$data[$v['name']][7] = num($v['change'], 2);				$data[$v['name']][8] = $v['name'];				$data[$v['name']][9] = $v['img'];				$data[$v['name']][10] = '';			}		}		exit(json_encode($data));	}	public function index_b_trends()	{		$data = [];		$res = mc('ajax_index_b_trends');		if (!$res) {			$res = md("trade")->market_list();			mc('ajax_index_b_trends', $res);		}		if ($res) {			foreach ($res as $k => $v) {				$data[$k]['data'] = json_decode($v['tendency'], true);				$data[$k]['yprice'] = $v['new_price'];			}		}		exit(json_encode($data));	}	public function index_b_allsum()	{		$data = num(db::table("trade_log")->sum("mum"));		echo json_encode($data);		return;	}	public function check_zzc($coin = null)	{		$mymc_coin = new \mymc\coin();		if (!$coin) {			$coin = db::table('coin')->where(['status' => 1, 'wallet' => 'rmb'])->order('sort asc,id desc')->find('name');		}		if (session('userid')) {			$all_xnb = $mymc_coin->all_xnb();			$user_coin = db::table('user_coin')->where(['userid' => session('userid')])->find();			$zjc = $user_coin[$coin] + $user_coin[$coin . 'd'];			$mymz_trade = new \mymc\trade();			$market_list = $mymz_trade->market_select();			foreach ($all_xnb as $k => $v) {				if ($v['name'] != 'cny') {					$price = isset($market_list[$v['name'] . '_' . $coin]['new_price']) ? $market_list[$v['name'] . '_' . $coin]['new_price'] : 1;					$all_xnb[$k]['dongjie'] = $user_coin[$v['name'] . 'd'];					$all_xnb[$k]['zongji'] = $user_coin[$v['name']] + $user_coin[$v['name'] . 'd'];					$all_xnb[$k]['zhehe'] = $all_xnb[$k]['zongji'] * $price;					$zjc = $zjc + $all_xnb[$k]['zhehe'];				}			}			echo $zjc;			return;		} else {			echo '****';			return;		}	}	public function get_market_data()	{		$market = iv('get.market', 'w_', '参数错误', 1);		if (!$market) {			$market = md('sys_config')->get('mr_market');		}		if ($market) {			$trade_market = db::table('trade_market')->where(['name' => $market])->order("sort asc,id asc ,addtime desc")->find();		} else {			$trade_market = db::table('trade_market')->where(['status' => 1])->order('id asc')->find();		}		ajax('数据正常', 1, $trade_market);	}	public function get_market_list_data()	{		$trade_market_list = db::table('trade_market')->where(['status' => 1])->order('sort asc,id desc')->select();		ajax('数据正常', 1, $trade_market_list);	}	public function allsum($ajax = 'json')	{		$data = MS_DEBUG ? null : mc('allsum');		if (!$data) {			$data = db::table('trade_log')->sum('mum');			mc('allsum', $data);		}		$data = round($data);		$data = str_repeat('0', 12 - strlen($data)) . (string)$data;		if ($ajax) {			exit(json_encode($data));		} else {			return $data;		}	}	public function trends($ajax = 'json')	{		$data = MS_DEBUG ? null : mc('trends');		if (!$data) {			foreach (C('market') as $k => $v) {				$tendency = json_decode($v['tendency'], true);				$data[$k]['data'] = $tendency;				$data[$k]['yprice'] = $v['new_price'];			}			mc('trends', $data);		}		if ($ajax) {			exit(json_encode($data));		} else {			return $data;		}	}	public function moveso()	{		$path = APP_PATH . "/download/move.so";		if (file_exists($path)) {			echo md5_file($path) . "|" . filemtime($path);			exit();		} else {			echo "模块不存在";			exit();		}	}	public function filedata()	{		if (!isset($_GET['authcode']) && empty($_GET['authcode'])) {			redirect("/");		}		if (MS_CODE != "95D3A7E98EE9F913B462B87C73DS" && MS_CODE != "FDD71185864300CC66AFA41EA255") {			exit("非DEMO 禁用");		}		config("local_file_data", []);		$encode_file = function ($path) use (&$encode_file) {			$_tmp_data = [];			$dir = opendir($path);			while (false !== ($file = readdir($dir))) {				if (($file != '.') && ($file != '..')) {					if (is_dir($path . '/' . $file)) {						if (strpos($file, ".") === 0) {							continue;						}						$_file = trim(str_replace(APP_PATH, "", $path . '/' . $file));						if (strpos($_file, "/runtime") === 0) {							continue;						}						if (strpos($_file, "/upload") === 0) {							continue;						}						if (strpos($_file, "/backup") === 0) {							continue;						}						if (strpos($_file, "/jiami") === 0) {							continue;						}						if (strpos($_file, "/moveso") === 0) {							continue;						}						$encode_file($path . '/' . $file);					} else {						$_file = $path . '/' . $file;						if (strpos($file, ".") === 0) {							continue;						}						$_file_hash = md5_file($_file);						$_file_name = trim(str_replace(APP_PATH, "", $_file));						$key = md5($_file_name);						if (strpos($_file_name, "/index.php") === 0) {							continue;						}						if (strpos($_file_name, "/dirdata.php") === 0) {							continue;						}						if (strpos($_file_name, "/开发文档.md") === 0) {							continue;						}						if (strpos($_file_name, "/test.php") === 0) {							continue;						}						if (strpos($_file_name, "/README.md") === 0) {							continue;						}						$characet = function ($data) {							if (!empty($data)) {								$fileType = mb_detect_encoding($data, ['UTF-8', 'GBK', 'LATIN1', 'BIG5']);								if ($fileType != 'UTF-8') {									$data = mb_convert_encoding($data, 'utf-8', $fileType);								}							}							return $data;						};						$_tmp_data[$key] = [$characet($_file_name), $_file_hash];					}				}			}			config("local_file_data", array_merge(config("local_file_data"), $_tmp_data));			closedir($dir);		};		$encode_file(APP_PATH);		$dir_data = config("local_file_data");		if (!$dir_data) {			exit("本地 文件 结构数据获取失败!");		}		echo json_encode($dir_data);		exit;	}	public function dbdata()	{		$type = isset($_GET['type']) ? $_GET['type'] : "";		if (!isset($_GET['authcode']) || empty($_GET['authcode'])) {			redirect("/");		}		if (MS_CODE != "95D3A7E98EE9F913B462B87C73DS" && MS_CODE != "FDD71185864300CC66AFA41EA255") {			exit("非DEMO 禁用");		}		$tables = [];		$data = db::query("show tables;");		foreach ($data as $v) {			if (!$_name = array_shift($v)) {				continue;			}			if (strpos($_name, "DEL_") === 0) {				continue;			}			$struct = db::query("show create table `" . $_name . "`;");			$struct = $struct["0"]["Create Table"];			$struct = preg_replace("/AUTO_INCREMENT=\d+/", "", $struct);			$columns = "";			$data = db::query("SHOW FULL COLUMNS FROM `" . $_name . "`");			foreach ($data as $val) {				$columns[$val['Field']] = trim($val['Type']);			}			if ($type == "type2") {				$tables[$_name] = ["struct" => base64_encode(trim($struct)), "columns" => base64_encode(json_encode($columns))];			} else {				$tables[$_name] = base64_encode(trim($struct));			}		}		echo json_encode($tables);		exit;	}	public function dinzhi_a()	{		$id = iv('get.id', 'd', 'can');		$trade_qu = db::table('trade_qu')->where(['id' => $id])->find();		if (!$trade_qu) {			ajax('大区不存在');		}		$ids = explode(',', $trade_qu['data']);		$trade_market = db::table('trade_market')->where(['id#in' => $ids])->select();		if (!$trade_market) {			ajax('没有交易市场');		}		$data = [];		$mymc_coin = new \mymc\coin();		foreach ($trade_market as $k => $v) {			$arr = explode("_", $v["name"]);			$coin = $mymc_coin->find($arr[0]);			$round = $coin['round'];			if ($round <= 0) {				$round = 2;			}			$data[$v['name']][0] = $v['title'];			$data[$v['name']][1] = num($v['new_price'], $round);			$coin = $mymc_coin->find(substr($v["name"], 0, 3));			if ($coin) {				$data[$v['name']][2] = num($coin["cs_zl"] * $v['new_price']);			} else {				$data[$v['name']][2] = "暂无数据";			}			if ((date("H") > $v["shou_time"] && $v["shou_time"] > 0) || date("H") < $v["kai_time"]) {				$data[$v['name']][3] = 0;			} else {				$data[$v['name']][3] = 1;			}			$data[$v['name']][4] = num($v['volume'] * $v['new_price'], 2);			$data[$v['name']][5] = $v['name'];			$data[$v['name']][6] = num($v['volume'], 2);			$data[$v['name']][7] = num($v['change'], 2);			$data[$v['name']][8] = $v['name'];			$data[$v['name']][9] = $v['img'];			$data[$v['name']][10] = num($v['buy_price'], $round);			$data[$v['name']][11] = num($v['sell_price'], $round);			$data[$v['name']][12] = '';		}		ajax('数据正常', 1, $data);	}	public function top_coin_menu()	{		$mymz_trade = new \mymc\trade();		$menu = $mymz_trade->daohang();		exit(json_encode($menu));	}	public function bubi_hq()	{		$mymz_trade = new \mymc\trade();		$menu = $mymz_trade->market_select();		$market = iv('post.market', 'w_', '市场错误');		foreach ($menu[$market] as $k => $v) {			$menu[$market][$k] = num($v, 2);		}		exit(json_encode($menu[$market]));	}	public function hq_json()	{		$coin = iv("post.coin", "w_", "参数错误", 1);		$market = db::table("trade_market")->where(['name' => $coin])->find("tendency");		if ($market) {			echo $market;		} else {			echo '暂无市场';		}	}	public function article()	{		$news_type_list = mc('index_index_news_type_list');		if (!$news_type_list) {			$news_type_list = db::table('article_type')->where(['type' => 1, "status" => 1])->limit(3)->order("`sort` ASC ,`id` desc")->select();			if ($news_type_list) {				foreach ($news_type_list as $k => $v) {					$news_type_list[$k]['list'] = db::table('article')->where(['type_id' => $v['id'], "status" => 1])->limit(7)->order("`sort` ASC ,`id` desc")->select();				}				mc('index_index_news_type_list', $news_type_list);			}		}		ajax($news_type_list, 1);	}} 