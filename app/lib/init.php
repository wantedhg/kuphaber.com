<?php
	if(!defined('APP')) die('...');

	//sayfa saatini başlatıyoruz,
	//ne kadar aşağıda başlatırsak o kadar hatalı sayar
	$starttime = microtime(true);

	//maximum çalışma zamanı
	ini_set('max_execution_time', 300);

	//türkiyeye geçelim
	date_default_timezone_set('Europe/Istanbul');

	//config dosyamızı çağıralım
	include 'lib.config.php';

	//değilse klasik hata mesajlarını kullansın
	error_reporting(E_ERROR);
	if(ST_DEBUG == 2) error_reporting(E_ALL);

	//site linkini oluşturalım
	if(ST_ONLINE == 1) define('SITELINK', 'http://www.kuphaber.com/');
	if(ST_ONLINE == 0) define('SITELINK', 'http://'.$_SERVER['HTTP_HOST'].'/');

	//--- [+]--- Root Path Doğrulaması ---
	$_SERVER['DOCUMENT_ROOT'] = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT']);
	if(substr($_SERVER['DOCUMENT_ROOT'], -1) != '/')
	{
		$_SERVER['DOCUMENT_ROOT'] .= '/';
	}
	//--- [-]--- Root Path Doğrulaması sonu ---

	//uygulama kaynakların include yollarını
	//PATH değerleri olarak burada tanımlıyoruz
	//dikkat
	//bu alanın dışında bir yerlerde PATH tanımlamayınız
	define('SITEPATH',			$_SERVER['DOCUMENT_ROOT']);
	define('ACP_MODULE_PATH',	SITEPATH.'app/Modules/');
	define('IMAGE_DIRECTORY',	SITEPATH.'assets/uploads/images/');
	//path tanımlamaları sonu

	//öncel fonksiyonlar
	include SITEPATH.'app/lib/lib.prefunctions.php';

	//vendor dosyalar
	//Veritabanı class, oturum ve bağlantı dosyası, Twig
	include SITEPATH.'vendor/adodb5/adodb.inc.php';
	include SITEPATH.'vendor/adodb5/adodb-memcache.lib.inc.php';

	//kendi dosyalarımız
	//muhtemelen oturuma hiç ihtiyacımız olmayacak
	include SITEPATH.'app/lib/lib.connection.php';

	//bu dosya dil değerleri için gerekli
	//oturum tablosundan sonra çalışmak zorunda
	//sonraki dosya ise, dizi değerlerimiz
	include SITEPATH.'app/lib/lib.array.php';
	include SITEPATH.'app/lib/lib.source.php';

	//yönetim panelinde kullandığımız iki dosya
	//fonksiyonlarımız ve sistem mesajlarımız
	include SITEPATH.'app/Modules/content/CLASS.content.php';
	$_content	= new content();

	//tablolar tanımlıyoruz
	define('T_CONTENT',		'app_content');

	//cachetime, saniye cinsinden hesaplanıyor
	define('memcached',		$memcache_status);
	define('cachetime',		90);

	if(ST_CDN == 0)
	{
		define('SITELINK_CDN', 		SITELINK);
		define('G_CSSLINK',			SITELINK.'assets/css/');
		define('G_JSLINK',			SITELINK.'assets/js/');
		define('G_IMGLINK',			SITELINK.'assets/img/');
		define('G_IMAGES',			SITELINK.'assets/uploads/images/');
	}

	if(ST_CDN == 1)
	{
		define('SITELINK_CDN', 		'http://cdn.kuphaber.com/');
		define('G_CSSLINK',			SITELINK_CDN.'assets/css/');
		define('G_JSLINK',			SITELINK_CDN.'assets/js/');
		define('G_IMGLINK',			SITELINK_CDN.'assets/img/');
		define('G_IMAGES',			SITELINK_CDN.'assets/uploads/images/');
	}

	$_id			= intval(myReq('id', 1));
	$_pg			= intval(myReq('pg', 1));
	$_key 			= htmlspecialchars(strip_tags(myReq('key',2)));
	$type 			= htmlspecialchars(strip_tags(myReq('type',2)));

	$sayfaadi = basename($_SERVER['SCRIPT_NAME'],'.php');

	if($sayfaadi == 'index')
	{
		include SITEPATH.'vendor/Twig/Autoloader.php';

		Twig_Autoloader::register();

		//$loader = new Twig_Loader_Filesystem($templateDir);
		$loader	= new Twig_Loader_Filesystem(SITEPATH.'app/Template/view');

		//debug kapalı ise bellekleme işlemi aktif
		//debug açık ise bellekleme işlemi pasif
		if(ST_DEBUG == 0)	$twig = new Twig_Environment($loader, array('cache' => SITEPATH.'cache/tmp/'));
		if(ST_DEBUG <> 0)	$twig = new Twig_Environment($loader);

		//gün sayar fonksiyonunu twig'e time_diff diye geçiriyoruz
		$filter = new Twig_SimpleFilter('time_diff', 'twig_daycounter');
		$twig->addFilter($filter);

		//temada kullanacağımız global değişkenleri bu şekilde geçiriyoruz
		//yoksa tek tek tema dosyalarında geçirmek zorunda kalıyoruz
		$twig->addGlobal('LINK_INDEX',	SITELINK);
		$twig->addGlobal('SITELINK',	SITELINK);
		//kaynaklar
		$twig->addGlobal('G_CSSLINK',	G_CSSLINK);
		$twig->addGlobal('G_JSLINK',	G_JSLINK);
		$twig->addGlobal('G_IMGLINK',	G_IMGLINK);
		//diziler
		$twig->addGlobal('L',			$L);
		//değerler
		$twig->addGlobal('id',			$_id);
		$twig->addGlobal('pg',			$_pg);
		$twig->addGlobal('key',			$_key);
		//kaynaklar

		//$source_teknoloji = array_merge( $list_sources['teknoloji'], $list_sources_extended['teknoloji'] );
 		//asort($source_teknoloji);

// 		$twig->addGlobal('source_gazete',			$list_sources['gazete']);
// 		$twig->addGlobal('source_gazete_spor',		$list_sources['gazete_spor']);
// 		$twig->addGlobal('source_internet',			$list_sources['internet']);
		$twig->addGlobal('source_teknoloji',		$list_sources['teknoloji']);
		$twig->addGlobal('source_savunma',			$list_sources['savunma']);
		$twig->addGlobal('source_bilim',			$list_sources['bilim']);
		$twig->addGlobal('source_kultursanat',		$list_sources['kultursanat']);
		$twig->addGlobal('source_technology',		$list_sources['technology']);
	}

	//sadece cron_cache sayfasında parser yüklüyoruz
	if($sayfaadi == 'cron_cache')
	{
		//include SITEPATH.'app/CLASS/CLASS.parse.php';
		//talepten ilgili parseri seçerek yüklüyoruz;
		//böylece bellekten kazanıyoruz
		if(array_key_exists($type, $list_sources_big))
		{
			if(file_exists(SITEPATH.'app/CLASS/'.$type[0].'/'.$type.'.php'))
			{
				require_once SITEPATH.'app/CLASS/'.$type[0].'/'.$type.'.php';
			}
		}
	}
