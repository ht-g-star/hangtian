<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 1010422715@qq.com All rights reserved.
// +----------------------------------------------------------------------
// | author 烟消云散 <1010422715@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Model\FcouponModel;

/**
 * 后台订单控制器
 *
 */
class FcouponController extends AdminController {

	/**
	 * 订单管理
	 * author 烟消云散 <1010422715@qq.com>
	 */
	public function index() {

		$new=new FcouponModel();
		$new->getCoupons(3959);

		/* 查询条件初始化 */

		$map  = array ('status' => 1);
		$list = $this->lists('fcoupon', $map, 'id');

		$this->assign('list', $list);
		// 记录当前列表页的cookie
		Cookie('__forward__', $_SERVER['REQUEST_URI']);

		$this->meta_title = '优惠券管理';
		$this->display();
	}

	/* 编辑分类 */
	public function edit($id = null, $pid = 0) {
		$fcoupon = D('Fcoupon');

	
		if (IS_POST) { //提交表单
			if (false !== $fcoupon->update()) {


				$this->success('编辑成功！', U('index'));
			} else {
				$error = $fcoupon->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$cate = '';
			if ($pid) {
				/* 获取上级分类信息 */
				$cate = $fcoupon->info($pid, 'id,name,title,status');
				if (!($cate && 1 == $cate['status'])) {
					$this->error('指定的上级分类不存在或被禁用！');
				}
			}

			/* 获取分类信息 */
			$info = $id ? $fcoupon->info($id) : '';

			// echo '<pre>';
			// var_dump($info);
			// echo '</pre>';

			$this->assign('info', $info);
			$this->assign('category', $cate);
			$this->meta_title = '编辑优惠券';
			$this->display();
		}
	}

	public function add() {
		$fcoupon = D('Fcoupon');


		if (IS_POST) { //提交表单

			if (false !== $fcoupon->update()) {
				$this->success('新增成功！', U('index'));
			} else {
				$error = $fcoupon->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$this->meta_title = '新增优惠券';
			$this->display('edit');
		}
	}

	function makecode() {
		//新时间截定义,基于世界未日2012-12-21的时间戳。
		$endtime = 1356019200;//2012-12-21时间戳
		$curtime = time();//当前时间戳
		$newtime = $curtime - $endtime;//新时间戳
		$rand    = rand(10, 9999);//两位随机
		$all     = $rand . $newtime;
		$onlyid  = base_convert($all, 10, 36);//把10进制转为36进制的唯一ID
		if ($onlyid) {
			$data['status'] = "1";
			$data['code']   = $onlyid;
			$data['info']   = '获取成功';
			$this->ajaxReturn($data);

		} else {
			$data['status'] = "1";
			$data['info']   = '获取失败';


		}
	}

	public function del() {
		if (IS_POST) {
			$ids   = I('post.id');
			$order = M("fcoupon");
			
			if (is_array($ids)) {
				$order->where(array ('id' => array ('in', $ids)))->delete();
			}
			addUserLog("删除优惠券", is_login());
			$this->success("删除成功！");
		} else {
			$id     = I('get.id');
			$db     = M("fcoupon");
			$status = $db->where("id='$id'")->delete();
			if ($status) {
				addUserLog("删除优惠券", is_login());
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}

}