<?php


if (isBot() == false) {
	if ($_SERVER["GEOIP_COUNTRY_CODE"] == "RU") {
		header("Location: /dlya-vas-dostup-na-sajt-zakryt.html");
	}
	/** * Узнаем IP адрес пользователя */ 
	$ip = $_SERVER["REMOTE_ADDR"]; 
	/** * Получаем информацию относительно IP * (страна, город и другая информация) */ 
	$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json?token=170571f9df6bd3")); 
	/* Логируем информацию о IP */ 
	file_put_contents(__DIR__ . '/hits.log', print_r($details, 1), FILE_APPEND);
}


 /* Эта функция будет проверять, является ли посетитель роботом поисковой системы */
function isBot(&$botname = ''){
	$bots = array(
		'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
		'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
		'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
		'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
		'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
		'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
		'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
		'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
		'Nigma.ru','bing.com','dotnetdotcom'
		);
	foreach($bots as $bot)
		if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false){
		  return true;
		}
	return false;
}