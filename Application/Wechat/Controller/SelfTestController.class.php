<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Wechat\Controller;

use Common\Model\SelfTestModel;
use Common\Model\SuiteModel;
use Think\Controller;

/**
 */
class SelfTestController extends FollowerController {
	
	public function _initialize() {
		parent::_initialize();
	}
	
	public function index() {
		$this->assign("meta_title", "健康自测");
		if (IS_POST) {
			$data  = I("post.");
			$sex   = $data['sex'];
			$age   = $data['age'];
			$model = new SuiteModel();
			if ($data['q10'] || $data['q11']) {
//				糖尿病套餐
				$data = $model->findByTitle("糖尿病");
			} else if ($data['q6'] || $data['q7']) {
//					高血压套餐
				$data = $model->findByTitle("高血压");
			} else if ($age > 55) {
//					关爱套餐
				$data = $model->findByTitle("关爱");
			} else if ($data['q4'] || $data['q5']) {
//					关爱套餐
				$data = $model->findByTitle("关爱");

			} else if ($data['q4'] || $data['q5']) {
				$data = $model->findByTitle("电脑族");
//					电脑族套餐
			} else if ($data['q1'] || $data['q2'] || $data['q3']) {
				$data = $model->findByTitle("亚健康");

//					亚健康套餐
			} else if ($data['q8'] && $data['q9']) {
				$data = $model->findByTitle("高血压");

				//高血压套餐
			} else if ($data['q12'] && $data['q13']) {
				$data = $model->findByTitle("糖尿病");
				//糖尿病套
			} else if ($data['q8'] || $data['q9'] || $data['q12'] || $data['q13']) {
				//亚健康套餐
				$data = $model->findByTitle("亚健康");
			} else {
				$data = $model->findByTitle("A");
			}
			$this->assign("data", $data);
			$this->display("result");
		} else {
			$this->display();
		}
	}
	
	
}
