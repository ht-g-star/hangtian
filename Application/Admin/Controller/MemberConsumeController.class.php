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
class MemberConsumeController extends AdminController {
	
	public function index() {
		$this->assign("meta_title", "会员消费导入");
		if (IS_AJAX) {
			set_time_limit(0);
			$result      = session("import_consume");
			$wrong_data  = session("wrong_data");
			$total_money = session("total_money");
			$total_count = session("total_count");
			if (!$wrong_data) {
				$wrong_data = array ();
			}
			$db        = M("customer");
			$log_db    = M("consume_log");
			$loginuser = session("user_auth");
			$sms       = new Sms();
			$_temp     = 0;
			foreach ($result as $key => $row) {
				$_temp++;
				M()->startTrans();
				$r1          = $db->fetchSql(false)->where(array ("card_num" => $row['card_num']))->setDec("balance", $row['money']) . ";";
				$r2          = $log_db->fetchSql(false)->add(array (
					                                             'uid'      => $loginuser['uid'],
					                                             'cid'      => $row['id'],
					                                             'cTime'    => time(),
					                                             'card_num' => $row['card_num'],
					                                             'reason'   => $row['item'],
					                                             "money"    => $row['money'],
					                                             "type"     => $row['type'] ? $row['type'] : '财务',
					                                             'remark'   => "会员{$row['realname']}消费了{$row['money']},理由是{$row['item']},付款单位{$row['pay_company']},会员ID{$row['id']}"
				                                             )) . ";";
				$temp        = round(floatval($row['balance']) - floatval($row['frozen_balance']) - floatval($row['money']), 2);
				$sms_content = "您的账户在" . date("Y-m-d H:i:s") . "消费了{$row['money']}元，现在的余额为" . $temp . "。";
//				Log::write($send_status);
				if ($r1 && $r2) {
					M()->commit();
					$total_money += $row['money'];
					$send_status = $sms->send($row['mobile'], $sms_content);
					$total_count++;
				} else {
					$wrong_data[] = $row;
					M()->rollback();
				}
				unset($result[ $key ]);
				if ($_temp > 100) {
					break;
				}
			}
//			dump($sms->send_multi());
			session("import_consume", $result);
			session("wrong_data", $wrong_data);
			session("total_money", $total_money);
			session("total_count", $total_count);
			Log::record("出错数据:" . print_r($wrong_data, true), Log::ERR);
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
					                  "count"  => count($result)
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
			$info        = $Upload->upload();
			$total_money = 0;
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
				$card_nums    = array ();
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
					$card_nums[]        = $row['A'];
					$lists[ $row['A'] ] = array (
						"card_num"    => $row['A'],
						"money"       => $row['B'],
						"item"        => $row['C'],
						"pay_company" => $row['D'],
						"type"        => $row['E']
					
					);
					$total_money += floatval($row['B']);
				}
				
				$result = $db->field("id,realname,sex,card_num,company,id_num,dept,position,balance,mobile")
				             ->where(array ('card_num' => array ("in", $card_nums)))->select();
				foreach ($result as &$member) {
					$key = array_search($member['card_num'], $card_nums);
					array_splice($card_nums, $key, 1);
					
					if (!empty($lists[ $member['card_num'] ]) && !empty($member)) {
						$member = array_merge($member, $lists[ $member['card_num'] ]);
					}
				}
				//提示出来卡号出问题的人.
				if (count($result) != count($lists)) {
					$this->error("发现卡号为: " . implode(",", $card_nums) . " 的用户在系统中不存在,请检查. ");
					return;
				}
				
				
				if ($count_import != count($lists)) {
					$this->error("导入的数据条数和最终确认条数不一致,请检查!(同会员一张Excel不得扣除两次)");
					return;
				}
				
				
				if ($result) {
					session("import_consume", $result);
					session("import_consume_backUp", $result);
					$this->assign("result", $result);
					$this->assign("total_money", $total_money);
					$this->assign("list_amount", count($result));
					$this->assign("meta_title", "数据确认");
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
		$import_consume_backUp = session("import_consume_backUp");
		$wrong_data            = session("wrong_data");
		$this->assign("total_count", session("total_count"));
		$this->assign("total_money", session("total_money"));
		
		session("import_consume_backUp", null);
		session("wrong_data", null);
		session("import_consume", null);
		session("wrong_data", null);
		session("total_money", null);
		session("total_count", null);
		
		
		$this->assign("result", $import_consume_backUp);
		$this->assign("wrong_data", $wrong_data);
		$this->display();
	}
	
}