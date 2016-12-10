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
use Admin\Model\ConsumeTypeModel;
use Think\Log;
use Think\Upload;
use Vendor\Sms\Sms;

/**
 * 后台用户控制器
 *
 */
class MemberController extends AdminController {
	const STATUS_DELETED = -1;
	const STATUS_NO_CARD = 0;
	const STATUS_OK      = 1;
	const STATUS_LOST    = 10;
	
	
	/**
	 * 用户管理首页
	 *
	 */
	public function index() {
		$db = M('customer');
		
		$companys = $db->distinct(true)->field('company')->cache(true)->select();
		$this->assign("companys", $companys);
		$this->assign("chargeReasons", ChargeReasonModel::getAllReason());
		$this->assign("consumeReasons", ConsumeReasonModel::getAllReason());
		$this->assign("balanceReasons", BalanceReasonModel::getAllReason());
		$this->assign("consume_type", ConsumeTypeModel::getAll(is_login()));
		$where     = array ('status' => array ('egt', self::STATUS_NO_CARD));
		$company   = I("get.company");
		$is_onsite = I("get.is_onsite");
		$sex       = I("get.sex");
		$rank      = I("get.rank");
		$level     = I("get.level");
		$married   = I("get.married");
		if ($company !== '') {
			$where['company'] = $company;
		}
		if ($is_onsite !== '') {
			$where['is_onsite'] = $is_onsite;
		}
		if ($sex !== '') {
			$where['sex'] = $sex;
		}
		if ($rank !== '') {
			$where['rank'] = $rank;
		}
		if ($level !== '') {
			$where['level'] = $level;
		}
		if ($married !== '') {
			$where['married'] = $married;
		}
		$field = explode(',', 'id,card_num,company,dept,position,is_onsite,realname,sex,id_num,mobile,balance,rank,level,married,status');
		if (I('get.type') == 'ajax') {
			$search = I("search");
			if ($search['value']) {
				$where['realname|card_num|id_num|mobile|level'] = array ("like", "%" . $search['value'] . "%");
			}
			$sort = I("order");
			if ($sort && count($sort) > 0) {
				foreach ($sort as $s) {
					$order = " " . $field[ $s['column'] ] . " " . $s['dir'];
				}
			}
			
			$recordsTotal    = $db->field("id")->count();
			$recordsFiltered = $db->field("id")->where($where)->count();
			$data            = $db->field($field)
			                      ->where($where)
			                      ->order($order)
			                      ->limit(I('start'), I('length', 10))
			                      ->select();
			echo json_encode(array (
				                 "data"            => $data == false ? array () : $data,
				                 'draw'            => I('get.draw'),
				                 'recordsTotal'    => $recordsTotal,
				                 'recordsFiltered' => $recordsFiltered,
				                 'where'           => json_encode($where)
			                 ));
		} else if (I('get.type') == 'add') {
			$data = I("post.");
			foreach ($data as &$_row) {
				$_row = trim($_row);
			}
			
			$data['cTime']  = time();
			$data['status'] = self::STATUS_NO_CARD;
			if (strlen($data['id_num']) == 15) {
				$data['sex'] = $data['id_num'][14] % 2 == 0 ? 0 : 1;
			} else if (strlen($data['id_num']) == 18) {
				$data['sex'] = $data['id_num'][16] % 2 == 0 ? 0 : 1;
			} else {
				$data['sex'] = 3;
				$this->error("身份证号:" . $data['id_num'] . "错误,身份证号码为15位或者18位");
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
			
		} else if (I('get.type') == 'bat_edit') {
			$data = I("post.");
			$ids  = $data['ids'];
			if (!$ids) {
				$this->error("数据不完整");
			}
			unset($data['ids']);
			if ($db->where(array ('id' => array ("IN", $ids)))->save($data) !== false) {
				$this->success("修改成功");
			} else {
				$this->error($db->getDbError());
			};
		} else if (IS_AJAX && I('get.type') == 'getOne') {
			echo json_encode($db->field('id,card_num,card_sn,company,dept,position,is_onsite,realname,sex,id_num,mobile,balance,rank,level,married,service,status,frozen_balance')
			                    ->where("status>=0")->find(I('get.id')));
		} else if (IS_AJAX && I('get.type') == 'getOneByCardNum') {
			$card_num = I('get.card_num', "0");
			if ($card_num === "0") {
				echo "0";
			} else {
				echo json_encode($db->field('id,card_num,card_sn,company,dept,position,is_onsite,realname,sex,id_num,mobile,balance,rank,level,married,service,status,password,frozen_balance')
				                    ->where(array (
					                            "status"   => array ("egt", self::STATUS_OK),
					                            "card_num" => $card_num
				                            ))->find());
			}
		} else if (IS_AJAX && I('get.type') == 'getBat') {
			$result = $db
				->field('id,card_num,card_sn,company,dept,position,realname,mobile')
				->where(array (
					        'status' => array ("egt", 0),
					        "id"     => array ("in", I("get.ids"))
				        ))
				->select();
			echo json_encode($result);
		} else if (IS_AJAX && I('get.type') == 'getConsume_log') {
			$id     = I("get.id");
			$result = M('consume_log')
				->field('uid,cid,cTime,remark,reason')
				->where(array (
					        'cid' => $id
				        ))
				->order('cTime desc')
				->select();
			if ($result == false) {
				$result = array ();
			}
			$result3 = M('charge_log')
				->field('uid,cid,cTime,remark,reason')
				->where(array (
					        'cid' => $id
				        ))
				->order('cTime desc')
				->select();

//			if ($id > 100) {
//				$result2 = M('user_log')
//					->field('create_time as cTime,title as remark')
//					->where(array (
//						        'uid'    => $id,
//						        'source' => 0
//					        ))
//					->order('create_time desc')
//					->select();
//				if ($result2 == false) {
//					$result2 = array ();
//				}
//
//			}
			
			foreach ($result3 as $item) {
				$result[] = array ('uid' => $item['uid'], 'cid' => $item['cid'], 'ctime' => $item['ctime'], 'remark' => $item['remark']);
			}
			foreach ($result as &$row) {
				if ($row['reason'] && !is_int($row['reason'])) {
					$row['remark'] = $row['remark'] . " 理由:" . $row['reason'];
				}
				$row['ctime'] = date("Y-m-d H:i:s", $row['ctime']);
			}
			echo json_encode($result);
		} else {
			$this->meta_title = '会员信息';
			$this->display();
		}
	}
	
	public function import_tuan() {
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
				$db         = M("customer");
				$company_db = M("company");
				
				$card_num_max = $db->where("card_num LIKE 'C%'")->max("card_num");
				$num_max      = empty($card_num_max) ? '1' : substr($card_num_max, 1) + 1;
				
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
							$row[ $key ] = trim((string)$value);
						}
					}
					
					if (strlen($row['F']) == 15) {
						$sex = $row['F'][14] % 2 == 0 ? 0 : 1;
					} else if (strlen($row['F']) == 18) {
						$sex = $row['F'][16] % 2 == 0 ? 0 : 1;
					} else {
						$sex = 3;
						$this->error("身份证号:" . $row['F'] . "错误,身份证号码为15位或者18位");
					}
					
					if ($db->where("id_num='%s'", $row['F'])->find()) {
						$this->error("身份证号:" . $row['F'] . "错误,数据库中已经存在该会员!");
					}
					
					
					if ($row['I'] == '航天内部会员') {
						$row['I'] = 1;
					} else if ($row['I'] == 'VIP会员') {
						$row['I'] = 2;
					} else {
						$row['I'] = 0;
					}
					
					//modify 单位修正 单位必须存在
					
					if (!$company_db->where("name='%s'", $row['B'])->find()) {
						$this->error("该单位不存在:" . $row['B']);
					}
					
					if (!in_array($row['K'], array ('Ⅰ', 'Ⅱ', 'Ⅲ', 'Ⅳ', 'Ⅴ', 'Ⅵ', 'Ⅶ'))) {
						$this->error("错误的会员等级:" . $row['K']);
					}
					
					if (!in_array($row['L'], array ('有', '无', '不确定'))) {
						$row['L'] = '不确定';
					}
					
					$password = md5(substr($row['F'],-6));					
					$customerList[] = array (
						'company'  => trim($row['B']),
						'dept'     => trim($row['C']),
						'position' => trim($row['D']),
						'realname' => trim($row['E']),
						'id_num'   => trim($row['F']),
						'mobile'   => trim($row['G']),
						'balance'  => trim($row['H']),
						'rank'     => trim($row['I']),
						'service'  => trim($row['J']),
						'level'    => trim($row['K']),
						'married'  => trim($row['L']),							
						'card_num' => 'C' . substr('00000' . $num_max, -6),
						'password'  => $password,
						'cTime'    => time(),
						'status'   => self::STATUS_OK,
						"sex"      => $sex
					);
					
					$num_max++;
					if ($num_max > 999999) {
						$this->error("会员卡号出现溢出错误:大于C999999");
					}
				}
				$result = $db->addAll($customerList);
				if ($result) {
					$loginuser = session("user_auth");
					addUserLog("导入会员{$path}", $loginuser['uid']);
					$this->success("导入成功");
				} else {
					$this->error("请检查是否存在重复的身份证,或者Excel中数据缺失!" . $db->getDbError() . $db->getError());
				}
			} else {
				$this->error($Upload->getError());
				return false;
			}
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
				$db           = M("customer");
				$company_db   = M("company");
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
							$row[ $key ] = trim((string)$value);
						}
					}
					
					if (strlen($row['F']) == 15) {
						$sex = $row['F'][14] % 2 == 0 ? 0 : 1;
					} else if (strlen($row['F']) == 18) {
						$sex = $row['F'][16] % 2 == 0 ? 0 : 1;
					} else {
						$sex = 3;
						$this->error("身份证号:" . $row['F'] . "错误,身份证号码为15位或者18位");
					}
					if ($db->where("id_num='%s'", $row['F'])->find()) {
						$this->error("身份证号:" . $row['F'] . "错误,数据库中已经存在该会员!");
					}
					
					if ($row['I'] == '航天内部会员') {
						$row['I'] = 1;
					} else if ($row['I'] == 'VIP会员') {
						$row['I'] = 2;
					} else {
						$row['I'] = 0;
					}
					
					//modify 单位修正 单位必须存在
					
					if (!$company_db->where("name='%s'", $row['B'])->find()) {
						$this->error("该单位不存在:" . $row['B']);
					}
					
					if (!in_array($row['K'], array ('Ⅰ', 'Ⅱ', 'Ⅲ', 'Ⅳ', 'Ⅴ', 'Ⅵ', 'Ⅶ'))) {
						$this->error("错误的会员等级:" . $row['K']);
					}
					
					if (!in_array($row['L'], array ('有', '无', '不确定'))) {
						$row['L'] = '不确定';
					}
					
					$customerList[] = array (
						'company'      => trim($row['B']),
						'dept'         => trim($row['C']),
						'position'     => trim($row['D']),
						'realname'     => trim($row['E']),
						'id_num'       => trim($row['F']),
						'mobile'       => trim($row['G']),
						'balance'      => trim($row['H']),
						'rank'         => trim($row['I']),
						'service'      => trim($row['J']),
						'level'        => trim($row['K']),
						'married'      => trim($row['L']),
						'init_balance' => trim($row['H']),
						'cTime'        => time(),
						'status'       => self::STATUS_NO_CARD,
						"sex"          => $sex
					);
				}
				$result = $db->addAll($customerList);
				if ($result) {
					$loginuser = session("user_auth");
					addUserLog("导入会员{$path}", $loginuser['uid']);
					$this->success("导入成功");
				} else {
					$this->error("请检查是否存在重复的身份证号,或者Excel中数据缺失!" . $db->getDbError() . $db->getError());
				}
			} else {
				$this->error($Upload->getError());
				return false;
			}
		}
	}
	
	public function export() {
		$db  = M("customer");
		$map = array ('status' => array ('egt', self::STATUS_NO_CARD));
		if (I("get.company")) {
			$map['company'] = I("get.company");
		}
		$data = $db->field('id,card_num,company,dept,position,is_onsite,realname,sex,id_num,mobile,balance,rank,service')
		           ->where($map)
		           ->select();
		//判断性别是否男女
		foreach ($data as $k => &$v) {
			$v['sex'] = $v['sex'] ? '男' : '女';
		}
		exportExcel("会员导出", array (
			array ("id", "id号"),
			array ("card_num", "会员卡号"),
			array ("company", "单位"),
			array ("dept", "部门"),
			array ("position", "职位"),
			array ("is_onsite", "是否一线"),
			array ("realname", "姓名"),
			array ("sex", "性别"),
			array ("id_num", "身份证"),
			array ("mobile", "手机"),
			array ("balance", "金额"),
			array ("service", "服务")
		), $data);
		
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
		
		$res = M('customer')->where(array ("id" => array ("IN", $ids)))->setField('status', self::STATUS_DELETED);
		if ($res) {
			$loginuser = session("user_auth");
			addUserLog("删除会员{$res['id']{$res['realname']}}", $loginuser['uid']);
			$this->success("删除成功!");
		} else {
			$this->error(M()->getDbError());
		}
	}
	
	
	public function charge() {
		if (IS_POST) {//通过刷卡充值
			$data  = I("post.");
			$db    = M('customer');
			$where = array (
				"card_num" => $data['card_num'],
				"card_sn"  => $data['card_sn'],
			);
			$exits = $db->where($where)->find();
			if ($exits) {
				if ($data['money'] < 0 && is_numeric($data['money'])) {
					$this->error("充值只能为正数!");
				}
				$res = $db->where(array ('id' => $exits['id']))->setInc("balance", $data['money']);
				if ($res !== false) {
					session("last_charge_reason", $data['reason']);
					$loginuser = session("user_auth");
					addUserLog("给 {$data['card_num']}-{$exits['realname']} 充值{$data['money']} , 理由:{$data['reason']}", $loginuser['uid']);
					addLog('charge_log', array (
						'uid'    => $loginuser['uid'],
						'cid'    => $exits['id'],
						'money'  => $data['money'],
						'cTime'  => time(),
						'reason' => $data['reason'],
						'remark' => ChargeReasonModel::getReason($data['reason'])
					));
					$sms         = new Sms();
					$send_status = $sms->send($exits['mobile'], "您的账户在" . date("Y-m-d H:i:s") . "充值了{$data['money']}元，现在的余额为" . round(floatval($exits['balance']) + floatval($data['money']), 2) . "。");
					Log::write($send_status);
					$this->success("充值成功");
				} else {
					$this->error("充值失败", $db->getDbError());
				}
			} else {
				$this->error("此卡错误!未找到该卡信息");
			}
		} else {
			$this->error("访问出错!");
		}
//		else if(IS_GET){//TODO 通过选择充值
//
//		}
		
	}
	
	/**
	 * 开卡
	 */
	public function newcard() {
		if (IS_POST) {//通过刷卡充值
			$data   = I("post.");
			$db     = M('customer');
			$db_log = M('new_card_log');
			
			if ($data['card_num'] == '') {
				$this->error("卡片信息错误,刷卡失败,请重试");
			}
			
			if ($data['card_sn']) {
				if ($db_log->where("card_num='%s' or card_sn='%s' ", $data['card_num'], $data['card_sn'])->find()) {
					$this->error("此卡已经使用!");
				}
			} else {
				if ($db_log->where("card_num='%s' ", $data['card_num'])->find()) {
					$this->error("此卡已经使用!");
				}
			}
			
			$exits = null;
			if ($data['card_sn']) {
				$exits = $db->where(" card_num='%s' or card_sn='%s' ", $data['card_num'], $data['card_sn'])->find();
			} else {
				$exits = $db->where(" card_num='%s'  ", $data['card_num'])->find();
			}
			if (!$exits) {
				
				$res = $db->where(array ('id' => $data['id']))->save(array (
					                                                     "card_num" => $data['card_num'],
					                                                     "card_sn"  => $data['card_sn'],
					                                                     'status'   => self::STATUS_OK
				                                                     ));
				
				if ($res !== false) {
					$loginuser = session("user_auth");
					addUserLog("给 卡号:{$data['card_num']} 开卡,绑定会员ID{$data['id']}", $loginuser['uid']);
					addLog('new_card_log', array (
						'uid'      => $loginuser['uid'],
						'cid'      => $data['id'],
						'cTime'    => time(),
						'card_num' => $data['card_num'],
						'card_sn'  => $data['card_sn'],
						'remark'   => "给 卡号:{$data['card_num']} 开卡,绑定会员ID{$data['id']}", $loginuser['uid']
					));
					$this->success("开卡成功");
				} else {
					$this->error("开卡失败", $db->getDbError());
				}
			} else {
				$this->error("此卡已被绑定!");
			}
		} else {
			$this->error("访问出错!");
		}
		
	}
	
	
	/**
	 * 补卡
	 */
	public function recard() {
		if (IS_POST) {//通过刷卡充值
			$data   = I("post.");
			$db     = M('customer');
			$db_log = M('new_card_log');
			$exits  = $db->where("id=%d", $data['id'])->find();
			if ($exits) {
				
				if ($data['card_sn']) {
					if ($db_log->where("card_num='%s' or card_sn='%s' ", $data['card_num'], $data['card_sn'])->find()) {
						$this->error("此卡已经使用!");
					}
				} else {
					if ($db_log->where("card_num='%s' ", $data['card_num'])->find()) {
						$this->error("此卡已经使用!");
					}
				}
				
				
				$res = $db->where(array ('id' => $data['id']))->save(array (
					                                                     "card_num" => $data['card_num'],
					                                                     "card_sn"  => $data['card_sn'],
					                                                     "status"   => self::STATUS_OK
				                                                     ));
				
				if ($res !== false) {
					$loginuser = session("user_auth");
					addUserLog("给 新卡号:{$data['card_num']} {$exits['realname']}补卡,绑定会员ID{$data['id']}", $loginuser['uid']);
					addLog('new_card_log', array (
						'uid'      => $loginuser['uid'],
						'cid'      => $data['id'],
						'cTime'    => time(),
						'card_num' => $data['card_num'],
						'card_sn'  => $data['card_sn'],
						'remark'   => "给 新卡号:{$data['card_num']} {$exits['realname']}补卡,绑定会员ID{$data['id']}"
					));
					$sms         = new Sms();
					$send_status = $sms->send($exits['mobile'], "您的账户在" . date("Y-m-d H:i:s") . "补卡成功，当前余额为" . round(floatval($exits['balance']), 2) . "。");
					Log::write($send_status);
					$this->success("补卡成功");
				} else {
					$this->error("补卡失败", $db->getDbError());
				}
			} else {
				$this->error("查无此人!");
			}
		} else {
			$this->error("访问出错!");
		}
		
	}
	
	/**
	 * 挂失
	 */
	public function lost() {
		if (IS_AJAX) {
			$id = I("get.id");
			$db = M('customer');
			
			$exits = $db->where("id = %d", $id)->find();
			if ($exits) {
				if ($exits['status'] < self::STATUS_OK) {
					$this->error("未开卡状态不能挂失!");
					return;
				}
				if ($exits['status'] == self::STATUS_LOST) {
					$this->error("已经挂失!");
					return;
				}
				$res = $db->where(array ('id' => $id))->save(array (
					                                             "card_num" => '',
					                                             "card_sn"  => '',
					                                             'status'   => self::STATUS_LOST
				                                             ));
				
				if ($res !== false) {
					$loginuser = session("user_auth");
					addUserLog("给 会员{$exits['realname']}挂失,会员ID{$exits['id']}", $loginuser['uid']);
					addLog('lost_card_log', array (
						'uid'      => $loginuser['uid'],
						'cid'      => $id,
						'cTime'    => time(),
						'card_num' => $exits['card_num'],
						'card_sn'  => $exits['card_sn'],
						'remark'   => "给 会员{$exits['realname']}挂失,会员ID{$exits['id']}"
					));
					$sms         = new Sms();
					$send_status = $sms->send($exits['mobile'], "您的账户在" . date("Y-m-d H:i:s") . "挂失成功，当前余额为" . round(floatval($exits['balance']), 2) . "。");
					Log::write($send_status);
					$this->success("挂失成功");
				} else {
					$this->error("挂失失败", $db->getDbError());
				}
			} else {
				$this->error("查无此人!");
			}
		} else {
			$this->error("访问出错!");
		}
		
	}
	
	public function repass() {
		if (IS_POST) {
			$data = I("post.");
			$db   = M("customer");
			if (isset($data['id'])) {
				$exist = $db->find($data['id']);
				if ($exist && $exist['status'] >= self::STATUS_OK && $exist['status'] != self::STATUS_LOST) {

//					if ($exist['password']) {//有原密码
//						if (md5($data['password0']) != $exist['password']) {//原密码正确
//							$this->error("原密码错误,请重试!");
//						}
//					} else {//没有原密码
//						// 0 代替X
//
//						if (trim($data['password0']) != str_ireplace("X", "0", strtoupper(substr($exist['id_num'], -6)))) {//身份证后六位
//							$this->error("原密码错误,请重试!");
//						}
//					}
//
//					if (($data['password'] == $data['password1']) && strlen($data['password']) == 6) {
//						if ($data['password'] == str_ireplace("X", "0", strtoupper(substr($exist['id_num'], -6)))) {
//							$this->error("不能使用默认密码!");
//						} else {
//							$result = $db->where("id = %d ", $data['id'])->setField("password", md5($data['password']));
//							if ($result !== false) {
//								$loginuser = session("user_auth");
//								addUserLog("ID:{$exist['id']},用户{$exist['realname']} 修改了密码", $loginuser['uid']);
//								$this->success("更改成功");
//							} else {
//								$this->error("修改失败!" . $db->getDbError());
//							}
//						}
//					} else {
//						$this->error("两次密码不一致,或者密码不是6位");
//					}
					if (($data['password'] == $data['password1']) && strlen($data['password']) == 6 && is_numeric($data['password'])) {
						if ($data['password'] == str_ireplace("X", "0", strtoupper(substr($exist['id_num'], -6)))) {
							$this->error("不能使用默认密码!");
						} else {
							$result = $db->where("id = %d ", $data['id'])->setField("password", md5($data['password']));
							if ($result !== false) {
								$loginuser = session("user_auth");
								addUserLog("ID:{$exist['id']},用户{$exist['realname']} 修改了密码", $loginuser['uid']);
								$this->success("更改成功");
							} else {
								$this->error("修改失败!" . $db->getDbError());
							}
						}
					} else {
						$this->error("两次密码不一致,或者密码不是6位纯数字");
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
	
	public
	function lockmoney() {
		$db = M("customer");
		if (IS_POST) {
			$data = I("post.");
			
			if (isset($data['id'])) {
				$exist = $db->find($data['id']);
				if ($exist && $exist['status'] >= self::STATUS_OK && $exist['status'] != self::STATUS_LOST) {
					if (md5($data['password']) == $exist['password']) {
						M()->startTrans();
						$result  = $db->where("id = %d ", $data['id'])->setInc("frozen_balance", $data['money']);
						$result2 = $db->where("id = %d ", $data['id'])->setDec("balance", $data['money']);
						if ($result && $result2) {
							M()->commit();
						} else {
							M()->rollback();
							$this->error("修改失败,please try again.");
						}
						if ($result !== false) {
							$loginuser = session("user_auth");
							addUserLog("ID:{$exist['id']},用户{$exist['realname']} 冻结了{$data['money']}", $loginuser['uid']);
							addLog('frozen_log', array (
								'uid'      => $loginuser['uid'],
								'cid'      => $data['id'],
								'cTime'    => time(),
								'card_num' => $exist['card_num'],
								'card_sn'  => $exist['card_sn'],
								'remark'   => "给 会员{$exist['realname']}冻结了{$data['money']},会员ID{$exist['id']}"
							));
							$sms         = new Sms();
							$send_status = $sms->send($exist['mobile'], "您的账户在" . date("Y-m-d H:i:s") . "冻结了{$data['money']}元，现在的余额为" . round(floatval($exist['balance']) - floatval($data['money']), 2) . "。");
							Log::write($send_status);
							$this->success("冻结成功");
						} else {
							$this->error("修改失败!" . $db->getDbError());
						}
					} else {
						$this->error("密码错误!");
					}
				} else {
					$this->error("用户状态异常,请重试!");
				}
			} else {
				$this->error("未找到用户,请重试!");
			}
		} else {
			if (IS_AJAX && I('get.type') == 'release') {
				$id            = I("get.id");
				$release_money = I('get.release_money');
				$exist         = $db->find($id);
				if ($exist && $exist['status'] >= self::STATUS_OK && $exist['status'] != self::STATUS_LOST) {
					if ($exist['frozen_balance'] >= $release_money) {
						
						M()->startTrans();
						$result  = $db->where("id = %d ", $id)->setDec("frozen_balance", $release_money);
						$result2 = $db->where("id = %d ", $id)->setInc("balance", $release_money);
						if ($result && $result2) {
							M()->commit();
						} else {
							M()->rollback();
							$this->error("修改失败,please try again.");
						}
						if ($result !== false) {
							$loginuser = session("user_auth");
							addUserLog("ID:{$exist['id']},用户{$exist['realname']} 解冻了{$release_money}", $loginuser['uid']);
							addLog('frozen_log', array (
								'uid'      => $loginuser['uid'],
								'cid'      => $id,
								'cTime'    => time(),
								'card_num' => $exist['card_num'],
								'card_sn'  => $exist['card_sn'],
								'remark'   => "给 会员{$exist['realname']}解冻结了{$release_money},会员ID{$exist['id']}"
							));
							$this->success("解冻成功");
						} else {
							$this->error("修改失败!" . $db->getDbError());
						}
					} else {
						$this->error("解冻应该少于冻结金额!");
					}
				} else {
					$this->error("用户状态异常,请重试!");
				}
				
			} else {
				$this->error("错误访问!");
			}
			
		}
	}
	
	public
	function consume() {
		$db = M("customer");
		if (IS_POST) {
			$data = I("post.");
			if (isset($data['id'])) {
				$exist = $db->find($data['id']);
				if ($exist && $exist['status'] >= self::STATUS_OK && $exist['status'] != self::STATUS_LOST) {
					session("last_reason", $data['reason']);
					if (md5($data['password']) == $exist['password']) {
						if ($exist['balance'] >= $data['money']) {
							$result = $db->where("id = %d ", $data['id'])->setDec("balance", $data['money']);
							if ($result !== false) {
								$loginuser = session("user_auth");
								addUserLog("ID:{$exist['id']},用户{$exist['realname']} 消费了{$data['money']}", $loginuser['uid']);
								addLog('consume_log', array (
									'uid'      => $loginuser['uid'],
									'cid'      => $data['id'],
									'cTime'    => time(),
									'card_num' => $exist['card_num'],
									'card_sn'  => $exist['card_sn'],
									'reason'   => $data['reason'],
									"money"    => $data['money'],
									'type'     => $data['type'],
									'pay_type' => "余额后台消费",
									'remark'   => "会员{$exist['realname']}消费了{$data['money']},理由是{$data['reason']},类型为{$data['type']},会员ID{$exist['id']}"
								));
								$sms         = new Sms();
								$send_status = $sms->send($exist['mobile'], "您的账户在" . date("Y-m-d H:i:s") . "消费了{$data['money']}元，现在的余额为" . round(floatval($exist['balance']) - floatval($data['money']), 2) . "。");
								Log::write($send_status);
								$this->success("消费成功");
							} else {
								$this->error("出错!" . $db->getDbError());
							}
						} else {
							$this->error("余额不足!");
						}
						
					} else {
						$this->error("密码错误!");
					}
				} else {
					$this->error("用户状态异常,请重试!");
				}
			} else {
				$this->error("未找到用户,请重试!");
			}
		}
		
	}
	
	
	/**
	 * 金额调整
	 */
	public
	function balance() {
		$db = M("customer");
		if (IS_POST) {
			$data = I("post.");
			$ids  = $data['ids'];
			if (!$ids) {
				$this->error("数据不完整");
			}
			if ($data['value'] <= 0) {
				$this->error("值必须为正数!");
			}
			$result = false;
			unset($data['ids']);
			$where = array ('id' => array ("IN", $ids));
			if ($data['balance_type'] == '+') {
				$result = $db->where($where)->setInc("balance", $data['value']);
			} else if ($data['balance_type'] == '-') {
				$_where            = $where;
				$_where['balance'] = array ('lt', $data['value']);
				$members           = $db->where($_where)->find();
				if ($members) {
					$this->error("会员{$members['realname']}余额不足!");
					return;
				}
				
				$result = $db->where($where)->setDec("balance", $data['value']);
			} else {
				$this->error("错误的操作类型!");
			}
			if ($result !== false) {
				$loginuser = session("user_auth");
				addUserLog("用户ID:{$ids}, 被批量操作金额{$data['balance_type']}{$data['value']}", $loginuser['uid']);
				$sms = new Sms();
				
				$ids = explode(',', $ids);
				foreach ($ids as $id) {
					$exist   = $db->find($id);
					$_reason = BalanceReasonModel::getReason($data['reason']);
					addLog('consume_log', array (
						'uid'      => $loginuser['uid'],
						'cid'      => $id,
						'cTime'    => time(),
						'card_num' => '',
						'card_sn'  => '',
						'reason'   => $_reason,
						"money"    => $data['value'],
						'pay_type' => '余额批量扣费',
						'remark'   => "会员ID:{$exist['realname']}批量操作{$data['balance_type']}{$data['value']},理由是{$_reason},会员ID{$exist['id']}"
					));
					if ($data['reason']) {
						$send_status = false;
						if ($data['balance_type'] == '+') {
							$text        = "您的账户在" . date("Y-m-d H:i:s") . "充值了{$data['value']}元，现在的余额为" . round(floatval($exist['balance']), 2) . "。";
							$send_status = $sms->send($exist['mobile'], $text);
						} else if ($data['balance_type'] == '-') {
							$text        = "您的账户在" . date("Y-m-d H:i:s") . "消费了{$data['value']}元，现在的余额为" . round(floatval($exist['balance']), 2) . "。";
							$send_status = $sms->send($exist['mobile'], $text);
						}
					}
				}
				
				$this->success("修改成功");
			} else {
				$this->error($db->getDbError());
			};
		}
		
	}
	
	//安排团建信息发送 
	public function tuanjian() {
		
		$com = $_GET['company'];
		
		$api = A('Api/User');
		
		echo $api->getTeamInfo($com);
	}
	
}