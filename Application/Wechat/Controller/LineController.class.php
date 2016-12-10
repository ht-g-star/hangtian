<?php
/**
 * 度假套餐
 */
namespace Wechat\Controller;
use Home\Controller\HomeController;
use Org\Wechat\TPWechat;
use Org\Wechat\Wechat;
use Think\Log;

class LineController extends AuthController {


	/**
	 * 度假线路
	 */
	public function index() {
		$this->meta_title = '旅游线路预订';
		$url="http://www.myvacation.cn/wuxi/api.php?Action=GetLinelist";
		$data=$_GET;
		$data['type'];
		if($data['type']){
			$url=$url.'&type='.$data['type'];
		}else{
			$url=$url.'&type=1';
		}
		if($data['xl_type']){
			$url=$url.'&xl_type='.$data['xl_type'];
		}else{
			$url=$url.'&xl_type=2';
		}
		if($data['city_id']){
			$url=$url.'&city_id='.$data['city_id'];
			$this->assign('cityid',$data['city_id']);
		}else{
			//$url=$url.'&city_id=';
		}
		if($data['section_id']){
			$url=$url.'&section_id='.$data['section_id'];
			$this->assign('sectionid',$data['section_id']);
		}else{
			//$url=$url.'&section_id=';
		}
		if($data['cfyf']){
			$url=$url.'&cfyf='.$data['cfyf'];
			$this->assign('datetime',$data['cfyf']);
		}
		if($data['keywords']){
			$url=$url.'&keywords='.$data['keywords'];
			$this->assign('keywords',$data['keywords']);
		}
		$linelist=$this->getJsonByUrl($url.'&show_count=0');
		
		Log::write("旅游线路预订数据：".json_encode($linelist));

		$count=$this->getJsonByUrl($url.'&show_count=1');
		$url2="http://www.myvacation.cn/wuxi/api.php?Action=GetLineSections&type=1&xl_type=2";
		$cache = $this->getJsonByUrl($url2);
		if(!$cache['result']){
			//无结果
			//返回错误信息
		}else{
//			foreach($cache as $k=>$v){
//				$url1="http://www.myvacation.cn/wuxi/api.php?Action=GetLineDetail&type=1&xl_id=".$v['xl_id'];
//				$cache['xl_info']=$this->getJsonByUrl($url1);
//			}
			unset($linelist['result']);
			unset($linelist['msg']);
			unset($cache['result']);
			unset($cache['msg']);
			$linecity = 0;
			foreach($cache as $k=>$v){
				$linecity+=$v['section_num'];
				foreach($v['city'] as $kk=>$vv){
					$urlaa = "http://www.myvacation.cn/wuxi/api.php?Action=GetLineDateShifts&type=1&xl_type=2&city_id=".$vv['city_id']."&section_id=".$v['section_id']."&keywords=";
					$cfdate = $this->getJsonByUrl($urlaa);
				}
			}
			unset($cfdate['result']);
			unset($cfdate['msg']);
			$this->assign('linecity',$linecity);
			$this->assign('linelist',$linelist);
			$this->assign('cache',$cache);
			$cfsj = array();
			foreach($cfdate as $k=>$v){
				if('出团日期不限'!=$v['cfrq']){
					$cfsj[$k]['cfrqstr']=substr($v['cfrq'],0,4).'/'.substr($v['cfrq'],4,2);
					$cfsj[$k]['cfrq'] = $v['cfrq'];
				}
			}
			$this->assign('cfsj',$cfsj);
		}
		$this->display();
		
		// $url="http://www.myvacation.cn/wuxi/api.php?Action=GetLinelist&type=1&xl_type=2";
		// $linelist=$this->getJsonByUrl($url.'&show_count=0');
	}
	
	public function detail(){
		$this->meta_title = '旅游线路预订';
		$url = "http://www.myvacation.cn/wuxi/api.php?Action=GetLineDetail&type=1";
		$data=$_GET;
		if($data['xl_id']){
			$url=$url.'&xl_id='.$data['xl_id'];
			$this->assign('lineid',$data['xl_id']);
		}
		$line=$this->getJsonByUrl($url);
		if(!$line['result']){
			//无结果
			//返回错误信息
		}else{
			unset($line['result']);
			unset($line['msg']);
			if($line['xl_biaoqian']){
				$biaoqin =array_filter(explode(',', $line['xl_biaoqian']));
				$this->assign('biaoqin',$biaoqin);
			}
			$this->assign('line',$line);
			
			$url1 = "http://www.myvacation.cn/m/members/action/get_xl_jiage_string_wuxi.php?xl_id=".$data['xl_id'];
			$lineinfo=$this->getJsonByUrl1($url1);
			$lineinfo =array_filter(explode(';', $lineinfo));
			foreach($lineinfo as $k=>$v){
				$lineinfo[$k] = array_filter(explode('|', $v));
			}
			$this->assign('lineinfocount',count($lineinfo));
			$this->assign('lineinfo',$lineinfo);
		}
		$this->display();
	}

	public function order(){
		$this->meta_title = '订单结算';
		if(IS_POST){
			//todo 验证
			$data=I('post.');
			$data['uid']=$_SESSION['onethink_home']['user_auth']['id'];
			$data['create_time']=time();
			$data['cellphone']=$data['phone'];
			if($data['status']==1){
				//修改其他地址为非默认
				M('address')->where('uid='.$data['uid'])->setField('status',0);
			}
			$result=M('address')->add($data);
			if($result){
				$info['id']=$result;
				$info['msg']='&nbsp;联系人：'.$data['realname'].'&nbsp;&nbsp;手机号:'.$data['phone'].'&nbsp;&nbsp;';
				$info['status']=1;
			}else{
				$this->error('操作失败,请重试!');
			}
			$this->ajaxReturn($info);
		}else{
			$url = "http://www.myvacation.cn/wuxi/api.php?Action=GetLineDetail&type=1";
			$data=$_GET;
			$uid= is_login();
			if (!$uid) {
				//cookie('__forward__', U('order', array('id'=>$data['id'],'cfrq'=>$data['cfrq'],'num'=>$data['num'])));
				cookie('__forward__', U('order', array('xlid'=>$data['xlid'],'cfdate'=>$data['cfdate'],'adult'=>$data['adult'],'child'=>$data['child'],'num'=>$data['num'])));
				$this->error("您还没有登陆", U("User/login"));
			}
			$cfdatearr =array_filter(explode('-', $data['cfdate']));
			$cfdate = $cfdatearr[0].'.'.$cfdatearr[1].'.'.$cfdatearr[2];
			$this->assign('cfdate',$cfdate);
			$this->assign('adult',$data['adult']);
			$this->assign('child',$data['child']);
			$url=$url.'&xl_id='.$data['xlid'];
			$line=$this->getJsonByUrl($url);
			$money = $data['price']*($data['adult']+$data['child']);
			$this->assign('money',$money);
			$addreses = M("address")->where("uid=%d and status>=0", $uid)->select();
			$this->assign("default_id", M("customer")->where("id=%d", $uid)->getField("address_id"));
			$this->assign('address', $addreses);
			if(!$line['result']){
				//无结果
				//返回错误信息
			}else{
				unset($line['result']);
				unset($line['msg']);
				$this->assign('line',$line);
			}
			$this->display();
		}
	}
	/**
	 * 创建订单
	 */
	public function orderMake(){
		//避免多次调用验证
		$uid= is_login();
		if (!$uid) {
			$this->error("您还没有登陆", U("User/login"));
		}
		$data=I('post.');
		//验证
//		num:"{:I('num')}",
//		addressid:$('#default').val(),
//		message:$('#message').val(),
//		rzsj:"{:I('cfrq')}",
//		jdid:"{:I('id')}"
		if(!$data['num']){
			$info['status']=0;
			$info['msg']='商品数量不正确!';
			$this->ajaxReturn($info);
		}
		if(!$data['addressid']){
			$info['status']=0;
			$info['msg']='未填写地址信息!';
			$this->ajaxReturn($info);
		}
		if(!$data['rzsj']){
			$info['status']=0;
			$info['msg']='请选择出发时间!';
			$this->ajaxReturn($info);
		}
		if(!$data['xl_id']){
			$info['status']=0;
			$info['msg']='商品有误,请选择其他商品!';
			$this->ajaxReturn($info);
		}
		//获取商品信息
		$url="http://www.myvacation.cn/wuxi/api.php?Action=GetLineDetail&type=1&xl_id=".$data['xl_id'];
		$goods=$this->getJsonByUrl($url);
		if(!$goods){
			$info['status']=0;
			$info['msg']='商品有误,请选择其他商品!';
			$this->ajaxReturn($info);
		}

		$address = M('Address')->where('id='.$data['addressid'])->find();

		$order['orderid'] = date('Ym', time()) . time() . $uid;//订单号
		$order['tag']=$order['orderid'];
		$order['total_money']=$data['price'];
		$order['create_time']=time();
		$order['status']=1;
		$order['update_time']=0;
		$order['uid']=$uid;
		$order['ispay']=1;
		$order['total']=$order['total_money'];
		$order['addressid']=$data['addressid'];
		$order['cxrname']=$address['realname'];
		$order['cxrphone']=$address['cellphone'];
		$order['message']=$data['message'];
		$order['ordertype']=3;
		$order['cftime']=str_replace("-", "", $data['rzsj']);
		$order['goodsname']=$goods['xl_code'].'-'.$goods['xl_name'];
		$order['goodspic']=$goods['xl_list_img'];
		$order["crj_num"] = $data["crj_num"];
		$order["rtj_num"] = $data["rtj_num"];
		$result=M('order')->add($order);
		$goodsdata['goodid']=$goods['xl_id'];
		$goodsdata['num']=$data['num'];
		$goodsdata['orderid']=$result;
		$goodsdata['uid']=$uid;
		$goodsdata['status']=1;
		$goodsdata['create_time']=time();
		$goodsdata['price']=$data['qprice'];
		$goodsdata['total']=$data['price'];
		$goodsdata['tag']=$order['orderid'];
		$goodsdata['sname']=$goods['xl_name'];
		$goodsdata['spic']=$goods['xl_list_img'];
		$goodsdata['sid']=$goods['xl_id'];
		$goodsdata['priceid']=$data['priceid'];
		$goodsdata['crnum']=$data['crj_num'];
		$goodsdata['rtnum']=$data['rtj_num'];
		$goodsdata['stype']=3;
		M('shoplist')->add($goodsdata);
		if($result){
			$this->ts($order['orderid']);
			$info['status']=1;
			$info['orderid']=$order['orderid'];
			$info['msg']='订单创建成功,请等待网站审核后付款!';
			$info['sss']=$order;
		}else{
			$info['status']=0;
			$info['msg']='订单生成失败,请重试!';
		}
		$this->ajaxReturn($info);
	}
	

	//修改地址
	public function addset(){
		if(IS_POST){
			$data=I('post.');
//			dump($data);	
			$add=M('address')->where(array('id'=>$data['addid']))->find();
			if(!$add){
				$info['status']=0;
				$info['msg']='未找到地址!';
				$this->ajaxReturn($info);
			}else{
				$info['status']=1;
				$info['msg']='找到了！';
				$info['add']=$add;
				$this->ajaxReturn($info);
			}			
		}else{
			die('no access!');
		}
	}
	
	//保存地址
	public function addsave(){
		if(IS_POST){
			$data=I('post.');
//			dump($data);	
			$add=M('address')->where(array('id'=>$data['id']))->find();
			if(!$add){
				$info['status']=0;
				$info['msg']='未找到地址!';
				$this->ajaxReturn($info);
			}else{
				$re=M('address')->save($data);
				if($re!==FALSE){
					$info['status']=1;
					$info['msg']='修改成功！';
					
				}else{
					$info['status']=0;
					$info['msg']='修改失败，请重试！';
				}				
				$this->ajaxReturn($info);
			}			
		}else{
			die('no access!');
		}
	}
	/**
	 * 线路和酒店订单推送
	 */
	private function ts($orderid){
		$order=M('Order')->where(array('orderid'=>$orderid))->find();
		if($order['ordertype']!=1){
			$goods=M('shoplist')->where(array('tag'=>$order['tag']))->find();
			$address=M('address')->where(array('id'=>$order['addressid']))->find();
			if($goods['stype'] == 2){
				$crnum=$goods['num'];
				$rtnum=0;
			}
			if($goods['stype'] == 3){
				$crnum=$goods['crnum'];
				$rtnum=$goods['rtnum'];
			}
			$url = "http://www.myvacation.cn/wuxi/api.php?Action=OrderPost".
			"&xl_id=".$goods['sid'].
			"&jg_id=".$goods['priceid'].
			"&cfrq=".$order['cftime'].
			"&xlmc=".urlencode($goods['sname']).
			"&crj_num=".$crnum.
			"&rtj_num=".$rtnum.
			"&tssm=".urlencode($order['message']).
			"&order_zje=".floatval($order['total_money']).
			"&order_id_wx=".$order['orderid'].
			"&lxr=".urlencode($address['realname']).
			"&mobile=".$address['cellphone'];
			$zfre=$this->getJsonByUrl($url);
			Log::write("线路提交订单请求：".$url);
			Log::write("线路提交订单返回数据：".json_encode($zfre));
			if($zfre['result'] == 1){
				$ore = M('Order')->where(array('orderid'=>$orderid))->setField('ists',1);
				$ore1 = M('Order')->where(array('orderid'=>$orderid))->setField('tsorderid',"XZ0".$zfre['order_id']);
			}
		}
	}
}
