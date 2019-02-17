<?php
	if(!defined('APP')) die('...');

	/**
	|--------------------------------------------------------------
	| Geçerli talep tipleri ve talep şekilleri
	|--------------------------------------------------------------

	|--------------------------------------------------------------
	|---UYGULAMA BAZLI TANIMLAMALAR
	|--------------------------------------------------------------
	|
	| service.php?secure=SOME_KEY_HERE&type=menu_cubetechno
	|
	| CUBE TECHNO için Menü Kategorilerini servis eder
	|
	|--------------------------------------------------------------
	|
	| service.php?secure=SOME_KEY_HERE&type=source_cubetechno
	|
	| CUBE TECHNO için Menü kategorilerindeki kaynakları servis eder
	|
	|--------------------------------------------------------------
	|
	| service.php?secure=SOME_KEY_HERE&type=menu_teknokup
	|
	| KÜP TEKNO için Menü Kategorilerini servis eder
	|
	|--------------------------------------------------------------
	|
	| service.php?secure=SOME_KEY_HERE&type=source_teknokup
	|
	| KÜP TEKNO için Menü kategorilerindeki kaynakları servis eder
	|
	|--------------------------------------------------------------
	|---YÖNETİMSEL TANIMLAMALAR
	|--------------------------------------------------------------
	|
	| service.php?secure=SOME_KEY_HERE&type=truncate&key={KEY}
	|
	| Belirtilen KAYNAĞA AİT TÜM HABERLERİ silmeye yarar
	| Yönetimsel amaçlar için kullanılmaktadır
	|
	|--------------------------------------------------------------
	|
	| service.php?secure=SOME_KEY_HERE&type=delete&id={NUMBER}
	|
	| Belirtilen içeriki silmeye yarar, çok saçma sebepler olmadıkça kullanmamak gerekiyor
	|
	|--------------------------------------------------------------
	|---TÜM UYGULAMALARA YÖNELİK TANIMLAMALAR
	|--------------------------------------------------------------
	|
	| service.php?secure=SOME_KEY_HERE&type=source&source={KEYWORD}&page={NUMBER}&limit={NUMBER}
	|
	| İlgili KAYNAĞA ait haberleri servis eder
	|
	|--------------------------------------------------------------
	|
	| service.php?secure=SOME_KEY_HERE&type=source_multiple&source={KEYWORD,KEYWORD}&page={NUMBER}&limit={NUMBER}
	|
	| İlgili Kaynağa ait haberleri servis eder
	|
	|--------------------------------------------------------------
	|
	| service.php?secure=SOME_KEY_HERE&type=cat&cat={KEYWORD}&page={NUMBER}&limit={NUMBER}
	|
	| İlgili KATEGORİYE ait haberleri servis eder
	|
	|--------------------------------------------------------------
	*/

	//init çağrısından önce kod olmaması tercihimdir
	include 'lib/init.php';

	define('serviceApiKey', 'SOME_KEY_HERE');
	//temel güvenlik
	$type		= htmlspecialchars($_REQUEST["type"]);
	$secure		= htmlspecialchars($_REQUEST["secure"]);
	$i_source	= htmlspecialchars($_REQUEST["source"]);
	$i_cat		= htmlspecialchars($_REQUEST["cat"]);
	$i_page		= intval($_REQUEST["page"]);
	$i_limit	= intval($_REQUEST["limit"]);

	//page sayı olsun
	if($i_page < 0) 			$i_page = 0;

	//limitleri biz koyalım
	if($i_limit < 10)			$i_limit = 10;
	elseif($i_limit < 20)		$i_limit = 10;
	elseif($i_limit < 50)		$i_limit = 20;
	elseif($i_limit < 100)		$i_limit = 50;
	elseif($i_limit > 100) 		$i_limit = 100;

	if($secure <> serviceApiKey)
	{
		die("Anahtar Hatali");
	}

	//belli bir haberi silmemiz gerektiğinde
	if($type == "delete" && $_id > 0)
	{
		$_content->content_delete_soft($_id);
		$data = true;
	}

	//belli bir kaynağa ait tüm haberleri silmemiz gerektiğinde
	if($type == "truncate" && $_key <> '')
	{
		$_content->content_truncate_source($_key);
		$data = true;
	}

	//belli bir kaynağa ait tüm haberlerin yayında olup olmadığını kontrol etmemiz gerektiğinde
	if($type == "repair" && $_key <> '')
	{
		$_content->content_repair_source($_key);
		//$data = true;
	}

	#--- [ + ]----| CUBE TECHNO |--------------

	if($type == "menu_cubetechno")
	{
		$data = '
            [
                { "id": "1", "order" : "1", "slug" : "technology", "title": "Technology"}
            ]
		';
		header('Content-type: application/json');
		echo trim($data);
		exit();
	}


	if($type == "source_cubetechno")
	{
		//menu id ile group id aynı olması gerekiyor
		//kaynakları ilgili kategoriye dahil etmeye yarıyor
		$text = '';
		foreach($list_sources['technology'] as $k => $v)
		{
			$text.= '{ "source": "'.$k.'", "title" : "'.$v.'", "group": "1"},';
		}

		//bunun amacı json datanın kırılmasını engellemek
		$text.= '{ "source": "Kaynak", "title" : "Kaynak Adı", "group": "5"}';

		//hepsini paketle
		$data = '[ '.$text.' ]';

		header('Content-type: application/json');
		echo trim($data);
		exit();
	}
	#---[ - ]----| CUBE TECHNO |--------------


	#---[ + ]----| KÜP TEKNO |--------------

	if($type == "menu_teknokup")
	{
		$data = '
            [
                { "id": "1", "order" : "1", "slug" : "teknoloji", 	"title": "Teknoloji"},
                { "id": "2", "order" : "2", "slug" : "technology", 	"title": "Technology"},
                { "id": "3", "order" : "3", "slug" : "savunma", 	"title": "Savunma ve Havacılık"},
                { "id": "4", "order" : "4", "slug" : "bilim", 		"title": "Bilim Teknik"}
            ]
		';
		header('Content-type: application/json');
		echo trim($data);
		exit();
	}

	if($type == "source_teknokup")
	{

		//menu id ile group id aynı olması gerekiyor
		//kaynakları ilgili kategoriye dahil etmeye yarıyor
		$text = '';
		foreach($list_sources['teknoloji'] as $k => $v)
		{
			$text.= '{ "source": "'.$k.'", "title" : "'.$v.'", "group": "1"},';
		}

		foreach($list_sources['technology'] as $k => $v)
		{
			$text.= '{ "source": "'.$k.'", "title" : "'.$v.'", "group": "2"},';
		}

		foreach($list_sources['savunma'] as $k => $v)
		{
			$text.= '{ "source": "'.$k.'", "title" : "'.$v.'", "group": "3"},';
		}
		foreach($list_sources['bilim'] as $k => $v)
		{
			$text.= '{ "source": "'.$k.'", "title" : "'.$v.'", "group": "4"},';
		}

        //bunun amacı json datanın kırılmasını engellemek
		$text.= '{ "source": "Kaynak", "title" : "Kaynak Adı", "group": "5"}';

		//hepsini paketle
		$data = '[ '.$text.' ]';

		header('Content-type: application/json');
		echo trim($data);
		exit();
	}

	#---[ - ]----| KÜP TEKNO |--------------

	if($type == "cat")
	{
		if($i_cat == 'teknoloji') 	$i_cat = 9;
		if($i_cat == 'technology') 	$i_cat = 59;
		if($i_cat == 'savunma') 	$i_cat = 12;
		if($i_cat == 'bilim') 		$i_cat = 11;

		$data = $_content->content_list_manset(
			$page 		= $i_page,
			$limit 		= $i_limit,
			$cat		= $i_cat,
			$source		= 'none'
		);
	}

	if($type == "source")
	{
		$data = $_content->content_list_manset(
			$page 		= $i_page,
			$limit 		= $i_limit,
			$cat		= 100,
			$source		= $i_source
		);
	}

	if($type == "source_multiple")
	{

		$t = explode(',', $i_source);

		$data = array();
		foreach($t as $k => $v)
		{
			$datax = $_content->content_list_manset(
				$page 		= $i_page,
				$limit 		= $i_limit,
				$cat		= 100,
				$source		= $v
			);

			$data = array_merge($data, $datax);
		}
	}

	header('Content-type: application/json');
	$data = json_encode($data) ;
	echo $data;
