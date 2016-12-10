<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Admin\Model\ConfigModel;
use Think\Log;
use Think\Upload;
use User\Api\UserApi;
use Vendor\Sms\Sms;

/**
 * 后台用户控制器
 *
 */
class MemberBatBalanceController extends AdminController {
	
	public function index() {
		
		$this->assign("meta_title", "会员批量充值");
		if (IS_AJAX) {
			set_time_limit(0);
			$result      = session("import_balance");
			$wrong_data  = session("wrong_data");
			$total_money = session("total_money");
			$total_count = session("total_count");
			if (!$wrong_data) {
				$wrong_data = array ();
			}
			$db        = M("customer");
			$log_db    = M("charge_log");
			$loginuser = session("user_auth");
			$sms       = new Sms();
			$_temp     = 0;
			foreach ($result as $key => $row) {
				$_temp++;
				M()->startTrans();
				$r1          = $db->fetchSql(false)->where(array ("card_num" => $row['card_num']))->setInc("balance", $row['money']) . ";";
				$r2          = $log_db->fetchSql(false)->add(array (
					                                             'uid'      => $loginuser['uid'],
					                                             'cid'      => $row['id'],
					                                             'cTime'    => time(),
					                                             'card_num' => $row['card_num'],
					                                             'reason'   => "批量充值-" . $row['reason'],
					                                             "money"    => $row['money'],
					                                             'remark'   => "会员{$row['realname']}充值 {$row['money']},会员ID{$row['id']}"
				                                             )) . ";";
				$temp        = round(floatval($row['balance']) - floatval($row['frozen_balance']) + floatval($row['money']), 2);
				$sms_content = "您的账户在" . date("Y-m-d H:i:s") . "充值了{$row['money']}元，现在的余额为" . $temp . "。";

//				Log::write($send_status);
				
				if ($r1 && $r2) {
					M()->commit();
					$total_money += $row['money'];
					$total_count++;
					$send_status = $sms->send($row['mobile'], $sms_content);
				} else {
					$wrong_data[] = $row;
					M()->rollback();
				}
				unset($result[ $key ]);
				if ($_temp > 100) {
					break;
				}
			}
			session("import_balance", $result);
			session("wrong_data", $wrong_data);
			session("total_money", $total_money);
			session("total_count", $total_count);
			Log::record("出错数据:" . print_r($wrong_data, true), Log::ERR);
//			dump($sms->send_multi());
			if (count($result) <= 0) {
				addUserLog("导入批量充值文件,已经执行成功.", $loginuser['uid']);
				session("import_balance", null);
				$this->ajaxReturn(array (
					                  "status"      => 1,
					                  "count"       => count($result),
					                  'wrong_count' => count($wrong_data),
					                  'wrong'       => $wrong_data,
					                  'total_money' => $total_money,
					                  'total_count' => $total_count
				                  ));
			} else {
				$this->ajaxReturn(array (
					                  "status" => 1,
					                  "count"  => count($result),
				                  ));
			}
			
			
		} else if (IS_POST) {
			/* 上传文件 */
			session("import_consume_backUp", null);
			session("wrong_data", null);
			session("import_consume", null);
			session("wrong_data", null);
			session("total_money", null);
			session("total_count", null);
			
			$config      = C('MEMBER_UPLOAD');
			$Upload      = new Upload($config);
			$total_money = 0;
			$info        = $Upload->upload();
			if (isset($info['consume'])) { //文件上传成功，记录文件信息
				$path = "." . substr($config['rootPath'], 1) . $info['consume']['savepath'] . $info['consume']['savename'];    //在模板里的url路径
				if (stripos($path, ".xlsx") !== false) {
					$data = excel_import($path, 'xlsx');
				} else {
					$data = excel_import($path);
				}
				unset($data[1]);
				$count_import = 0;
				$db           = M("customer");
				$lists        = array ();
				$id_num       = array ();
				foreach ($data as $row) {
					if ($row['A'] == '') {
						continue;
					}
					$count_import++;
					foreach ($row as $key => $value) {
						if (is_null($value)) {
							$row[ $key ] = "";
						} else {
							$row[ $key ] = trim((string)$value);
						}
					}
					$id_num[]           = strtoupper($row['B']);
					$lists[ $row['B'] ] = array (
						'id_num'       => strtoupper($row['B']),
						"new_realname" => $row['C'],
						"money"        => $row['D'],
						'new_card_num' => $row['A'],
						'reason'       => trim((string)$row['E'])
					);
					$total_money += floatval($row['D']);
				}
				
				$result = $db->field("id,realname,sex,card_num,company,id_num,dept,position,balance,frozen_balance,mobile")
				             ->where(array ('id_num' => array ("in", $id_num)))->order('card_num ')->select();
				
				foreach ($result as &$member) {
					if (!empty($lists[ $member['id_num'] ]) && !empty($member)) {
						$member = array_merge($member, $lists[ $member['id_num'] ]);
						$key    = array_search($member['id_num'], $id_num);
						unset($id_num[ $key ]);
					}
				}
				
				
				//提示出来卡号出问题的人.
				if (count($result) != count($lists)) {
					$this->error("发现身份证号为: " . implode(",", $id_num) . " 的用户在系统中不存在,请检查. ");
					return;
				}
				
				if ($count_import != count($lists)) {
					$this->error("导入的数据条数和最终确认条数不一致,请检查!(同会员一张Excel不得扣除两次)");
					return;
				}


//				dump($id_num);
				if ($result) {
					session("import_balance", $result);
					session("import_balance_backUp", $result);
					
					$this->assign("result", $result);
					$this->assign("meta_title", "数据确认");
					$this->assign("wrong", $id_num);
					$this->assign("total_money", $total_money);
					$this->assign("total_count", count($result));
					$this->display("confirm");
				} else {
					$this->error("未找到对应的会员!" . $db->getError());
				}
			} else {
				$this->error($Upload->getError());
				return false;
			}
		} else {
			$this->display();
		}
	}
	
	public function result() {
		$this->assign("meta_title", "导入结果");
		$import_balance_backUp = session("import_balance_backUp");
		$wrong_data            = session("wrong_data");
		
		$this->assign("total_count", session("total_count"));
		$this->assign("total_money", session("total_money"));
		
		
		session("import_balance_backUp", null);
		session("wrong_data", null);
		session("import_consume", null);
		session("wrong_data", null);
		session("total_money", null);
		session("total_count", null);
		
		
		$this->assign("result", $import_balance_backUp);
		$this->assign("wrong_data", $wrong_data);
		$this->display();
	}
	
}