<?php
namespace Report\Controller;

use Think\Controller;
use Think\Log;

class IndexController extends Controller {
	public function index() {
		set_time_limit(99999999);
		session_write_close();
		define("REPORT_PATH", "E:/HealthTest/");
		
		
		$dir       = REPORT_PATH;
		$report_db = M('report');
		//1.读出8个星期内创建/修改的目录,保存到数据库中,标记出修改未处理的目录
		$dirs = array ();
		if (is_dir($dir)) {
			Log::write("读取根目录成功.", Log::DEBUG);
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if ((is_dir($dir . "/" . $file)) && $file != "." && $file != ".." && stripos($file, ".") !== 0 && stripos($file, "$") !== 0 && stripos($file, "~") !== 0) {
						Log::write("读到" . $file, Log::DEBUG);
						$mtime = filemtime($dir . $file);
						$now   = time();
						
						if ($mtime + 3600 * 24 * 60 < $now) {//Two month ago
							continue;
						}
						$dirs[] = array (
							"name"  => $file,
							"mtime" => $mtime,
							"path"  => $dir . $file,
							"diff"  => $now - $mtime + 3600 * 24 * 60
						);
					}
				}
				closedir($dh);
			} else {
				Log::write("打开目录失败" . $dh);
			}
			if (!$dirs) {
				Log::write("未发现报告");
				return;
			}
			$dirs_in_db = $report_db->where(array (
				                                'name'      => array ("IN", array_column($dirs, "name")),
				                                "is_report" => 0
			                                ))->getField("name,mtime");
			$count      = count($dirs);
			for ($i = 0; $i < $count; $i++) {
				if (!isset($dirs_in_db[ $dirs[ $i ]['name'] ])) {
					$report_db->add($dirs[ $i ]);
				}
			}
			
			//dump($dirs_in_db);
			//dump($dirs);exit;
			/*
			for ($i = 0; $i < $count; $i++) {
				if (isset($dirs_in_db[ $dirs[ $i ]['name'] ])) {
					if ($dirs_in_db[ $dirs[ $i ]['name'] ] == $dirs[ $i ]['mtime']) {
						unset($dirs[ $i ]);
						continue;
					} else {
						$report_db->where("name='%s'", $dirs[ $i ]['name'])->save($dirs[ $i ]);
					}
				} else {
					$report_db->add($dirs[ $i ]);
				}
			}
			*/
			
			//	foreach ($dirs as $key => $_dir) {
			//		if (isset($dirs_in_db[ $_dir['name'] ])) {
			//			if ($dirs_in_db[ $_dir['name'] ]['mtime'] == $_dir['mtime']) {
			//				unset($dirs[ $key ]);
			//			} else {
			//				$report_db->where("name='%s'", $_dir['name'])->save($_dir);
			//			}
			//		} else {
			//			$report_db->add($_dir);
			//		}
			//	}
			
			//此时的 $dirs 为要处理的 dir
			Log::write("要处理的dir:" . print_r($dirs, true));
			
			
			//处理其中的报告
			$reports = array ();
			foreach ($dirs as $_dir) {
				$_dir = $dir . $_dir['name'];
				if (is_dir($_dir)) {
					if ($dh = opendir($_dir)) {
						while (($file = readdir($dh)) !== false) {
							$file=iconv("UTF-8","gb2312",$file);
							if ((is_file($_dir . "/" . $file)) && $file != "." && $file != ".." && stripos($file, ".") !== 0 && stripos($file, "$") !== 0 && stripos($file, "~") !== 0 && stripos($file, "tjjg") === 0) {
								$file_name = explode("_", $file);
								$reports[] = array (
									"name"      => $file,
									"mtime"     => filectime($_dir . "/" . $file),
									"path"      => $_dir . "/" . $file,
									'is_report' => 1,
									"id_num"    => isset($file_name[2]) ? $file_name[2] : "",
									"order_no"  => isset($file_name[1]) ? $file_name[1] : "",
								);
								$save      = array ();
								$_id_num   = isset($file_name[2]) ? $file_name[2] : "";
								$_tel      = isset($file_name[4]) ? $file_name[4] : "";
								if ($_tel) {
									$save['mobile'] = $_tel;
									Log::write("{$_id_num}-修改了手机号-" . $_tel, Log::DEBUG);
									
								}
								$_rank = isset($file_name[5]) ? $file_name[5] : "";
								switch ($_rank) {
									case 1:
										$_rank = 'Ⅰ';
										break;
									case 2:
										$_rank = 'Ⅱ';
										break;
									case 3:
										$_rank = 'Ⅲ';
										break;
									case 4:
										$_rank = 'Ⅳ';
										break;
									case 5:
										$_rank = 'Ⅴ';
										break;
									case 6:
										$_rank = 'Ⅵ';
										break;
									case 7:
										$_rank = 'Ⅶ';
										break;
									default :
										$_rank = '';
										break;
								}
								$save['level'] = $_rank;
								$_married      = isset($file_name[6]) ? $file_name[6] : "";
								switch ($_married) {
									case 1:
										$_married = '有';
										break;
									case 2:
										$_married = '无';
										break;
									default :
										$_married = '不确定';
										break;
								}
								$save['married'] = $_married;
								
								M("customer")->where("id='%s'", $_id_num)->save($save);
							}
						}
						closedir($dh);
					}
				}
				
				Log::write("读到的报告:" . count($reports));
				//dump(array_column($reports, "name"));exit;
				//处理报告
				if (count($reports)==0) {
					continue;
				}
				$reports_in_db = $report_db->cache(false)->where(array (
					                                                 'name'      => array ("IN", array_column($reports, "name")),
					                                                 "is_report" => 1
				                                                 ))->getField("name,mtime");
				
				foreach ($reports as $key => $report) {
					if (isset($reports_in_db[ $report['name'] ])) {
						if ($reports_in_db[ $report['name'] ] == $report['mtime']) {
							unset($reports[ $key ]);
						} else {
							$report_db->where("name='%s'", $report['name'])->save($report);
						}
					} else {

						Log::write("保存新报告:" . print_r($report,true));
						$report_db->add($report);
					}
				}
			}
		}
		
	}
}