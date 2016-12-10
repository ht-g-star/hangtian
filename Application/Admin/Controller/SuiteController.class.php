<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Admin\Model\BalanceReasonModel;
use Admin\Model\ChargeReasonModel;
use Admin\Model\ConsumeReasonModel;
use Think\Log;
use Think\Upload;
use Vendor\Sms\Sms;

/**
 *
 */
class SuiteController extends AdminController {
	const STATUS_DELETED  = -1;
	const STATUS_DISABLED = 0;
	const STATUS_OK       = 1;

	const MODEL_NAME = "Suite";

	/**
	 * 套餐管理首页
	 *
	 */
	public function index() {
		$this->assign("meta_title", "套餐管理");
		$action = I("get.type");
		$this->assign("action", $action);
		$db = M(self::MODEL_NAME);
		if (I('get.type') == 'ajax') {
			echo json_encode(array (
				                 "data" => $db->field("*")
				                              ->where("status > " . self::STATUS_DELETED)
				                              ->order("sort")
				                              ->select(),
			                 ));
		} else if (I('get.type') == 'add') {

			if (IS_GET) {
				$this->assign("meta_title", "新建套餐");
				$this->display("add");
			} else if (IS_POST) {
				S('C_DB_SUITE', null);
				$data          = I("post.");
				$data['ctime'] = time();
				unset($data['id']);
				/* 上传文件 */
				$config = C('PICTURE_UPLOAD');
				$Upload = new Upload($config);
				$info   = $Upload->upload();
				if (isset($info['pic'])) { //文件上传成
					$path        = substr($config['rootPath'], 1) . $info['pic']['savepath'] . $info['pic']['savename'];    //在模板里的url路径
					$data['pic'] = $path;
				}

				if ($db->add($data)) {
					$this->success("添加成功", U('index'));
				} else {
					$this->error(M()->getDbError());
				};
			}
		} else if (I('get.type') == 'edit') {
			if (IS_GET) {
				$this->assign("meta_title", "编辑套餐");
				$this->assign("data", $db->find(I('get.id')));
				$this->display("add");
			} else if (IS_POST) {
				S('C_DB_SUITE', null);
				$data   = I("post.");
				$config = C('PICTURE_UPLOAD');
				$Upload = new Upload($config);
				$info   = $Upload->upload();
				if (isset($info['pic'])) { //文件上传成
					$path        = substr($config['rootPath'], 1) . $info['pic']['savepath'] . $info['pic']['savename'];    //在模板里的url路径
					$data['pic'] = $path;
				}

				if ($db->where(array ('id' => $data['id']))->save($data) !== false) {
					$this->success("修改成功", U('index'));
				} else {
					$this->error($db->getDbError());
				};
			}
		} else {
			$this->display();
		}
	}

	public function del() {
		$ids = I("get.ids");
		if (!$ids) {
			$this->error("请选择删除的条目");
		}
		$ids = explode(",", $ids);
		if (count($ids) <= 0) {
			$this->error("请选择删除的条目");
		}
		S('C_DB_SUITE', null);
		$res = M(self::MODEL_NAME)->where(array ("id" => array ("IN", $ids)))->setField('status', self::STATUS_DELETED);
		if ($res) {
			$loginuser = session("user_auth");
			addUserLog("删除套餐{$res['id']{$res['title']}}", $loginuser['uid']);
			$this->success("删除成功!");
		} else {
			$this->error(M()->getDbError());
		}
	}


	public function sysn() {
		$all_course = file_get_contents("http://localhost:8088/MeXamService.asmx/GetAllCourses");
		$all_course
		            = '{
    "total": 76,
    "count": 76,
    "data": [
        {
            "course_id": "000068",
            "course_name": "A套1601",
            "course_shortname": "A套1601",
            "course_invalidflag": "0",
            "course_sortnum": "0"
        },
        {
            "course_id": "000069",
            "course_name": "B套1601",
            "course_shortname": "B套1601",
            "course_invalidflag": "0",
            "course_sortnum": "1"
        },
        {
            "course_id": "000073",
            "course_name": "C套1601",
            "course_shortname": "C套1601",
            "course_invalidflag": "0",
            "course_sortnum": "2"
        },
        {
            "course_id": "000074",
            "course_name": "D套1601",
            "course_shortname": "D套1601",
            "course_invalidflag": "0",
            "course_sortnum": "3"
        },
        {
            "course_id": "000075",
            "course_name": "E套1601",
            "course_shortname": "E套1601",
            "course_invalidflag": "0",
            "course_sortnum": "4"
        },
        {
            "course_id": "000076",
            "course_name": "F套1601",
            "course_shortname": "F套1601",
            "course_invalidflag": "0",
            "course_sortnum": "5"
        },
        {
            "course_id": "000001",
            "course_name": "A套",
            "course_shortname": "A套",
            "course_invalidflag": "1",
            "course_sortnum": "6"
        },
        {
            "course_id": "000002",
            "course_name": "B套",
            "course_shortname": "B套",
            "course_invalidflag": "1",
            "course_sortnum": "7"
        },
        {
            "course_id": "000003",
            "course_name": "C套",
            "course_shortname": "C套",
            "course_invalidflag": "1",
            "course_sortnum": "8"
        },
        {
            "course_id": "000004",
            "course_name": "D套",
            "course_shortname": "D套",
            "course_invalidflag": "1",
            "course_sortnum": "9"
        },
        {
            "course_id": "000005",
            "course_name": "E套",
            "course_shortname": "E套",
            "course_invalidflag": "1",
            "course_sortnum": "10"
        },
        {
            "course_id": "000006",
            "course_name": "F套",
            "course_shortname": "F套",
            "course_invalidflag": "1",
            "course_sortnum": "11"
        },
        {
            "course_id": "000007",
            "course_name": "八院职工体检套餐",
            "course_shortname": "八院体检套餐",
            "course_invalidflag": "0",
            "course_sortnum": "12"
        },
        {
            "course_id": "000008",
            "course_name": "临客套餐",
            "course_shortname": "临客套餐",
            "course_invalidflag": "0",
            "course_sortnum": "13"
        },
        {
            "course_id": "000009",
            "course_name": "A套1001",
            "course_shortname": "A套1001",
            "course_invalidflag": "1",
            "course_sortnum": "14"
        },
        {
            "course_id": "000010",
            "course_name": "B套1001",
            "course_shortname": "B套1001",
            "course_invalidflag": "1",
            "course_sortnum": "14"
        },
        {
            "course_id": "000011",
            "course_name": "C套1001",
            "course_shortname": "C套1001",
            "course_invalidflag": "1",
            "course_sortnum": "15"
        },
        {
            "course_id": "000012",
            "course_name": "D套1001",
            "course_shortname": "D套1001",
            "course_invalidflag": "1",
            "course_sortnum": "16"
        },
        {
            "course_id": "000013",
            "course_name": "E套1001",
            "course_shortname": "E套1001",
            "course_invalidflag": "1",
            "course_sortnum": "17"
        },
        {
            "course_id": "000014",
            "course_name": "F套1001",
            "course_shortname": "F套1001",
            "course_invalidflag": "1",
            "course_sortnum": "18"
        },
        {
            "course_id": "000015",
            "course_name": "10院部在职套餐",
            "course_shortname": "10院部套餐",
            "course_invalidflag": "0",
            "course_sortnum": "19"
        },
        {
            "course_id": "000061",
            "course_name": "15院部体检套餐",
            "course_shortname": "15院部套餐",
            "course_invalidflag": "0",
            "course_sortnum": "20"
        },
        {
            "course_id": "000062",
            "course_name": "15院部套餐(新)",
            "course_shortname": "院部新套餐",
            "course_invalidflag": "0",
            "course_sortnum": "21"
        },
        {
            "course_id": "000016",
            "course_name": "A套1101",
            "course_shortname": "A套1101",
            "course_invalidflag": "1",
            "course_sortnum": "22"
        },
        {
            "course_id": "000017",
            "course_name": "B套1101",
            "course_shortname": "B套1101",
            "course_invalidflag": "1",
            "course_sortnum": "23"
        },
        {
            "course_id": "000018",
            "course_name": "C套1101",
            "course_shortname": "C套1101",
            "course_invalidflag": "1",
            "course_sortnum": "24"
        },
        {
            "course_id": "000019",
            "course_name": "D套1101",
            "course_shortname": "D套1101",
            "course_invalidflag": "1",
            "course_sortnum": "25"
        },
        {
            "course_id": "000020",
            "course_name": "E套1101",
            "course_shortname": "E套1101",
            "course_invalidflag": "1",
            "course_sortnum": "26"
        },
        {
            "course_id": "000021",
            "course_name": "F套1101",
            "course_shortname": "F套1101",
            "course_invalidflag": "1",
            "course_sortnum": "27"
        },
        {
            "course_id": "000022",
            "course_name": "A套1107",
            "course_shortname": "A套1107",
            "course_invalidflag": "1",
            "course_sortnum": "28"
        },
        {
            "course_id": "000023",
            "course_name": "B套1107",
            "course_shortname": "B套1107",
            "course_invalidflag": "1",
            "course_sortnum": "29"
        },
        {
            "course_id": "000024",
            "course_name": "C套1107",
            "course_shortname": "C套1107",
            "course_invalidflag": "1",
            "course_sortnum": "30"
        },
        {
            "course_id": "000025",
            "course_name": "D套1107",
            "course_shortname": "D套1107",
            "course_invalidflag": "1",
            "course_sortnum": "31"
        },
        {
            "course_id": "000026",
            "course_name": "E套1107",
            "course_shortname": "E套1107",
            "course_invalidflag": "1",
            "course_sortnum": "32"
        },
        {
            "course_id": "000027",
            "course_name": "F套1107",
            "course_shortname": "F套1107",
            "course_invalidflag": "1",
            "course_sortnum": "33"
        },
        {
            "course_id": "000028",
            "course_name": "A套1201",
            "course_shortname": "A套1201",
            "course_invalidflag": "0",
            "course_sortnum": "34"
        },
        {
            "course_id": "000029",
            "course_name": "B套1201",
            "course_shortname": "B套1201",
            "course_invalidflag": "0",
            "course_sortnum": "34"
        },
        {
            "course_id": "000030",
            "course_name": "C套1201",
            "course_shortname": "C套1201",
            "course_invalidflag": "0",
            "course_sortnum": "35"
        },
        {
            "course_id": "000031",
            "course_name": "D套1201",
            "course_shortname": "D套1201",
            "course_invalidflag": "0",
            "course_sortnum": "35"
        },
        {
            "course_id": "000032",
            "course_name": "E套1201",
            "course_shortname": "E套1201",
            "course_invalidflag": "0",
            "course_sortnum": "36"
        },
        {
            "course_id": "000033",
            "course_name": "F套1201",
            "course_shortname": "F套1201",
            "course_invalidflag": "0",
            "course_sortnum": "36"
        },
        {
            "course_id": "000034",
            "course_name": "集团总部套餐",
            "course_shortname": "集团总部套餐",
            "course_invalidflag": "0",
            "course_sortnum": "37"
        },
        {
            "course_id": "000070",
            "course_name": "贴心体检套餐",
            "course_shortname": "贴心体检套餐",
            "course_invalidflag": "0",
            "course_sortnum": "38"
        },
        {
            "course_id": "000035",
            "course_name": "A套1301",
            "course_shortname": "A套1301",
            "course_invalidflag": "0",
            "course_sortnum": "39"
        },
        {
            "course_id": "000071",
            "course_name": "超值体检套餐",
            "course_shortname": "超值体检套餐",
            "course_invalidflag": "0",
            "course_sortnum": "40"
        },
        {
            "course_id": "000036",
            "course_name": "B套1301",
            "course_shortname": "B套1301",
            "course_invalidflag": "0",
            "course_sortnum": "41"
        },
        {
            "course_id": "000072",
            "course_name": "尊享体检套餐",
            "course_shortname": "尊享体检套餐",
            "course_invalidflag": "0",
            "course_sortnum": "42"
        },
        {
            "course_id": "000037",
            "course_name": "C套1301",
            "course_shortname": "C套1301",
            "course_invalidflag": "0",
            "course_sortnum": "43"
        },
        {
            "course_id": "000038",
            "course_name": "D套1301",
            "course_shortname": "D套1301",
            "course_invalidflag": "0",
            "course_sortnum": "44"
        },
        {
            "course_id": "000039",
            "course_name": "E套1301",
            "course_shortname": "E套1301",
            "course_invalidflag": "0",
            "course_sortnum": "45"
        },
        {
            "course_id": "000040",
            "course_name": "F套1301",
            "course_shortname": "F套1301",
            "course_invalidflag": "0",
            "course_sortnum": "46"
        },
        {
            "course_id": "000041",
            "course_name": "团购温馨套餐1307",
            "course_shortname": "团购温馨套餐",
            "course_invalidflag": "0",
            "course_sortnum": "47"
        },
        {
            "course_id": "000042",
            "course_name": "团购健康无忧套餐1307",
            "course_shortname": "团购健康套餐",
            "course_invalidflag": "0",
            "course_sortnum": "48"
        },
        {
            "course_id": "000043",
            "course_name": "团购豪华尊享套餐1307",
            "course_shortname": "团购豪华套餐",
            "course_invalidflag": "0",
            "course_sortnum": "49"
        },
        {
            "course_id": "000044",
            "course_name": "A套1401",
            "course_shortname": "A套1401",
            "course_invalidflag": "0",
            "course_sortnum": "50"
        },
        {
            "course_id": "000045",
            "course_name": "B套1401",
            "course_shortname": "B套1401",
            "course_invalidflag": "0",
            "course_sortnum": "51"
        },
        {
            "course_id": "000046",
            "course_name": "C套1401",
            "course_shortname": "C套1401",
            "course_invalidflag": "0",
            "course_sortnum": "52"
        },
        {
            "course_id": "000047",
            "course_name": "D套1401",
            "course_shortname": "D套1401",
            "course_invalidflag": "0",
            "course_sortnum": "53"
        },
        {
            "course_id": "000048",
            "course_name": "E套1401",
            "course_shortname": "E套1401",
            "course_invalidflag": "0",
            "course_sortnum": "54"
        },
        {
            "course_id": "000049",
            "course_name": "F套1401",
            "course_shortname": "F套1401",
            "course_invalidflag": "0",
            "course_sortnum": "55"
        },
        {
            "course_id": "000050",
            "course_name": "关爱亲情套餐",
            "course_shortname": "关爱亲情套餐",
            "course_invalidflag": "0",
            "course_sortnum": "56"
        },
        {
            "course_id": "000051",
            "course_name": "父母感恩套餐",
            "course_shortname": "父母感恩套餐",
            "course_invalidflag": "0",
            "course_sortnum": "57"
        },
        {
            "course_id": "000067",
            "course_name": "16统筹套餐",
            "course_shortname": "16统筹套餐",
            "course_invalidflag": "0",
            "course_sortnum": "58"
        },
        {
            "course_id": "000052",
            "course_name": "15统筹套餐",
            "course_shortname": "15统筹套餐",
            "course_invalidflag": "0",
            "course_sortnum": "59"
        },
        {
            "course_id": "000066",
            "course_name": "集团职工体检套餐",
            "course_shortname": "集团职工套餐",
            "course_invalidflag": "0",
            "course_sortnum": "60"
        },
        {
            "course_id": "000053",
            "course_name": "A套1501",
            "course_shortname": "A套1501",
            "course_invalidflag": "0",
            "course_sortnum": "61"
        },
        {
            "course_id": "000054",
            "course_name": "B套1501",
            "course_shortname": "B套1501",
            "course_invalidflag": "0",
            "course_sortnum": "62"
        },
        {
            "course_id": "000055",
            "course_name": "C套1501",
            "course_shortname": "C套1501",
            "course_invalidflag": "0",
            "course_sortnum": "63"
        },
        {
            "course_id": "000056",
            "course_name": "D套1501",
            "course_shortname": "D套1501",
            "course_invalidflag": "0",
            "course_sortnum": "64"
        },
        {
            "course_id": "000057",
            "course_name": "E套1501",
            "course_shortname": "E套1501",
            "course_invalidflag": "0",
            "course_sortnum": "65"
        },
        {
            "course_id": "000058",
            "course_name": "F套1501",
            "course_shortname": "F套1501",
            "course_invalidflag": "0",
            "course_sortnum": "66"
        },
        {
            "course_id": "000065",
            "course_name": "一线人才套餐",
            "course_shortname": "一线人才套餐",
            "course_invalidflag": "0",
            "course_sortnum": "67"
        },
        {
            "course_id": "000059",
            "course_name": "健康男士套餐",
            "course_shortname": "健康男士套餐",
            "course_invalidflag": "0",
            "course_sortnum": "68"
        },
        {
            "course_id": "000060",
            "course_name": "窈窕淑女套餐",
            "course_shortname": "窈窕淑女套餐",
            "course_invalidflag": "0",
            "course_sortnum": "69"
        },
        {
            "course_id": "000063",
            "course_name": "健康精英套餐",
            "course_shortname": "健康精英",
            "course_invalidflag": "0",
            "course_sortnum": "70"
        },
        {
            "course_id": "000064",
            "course_name": "防癌卫士套餐",
            "course_shortname": "防癌卫士套餐",
            "course_invalidflag": "0",
            "course_sortnum": "71"
        }
    ],
    "status": "0",
    "msg": "",
    "err": ""
}';

		$all_course = json_decode($all_course, true);
		foreach ($all_course['data'] as $course) {
			if ($course['course_invalidflag'] == 0) {
				$code  = $course['course_id'];
				$exist = M("suite")->where("code='%s'", $code)->find();
				if (!$exist) {
					M("suite")->add(
						array (
							'code'        => $course['course_id'],
							"title"       => $course['course_shortname'],
							'description' => $course['course_name'],
							'status'      => self::STATUS_DISABLED
						)
					);
				}
			} else {
				continue;
			}
		}
        $this->success("同步成功!");

	}

	
}