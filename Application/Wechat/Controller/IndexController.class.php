<?php
namespace Wechat\Controller;

use Org\Wechat\TPWechat;
use Org\Wechat\Wechat;
use Think\Controller;
use Think\Log;

class IndexController extends Controller {
	private $wx;

	public function _initialize() {
		/* 读取数据库中的配置 */
		$config = S('DB_CONFIG_DATA');
		if (!$config) {
			$config = api('Config/lists');
			S('DB_CONFIG_DATA', $config);
		}
		C($config); //添加配置
		$option   = array (
			'token'     => C("WX_TOKEN"), //填写你设定的key
			'appid'     => C("WX_APPID"), //填写高级调用功能的app id
			'appsecret' => C('WX_APPSCRETE'), //填写高级调用功能的密钥
			"debug"     => false, "logcallback" => 'log_wechat'
		);
		$this->wx = new  TPWechat($option);
		$this->wx->valid();
	}

	public function index() {

		$type = $this->wx->getRev()->getRevType();
		switch ($type) {
			case Wechat::MSGTYPE_EVENT:
				$this->event();
				break;

			case Wechat::MSGTYPE_TEXT:
				$this->text();
				exit;
				break;
			case Wechat::MSGTYPE_IMAGE:
			case Wechat::MSGTYPE_MUSIC:
			case Wechat::MSGTYPE_VIDEO:
			case Wechat::MSGTYPE_VOICE:
				$this->media();
				break;

			case
			Wechat::MSGTYPE_LOCATION:
				$this->location();
				break;
			case Wechat::MSGTYPE_LINK:
				$this->link();
				break;
			case Wechat::MSGTYPE_NEWS:
				$this->news();
				break;
			default:
				$this->wx->text("不能处理您的请求.")->reply();
		}
	}

	public function event() {
		$type  = $this->wx->getRevEvent();
		$event = strtolower($type['event']);
		$this->$event();

	}

	public function subscribe() {

		$this->wx->text(C("WX_SUB_REPLY"))->reply();
	}

	public function unsubscribe() {

	}

	public function scan() {

	}


	public function view() {

	}

	public function __call($name, $arguments) {
		Log::write("执行未知方法{$name}" . print_r($arguments, true));
		exit("");
	}

	public function click() {
		$key = $this->wx->getRevEvent();
		$key = $key['key'];
		$this->reply($key);
	}

	public function text() {
		$content = trim($this->wx->getRevContent());
		$this->reply($content);
	}

	public function reply($keyword) {
		$keyword = strtolower($keyword);
		if ($keyword == 'getopenid') {
			$this->wx->text($this->wx->getRevFrom())->reply();
		}

		$data = M("wx_reply")->where(array ("keyword" => $keyword))->order("sort desc,id desc")->select();
		if ($data) {
			if ($data[0]['type'] == 1) {//文本回复
				$this->wx->text($data[0]['text'])->reply();
				Log::write(date("Y-m-d H:i:s") . print_r($data[0], true));
			}
			if ($data[0]['type'] == 2) {
				$return = array ();
				foreach ($data as $news) {
					$temp     = array (
						'Title'       => $news['title'],
						'Description' => $news['text'],
						'PicUrl'      => C('site_url') . "/" . $news['pic'],
						'Url'         => $news['link']
					);
					$return[] = $temp;
				}
			}
//			Log::write(date("Y-m-d H:i:s").print_r($return,true));
			$this->wx->news($return)->reply();
//		    if ($content == "会员") {
//			    $url = U('Wap/User/index', array(), true, true);
//			    $this->wx->text('<a  href="' . $url . '">个人中心</a>')->reply();
//		    }
		} else {
			//TODO
			$res = $this->wx->transfer_customer_service()->reply();
			Log::write("调用客服" . print_r($res, true));
		}
	}

	public function media() {
		$res = $this->wx->transfer_customer_service()->reply();
		Log::write("调用客服" . print_r($res, true));
	}

	public function location() {
		//
		/*$location      = $this->wx->getRevGeo();
		$lat           = $location['x'];
		$lon           = $location['y'];
//		$company_model = M('Company');
		$companies     = $company_model->query(
			"	SELECT	* FROM COMPANY
				WHERE
					LAT > {$lat} - 1
				AND LAT < {$lat} + 1
				AND LNG > {$lon} - 1
				AND LNG < {$lon} + 1
				AND STATUS =1
				AND IS_SHOW=1
				AND ROWNUM <= 8
				ORDER BY
					ACOS(
						SIN(({$lat} * 3.1415) / 180) * SIN((LAT * 3.1415) / 180) + COS(({$lat} * 3.1415) / 180) * COS((lat * 3.1415) / 180) * COS(
							({$lon} * 3.1415) / 180 - (LNG * 3.1415) / 180
						)
					) * 6380 ASC
			");
//        Log::write(print_r($companies,true));
		if ($companies) {
			foreach ($companies as $c) {
				$c = array_change_key_case($c, CASE_LOWER);
				if (!empty($c['linkurl'])) {
					$return[] = array (
						'Title'       => $c['shorttitle'],
						'Description' => $c['address'],
						'PicUrl'      => C('site_url') . "/" . $c['logourl'],
						'Url'         => str_replace('{openid}', $this->wx->getRevFrom(), $c['linkurl'])
					);
				} else {
					$return[] = array (
						'Title'       => $c['shorttitle'],
						'Description' => $c['address'],
						'PicUrl'      => C('site_url') . "/" . $c['logourl'],
						'Url'         => "http://l.map.qq.com/11365807228?m"
					);
				}
			}
			$this->wx->news($return)->reply();
		} else {
			$this->wx->text('附近没有找到信息哦~')->reply();;
		}
		*/
		$return[] = array (
			'Title'       => "航天无锡疗养院",
			'Description' => '地址:无锡市滨湖区
电话:0510-5552738 0510-5555738',
			'PicUrl'      => "http://apis.map.qq.com/ws/streetview/v1/image?size=600x480&pano=17031010130104102006800&heading=218&pitch=6&key=V4CBZ-3WZHR-ECDWC-W3OVB-CZXO5-3VFOO",
			'Url'         => "http://l.map.qq.com/11365807228?m"
		);
		$this->wx->news($return)->reply();
	}

	public function link() {

	}

	public function news() {

	}

}



