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
 * 后台用户控制器
 *
 */
class CompanyController extends AdminController {
	const STATUS_DELETED  = -1;
	const STATUS_DISABLED = 0;
	const STATUS_OK       = 1;


	/**
	 * 企业管理首页
	 *
	 */
	public function index() {
		$db = M('company');
		if (I('get.type') == 'ajax') {
			echo json_encode(array (
				                 "data" => $db->field("id,name,username,contact,code,contact_tel,balance,last_login_time,status")
				                              ->where("status>" . self::STATUS_DELETED)->select(),
			                 ));
		} else if (I('get.type') == 'add') {
			$data           = I("post.");
			$data['ctime']  = time();
			$data['status'] = self::STATUS_OK;
			if ($data['password'] != $data["password1"]) {
				$this->error("两次密码不一致");
			}
			unset($data['id']);
			if ($db->add($data)) {
				$this->success("添加成功");
			} else {
				$this->error(M()->getDbError());
			};
			
		} else if (I('get.type') == 'edit') {
			$data = I("post.");
			if ($db->where(array ('id' => $data['id']))->save($data) !== false) {
				$this->success("修改成功");
			} else {
				$this->error($db->getDbError());
			};
			
		} else if (IS_AJAX && I('get.type') == 'getOne') {
			echo json_encode($db->find(I('get.id')));
		} else if (IS_AJAX && I('get.type') == 'getConsume_log') {
			$id     = I("get.id");
			$result = M('company_consume_log')
				->field('cTime,remark,money,reason')
				->where(array (
					        'cid'    => $id,
					        "status" => array ("gt", self::STATUS_DELETED)
				        ))
				->order('cTime desc')
				->select();
			foreach ($result as &$row) {
				$row['ctime'] = date("Y-m-d H:i:s");
			}
			echo json_encode($result);
		} else {
			$this->assign("meta_title", "企业信息");
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
		
		$res = M('company')->where(array ("id" => array ("IN", $ids)))->delete();
		if ($res) {
			$loginuser = session("user_auth");
			addUserLog("删除企业帐号{$res['id']{$res['name']}}", $loginuser['uid']);
			$this->success("删除成功!");
		} else {
			$this->error(M()->getDbError());
		}
	}

	public function repass() {
		if (IS_POST) {
			$data = I("post.");
			$db   = M("company");
			if (isset($data['id'])) {
				$exist = $db->find($data['id']);
				if ($exist && $exist['status'] == self::STATUS_OK) {
					if (($data['password'] == $data['password1']) && strlen($data['password']) == 6) {
						$result = $db->where("id = %d ", $data['id'])->setField("password", md5($data['password']));
						if ($result !== false) {
							$loginuser = session("user_auth");
							addUserLog("ID:{$exist['id']},企业用户{$exist['name']} 重置了密码", $loginuser['uid']);
							$this->success("更改成功");
						} else {
							$this->error("修改失败!" . $db->getDbError());
						}
					} else {
						$this->error("两次密码不一致,或者密码不是6位");
					}
				} else {
					$this->error("用户状态异常,请重试!");
				}
			} else {
				$this->error("未找到用户,请重试!");
			}
		} else {
			$this->error("错误访问!");
		}
	}

	/**
	 * 金额调整
	 */
	public function balance() {
		$db = M("company");
		if (IS_POST) {
			$data = I("post.");
			$id   = $data['id'];
			if (!$id) {
				$this->error("数据不完整");
			}
			if ($data['value'] <= 0) {
				$this->error("值必须为正数!");
			}
			$result = false;
			unset($data['id']);
			$where = array ('id' => $id);
			if ($data['balance_type'] == '+') {
				$result = $db->where($where)->setInc("balance", $data['value']);
			} else if ($data['balance_type'] == '-') {
				$result = $db->where($where)->setDec("balance", $data['value']);
			} else {
				$this->error("错误的操作类型!");
			}
			if ($result !== false) {
				$loginuser = session("user_auth");
				addUserLog("企业ID:{$id}, 操作余额:{$data['balance_type']}{$data['value']}", $loginuser['uid']);
				addLog('company_consume_log', array (
					'uid'    => $loginuser['uid'],
					'cid'    => $id,
					'cTime'  => time(),
					"money"  => $data['value'],
					'remark' => $data['remark']
				));
				$this->success("修改成功");
			} else {
				$this->error($db->getDbError());
			};
		}

	}

	public function import() {
		if (IS_POST) {
			/* 上传文件 */
			$config = C('MEMBER_UPLOAD');
			$Upload = new Upload($config);
			$info   = $Upload->upload();
			if (isset($info['members'])) { //文件上传成功，记录文件信息
				$path = "." . substr($config['rootPath'], 1) . $info['members']['savepath'] . $info['members']['savename'];    //在模板里的url路径
				if (stripos($path, ".xlsx") !== false) {
					$data = excel_import($path, 'xlsx');
				} else {
					$data = excel_import($path);
				}
				unset($data[1]);
				$db           = M("company");
				$customerList = array ();
				foreach ($data as $row) {
					if ($row['A'] == '') {
						continue;
					}
					foreach ($row as $key => $value) {
						if (is_null($value)) {
							$row[ $key ] = "";
						}
						if (is_object($value)) {
							$row[ $key ] = (string)$value;
						}
					}

					if ($db->where("code='%s'", $row['A'])->cache(true)->find()) {
						$this->error("已经存在该code:" . $row['A']);
					}

					if ($db->where("name='%s'", $row['B'])->find()) {
						$this->error("已经存在该单位:" . $row['B']);
					}

					$customerList[] = array (
						'name'        => $row['B'],
						'ctime'       => time(),
						'username'    => $row['C'],
						'contact'     => $row['D'],
						'contact_tel' => $row['E'],
						'code'        => $row['A'],
						'status'      => 1,
					);
				}
				$result = $db->addAll($customerList);
				if ($result) {
					$loginuser = session("user_auth");
					addUserLog("导入单位{$path}", $loginuser['uid']);
					$this->success("导入成功");
				} else {
					$this->error("请检查是否存在重复的code,或者Excel中数据缺失!" . $db->getDbError() . $db->getError());
				}
			} else {
				$this->error($Upload->getError());
				return false;
			}
		}
	}

	
}