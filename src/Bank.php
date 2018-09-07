<?php
/**
 * author: yutao
 * createTime: 2018/9/7 下午6:03
 * description:
 */
namespace yutao8\BankRegion;

use QL\QueryList;
use Symfony\Component\DomCrawler\Crawler;

class Bank{
	function cate(){
		$url = 'http://www.lianhanghao.com/';
		$html = Helper::http_get_cache($url);
		$rules = array(
			'id' => array('#bank option','value'),
			'title' => array('#bank option','text'),
		);
		$data = QueryList::html($html)->rules($rules)->query()->getData()->all();
		array_shift($data);
		return $data;
	}

	function all($bank_id,$province_id,$city_id,$keyword = ''){
		print_r('           第1页' . PHP_EOL);
		$url = 'http://www.lianhanghao.com/index.php?bank=' . $bank_id . '&province=' . $province_id . '&city=' . $city_id . '&key=' . $keyword . '&p=1';
		$html = Helper::http_get_cache($url);
		$crawler = new Crawler();
		$crawler->addHtmlContent($html);
		try{
			$page_data = $crawler->filter('a.num')->each(function(Crawler $node,$i){
				return $node->text();
			});
		}catch(\Exception $e){
		}
		if($page_data){
			foreach($page_data as $page_id){
				if($page_id){
					print_r('           第' . $page_id . '页' . PHP_EOL);
					$data[$page_id] = $this->lists($bank_id,$province_id,$city_id,$keyword,$page_id);
				}else{
					print_r($bank_id . '-' . $province_id . '-' . $city_id . PHP_EOL);
				}
			}
		}
		$data[1] = $this->lists($bank_id,$province_id,$city_id,$keyword,1);
		return $data;
	}

	function lists($bank_id,$province_id,$city_id,$keyword = '',$page_id = 1){
		$url = 'http://www.lianhanghao.com/index.php?bank=' . $bank_id . '&province=' . $province_id . '&city=' . $city_id . '&key=' . $keyword . '&p=' . $page_id;
		$ql = QueryList::html(Helper::http_get_cache($url));
		$rules = array(
			'code' => array('td:eq(0)','html'),
			'title' => array('td:eq(1)','html'),
			'tel' => array('td:eq(2)','html'),
			'address' => array('td:eq(3)','html','',function($content){
				return str_replace('<a href="http://www.82029.com/" target="_blank"><font color="#FF0000">淘宝网限时优惠券--抢抢抢</font></a>','',$content);
			}),
		);
		$data = $ql->rules($rules)->range('tbody>tr')->query()->getData()->all();
		$ql->destruct();
		return $data;
	}
}