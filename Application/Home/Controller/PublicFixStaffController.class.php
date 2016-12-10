<?php
namespace Home\Controller;

use Think\Controller;

class PublicFixStaffController extends Controller {

	public function index() {
		$p  = I("p", 1);
		$db = M('consume_log');
		import("Org.Page");
		$last100_remark = session('last100_remark');
		$wrong          = session("wrong");
		$page           = new \Page($db->count(), 1000);
		$data           = $db->limit($page->firstRow, $page->listRows)->where("type='商城'")->select();
		if ($p < $page->totalPages) {
			$new_last = array ();
			foreach ($data as $row) {
				if (in_array($row['remark'], $last100_remark)) {
					$wrong[] = $row;
				}
				$new_last[] = $row['remark'];
			}
			session('last100_remark', $new_last);
			session('wrong', $wrong);
			redirect(U("index", array ('p' => ++$p)));
		} else {
			dump($wrong);
			session(null);
		}
	}

	public function clear() {
		session(null);
	}
}
