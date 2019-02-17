<?php
	require 'autoload.php';

	use Abraham\TwitterOAuth\TwitterOAuth;

	//minimum ayak izi için bu fonksiyonu tekrar kullanıyoruz
	function strpos_array($haystack, $needle, $offset=0)
	{
		/**
		* strpos un dizi ile çalışanı
		* ilk true gördüğünde direk dönüş yapar
		*/

		if(!is_array($needle)) $needle = array($needle);
		foreach($needle as $query)
		{
			if(strpos($haystack, $query, $offset) !== false)
			{
				return true; // stop on first true result
			}
		}
		return false;
	}

	/**
	* ESKİ APİ BİLGİLERİ
	* -------------------------------------------------------------
	* Twit API bilgileri
	* Owner					: gazisabriunal
	* Owner ID				: 759755739816611841
	* $CONSUMER_KEY			= 'hpXF9ANqw0AbkcKb7dIfHw7Dk';
	* $CONSUMER_SECRET		= 'tVIDXIFSTzB3whq8ic64v4UEGnwUNQjxSGg1yUwKqv95g71Kh8';
	* $access_token			= '759755739816611841-RYKplequV01K8BkUZnTKqUurwORfnic';
	* $access_token_secret	= '3MM8iUm8NcdiaVnzKyJiGlT4p5G3EEf1SOTINgrXLxek5';
	*/

	/**
		Owner gettweetsapp
		Owner ID 823836420070637568
	*/
	$CONSUMER_KEY			= 'qrjGX20iApUwI5vp6DHOS6hbq';
	$CONSUMER_SECRET		= 'dzkBuf7IwHT7PH2DViVCZoI0JROPGck2KfZmyYR1mEKIf2mN3P';
	$access_token			= '823836420070637568-RNyy2rGBUWzHFdteRAQVW2sEjLFb5JG';
	$access_token_secret	= 'Mlr4V6edN3gmY0J6oCFyixHEsS9dU9KiK4s8FYuFzzxje';

	/**
	* @username		: Hangi twitter isminden dataları çekeceğiz
	* @domain		: Sonuçlar hangi alan adını dönecek
	* @limit 		: Kaç adet sonuç dönecek
	*/
	$username		= $_REQUEST['username'];
	$domain 		= $_REQUEST['domain'];
	$limit 			= $_REQUEST['limit'];

	//Sadece belirli url mantıklarına izin veriyoruz
	//bunlar öncelikli olarak kısaltma servisleri ve sitenin kendi adresinden ibaret
	$array_url  = array(
		'goo.gl',
		'tinyurl.com',
		'bit.ly',
		'buff.ly',
		't.co',
		'dhbr.co',
	);

	//bu denetimle alan adını boş geçme şansımız da oluyor
	if($domain <> '') $array_url[] = $domain;

	$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $access_token, $access_token_secret);

	//API Versiyonu
	$connection->host = 'https://api.twitter.com/1.1/';

	//talebi gönderiyoruz ve sonucu alıyoruz
	$ret = $connection->get('statuses/user_timeline', array(
		'screen_name' 		=> $username,
		'exclude_replies' 	=> 'true',
		'include_rts' 		=> 'false',
		'count' 			=> $limit
	));

	$text = '<channel>';
	foreach($ret as $k => $v)
	{
		$cache_title 	= $ret[$k]->text;
		$cache_url 		= $ret[$k]->entities->urls[0]->expanded_url;
		$cache_image 	= $ret[$k]->entities->media[0]->media_url;

		//başlıktan hashtag yani # işaretlerini silelim
		//Tüm hastag metnini silmek sıkıntı olabiliyor
		$cache_title 	= str_replace('#','',$cache_title);

		//başlıktan mention yani @ işaretlerini silelim
		$cache_title 	= str_replace(array('@','aracılığıyla'),'',$cache_title);

		//başlıktan mantion isimlerini silelim
		$count = count($ret[$k]->entities->user_mentions);
		for($i = 0; $i < $count; $i++)
		{
			$replace 		= $ret[$k]->entities->user_mentions[$i]->screen_name;
			$cache_title 	= str_replace($replace,'',$cache_title);
		}

		//başlıktan imajın linkini silelim
		$count = count($ret[$k]->entities->media);
		for($i = 0; $i < $count; $i++)
		{
			$replace 		= $ret[$k]->entities->media[$i]->url;
			$cache_title 	= str_replace($replace,'',$cache_title);
		}

		//başlıktan haberin urlsini silelim
		$count = count($ret[$k]->entities->urls);
		for($i = 0; $i < $count; $i++)
		{
			$replace		= $ret[$k]->entities->urls[$i]->url;
			$cache_title 	= str_replace($replace,'',$cache_title);
		}

		if(strpos_array($cache_url, $array_url) == true)
		{
			$text.= '<item>';
			$text.= '<title>'.trim($cache_title).'</title>';
			$text.= '<url>'.trim($cache_url).'</url>';
			if($cache_image <> '')
			{
				$text.= '<image>'.trim($cache_image).'</image>';
			}
			$text.= '</item>';
		}
	}
	$text.= '</channel>';

	header('Content-type: application/xml');
	echo $text;
