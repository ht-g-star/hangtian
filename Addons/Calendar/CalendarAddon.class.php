<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace Addons\Calendar;

use Common\Controller\Addon;

/**
 * 编辑器插件
 *
 */
class CalendarAddon extends Addon {

	public $info
		= array (
			'name'        => 'Calendar',
			'title'       => '日历',
			'description' => '日历',
			'status'      => 1,
			'author'      => 'Todd',
			'version'     => '0.1'
		);

	public function install() {
		return true;
	}

	public function uninstall() {
		return true;
	}

	/**
	 * 编辑器挂载的文章内容钩子
	 * @param array ('name'=>'表单name','value'=>'表单对应的值')
	 */
	public function Calendar($data) {
		$year    = $data['year'];
		$mon     = $data['mon'];
		$fday    = $data['fday'];
		$period  = $data['period'];
		$_period = array ();
		foreach ($period as $p) {
			$_period[] = strtotime($p['period']);
		}
		
		if (in_array($mon, array (1, 3, 5, 7, 8, 10, 12))) {
			$i   = 0;
			$max = 31;
			while ($i < $fday) {
				echo "<td>&nbsp;</td>";
				$i++;
			}
			for (; $i - $fday + 1 <= $max; $i++) {
				if ($i % 7 == 0) {
					echo "</tr><tr>";
				}
				if (in_array(strtotime($year . "-" . $mon . "-" . ($i - $fday + 1)), $_period)) {
					echo "<td class='am-disabled'>" . ($i - $fday + 1) . "</td>";
				} else {
					echo "<td>" . ($i - $fday + 1) . "</td>";
				}

			}
			while (true) {
				if ($i % 7 == 0) {
					break;
				}
				echo "<td>&nbsp;</td>";
				$i++;
			}

		} else if (in_array($mon, array (4, 6, 9, 11))) {
			$i   = 0;
			$max = 30;
			while ($i < $fday) {
				echo "<td>&nbsp;</td>";
				$i++;
			}
			for (; $i - $fday + 1 <= $max; $i++) {
				if ($i % 7 == 0) {
					echo "</tr><tr>";
				}
				if (in_array(strtotime($year . "-" . $mon . "-" . ($i - $fday + 1)), $_period)) {
					echo "<td class='am-disabled'>" . ($i - $fday + 1) . "</td>";
				} else {
					echo "<td>" . ($i - $fday + 1) . "</td>";
				}

			}
			while (true) {
				if ($i % 7 == 0) {
					break;
				}
				echo "<td>&nbsp;</td>";
				$i++;
			}
		} else {
			$i   = 0;
			$max = 29;
			while ($i < $fday) {
				echo "<td>&nbsp;</td>";
				$i++;
			}
			for (; $i - $fday + 1 <= $max; $i++) {
				if ($i % 7 == 0) {
					echo "</tr><tr>";
				}
				if (in_array(strtotime($year . "-" . $mon . "-" . ($i - $fday + 1)), $_period)) {
					echo "<td class='am-disabled'>" . ($i - $fday + 1) . "</td>";
				} else {
					echo "<td>" . ($i - $fday + 1) . "</td>";
				}

			}
			while (true) {
				if ($i % 7 == 0) {
					break;
				}
				echo "<td>&nbsp;</td>";
				$i++;
			}
		}


		$this->display('content');
	}


}
