<?php
/**
 * author: yutao
 * createTime: 2018/9/7 下午11:12
 * description:
 */
namespace yutao8\BankRegion;

use Symfony\Component\DomCrawler\Crawler;

class Tui78{
	function cate_list(){
		$url = 'http://www.tui78.com/bank/';
		$html = iconv("gb2312//IGNORE","utf-8",Helper::http_get_cache($url));
		$crawler = new Crawler();
		$data = [];
		$crawler->addHtmlContent($html);
		try{
			$data = $crawler->filter('span.aps a')->each(function(Crawler $node,$i){
				return ['title' => $node->text(),'url' => $node->attr('href'),'id' => str_replace(['http://www.tui78.com/bank/','.html'],'',$node->attr('href'))];
			});
		}catch(\Exception $e){
		}
		return $data;
	}

	function city_list($url){
		$html = iconv("gb2312//IGNORE","utf-8",Helper::http_get_cache($url));
		$crawler = new Crawler();
		$crawler->addHtmlContent($html);
		try{
			$data = $crawler->filter('.weizhi')->each(function(Crawler $node,$i){
				if($i > 0){
					try{
						$res['province'] = $node->filter('h2')->text();
					}catch(\Exception $e){
						$res['province'] = '';
					}
					try{
						$res['city'] = $node->filter('ul a')->each(function(Crawler $node2,$j){
							$href = $node2->attr('href');
							preg_match('/_([0-9]+).html/',$href,$tmp);
							return ['title' => $node2->text(),'url' => $href,'id' => $tmp[1]];
						});
					}catch(\Exception $e){
						$res['city'] = [];
					}
					return $res;
				}
			});
		}catch(\Exception $e){
			$data = [];
		}
		return $data;
	}

	function bank_list($url){
		$html = iconv("gb2312//IGNORE","utf-8",Helper::http_get_cache($url));
		$crawler = new Crawler();
		$crawler->addHtmlContent($html);
		try{
			$data = $crawler->filter('#bank li')->each(function(Crawler $node,$i){
				if($i > 0){
					try{
						$res['title'] = $node->filter('.name')->text();
					}catch(\Exception $e){
						$res['title'] = '';
					}
					try{
						$res['code'] = $node->filter('.aps')->text();
					}catch(\Exception $e){
						$res['code'] = '';
					}
					try{
						$res['address'] = str_replace(' ','',$node->filter('.add')->text());
					}catch(\Exception $e){
						$res['address'] = '';
					}
					return $res;
				}
			});
		}catch(\Exception $e){
			$data = [];
		}
		return $data;
	}
}