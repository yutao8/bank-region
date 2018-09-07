<?php
/**
 * author: yutao
 * createTime: 2018/9/7 下午5:25
 * description:
 */
namespace yutao8\BankRegion;

use QL\QueryList;

class City{

	function province(){
		$url = 'http://www.lianhanghao.com/';
		$html = Helper::http_get_cache($url);
		$rules = array(
			'id' => array('#province option','value'),
			'title' => array('#province option','text'),
		);
		$data = QueryList::html($html)->rules($rules)->query()->getData()->all();
		array_shift($data);
		return $data;
	}

	function city($province_id){
		$url = 'http://www.lianhanghao.com/index.php/Index/Ajax?id=' . $province_id;
		$content = Helper::http_get_cache($url);
		if(preg_match('/^\xEF\xBB\xBF/',$content)){
			$content = substr($content,3);
		}
		$data = json_decode($content,true);
		return $data;
	}
}