<?php
/**
 * author: yutao
 * createTime: 2018/9/7 下午5:26
 * description:
 */
namespace yutao8\BankRegion;

class Helper{

	static function cache($key,$value = null,$cacheTime = 86400){
		$key = is_string($key) ? $key : md5(serialize($key));
		$cacheDir = __DIR__ . '/.temp/' . date('Ymd') . '/';
		self::mkdirs($cacheDir) or die('create cacheDir error!');
		$cacheFile = $cacheDir . $key . '.temp';
		if(is_null($value)){ //读
			if(file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime){
				return unserialize(file_get_contents($cacheFile));
			}else{
				return null;
			}
		}else{//写
			return file_put_contents($cacheFile,serialize($value));
		}
	}

	static function http_get_cache($url = '',$cacheTime = 86400){
		$md5Key = md5($url);
		$httpData = self::cache($md5Key,null,$cacheTime);
		if(empty($httpData)){
			usleep(rand(1000,500000)); //延迟1毫秒-0.5秒
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36');
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$httpData = curl_exec($ch);
			curl_close($ch);
			self::cache($md5Key,$httpData);
		}
		return $httpData;
	}

	// 创建多级目录
	static function mkdirs($dir){
		if(!is_dir($dir)){
			if(!self::mkdirs(dirname($dir))){
				return false;
			}
			if(!mkdir($dir,0777)){
				return false;
			}
		}
		return true;
	}
}