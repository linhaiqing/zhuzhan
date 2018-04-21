<?phpnamespace mymc;use Move\db;class coin{	public $mc_data = [];	public function __construct()	{		$data = mc('mymc_coin');		if (!$data) {			$res = db::table('coin')->order('sort asc ,id desc')->select();			if ($res) {				foreach ($res as $k => $v) {					$data[$v['name']] = $v;				}				mc('mymc_coin', $data);			}		}		$this->mc_data = $data;	}	public function select($status = -1)	{		if (!$this->mc_data) {			return [];		}		$res = [];		if ($status != -1) {			foreach ($this->mc_data as $k => $v) {				if ($v['status'] == $status) {					$res[$v['name']] = $v;				}			}			return $res;		}		return $this->mc_data;	}	public function find($name = null)	{		if (!$name) {			return [];		}		if (is_string($name)) {			return isset($this->mc_data[$name]) ? $this->mc_data[$name] : [];		}		if (is_array($name)) {			$coinName = isset($name['name']) ? $name['name'] : 0;			if ($coinName) {				return isset($this->mc_data[$coinName]) ? $this->mc_data[$coinName] : [];			}			$id = isset($name['id']) ? $name['id'] : 0;			if ($id) {				foreach ($this->mc_data as $k => $v) {					if ($v['id'] == $id) {						return isset($this->mc_data[$k]) ? $this->mc_data[$k] : [];					}				}			}			return [];		}		return [];	}	public function all_xnb($status = -1)	{		if (!$this->mc_data) {			return [];		}		$data = [];		foreach ($this->mc_data as $k => $v) {			if ($v['wallet'] != 'rmb') {				if ($status == -1) {					$data[$v['name']] = $v;				}				if (($status != -1) && ($v['status'] == 1)) {					$data[$v['name']] = $v;				}			}		}		return $data;	}	public function all_rmb($status = -1)	{		if (!$this->mc_data) {			return [];		}		$data = [];		foreach ($this->mc_data as $k => $v) {			if ($v['wallet'] == 'rmb') {				if ($status == -1) {					$data[$v['name']] = $v;				}				if (($status != -1) && ($v['status'] == $status)) {					$data[$v['name']] = $v;				}			}		}		return $data;	}	public function all_qbb($status = -1)	{		if (!$this->mc_data) {			return [];		}		$data = [];		foreach ($this->mc_data as $k => $v) {			if ($v['wallet'] == 'qbb') {				if ($status == -1) {					$data[$v['name']] = $v;				}				if (($status != -1) && ($v['status'] == $status)) {					$data[$v['name']] = $v;				}			}		}		return $data;	}}