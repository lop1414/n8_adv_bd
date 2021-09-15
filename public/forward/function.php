<?php

function getIp(){
    $onlineip = '';
    if( !empty($_SERVER['HTTP_CDN_SRC_IP']) ) {
        $onlineip = $_SERVER['HTTP_CDN_SRC_IP'];
    }else if(!empty($_SERVER['HTTP_X_YD_IP'])){
		$onlineip = $_SERVER['HTTP_X_YD_IP'];
	}else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
		$onlineip = getenv('REMOTE_ADDR');
	}else if(!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
		$onlineip = getenv('HTTP_CLIENT_IP');
	}else if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	}

	preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $onlineip, $onlineipmatches);
	$onlineip = isset($onlineipmatches[0]) ? $onlineipmatches[0] : 'unknown';

    return $onlineip;
}
