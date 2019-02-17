<?php
	if(!defined('APP')) die('...');

	//site DEBUG modunda detaylı hata geri bildirileri verir
	//0 debug modu kapalı
	//1 debug modu acık: sql sorguları + tema dosyaları
	//2 debug modu acık: sql sorguları + tema dosyaları + php hataları
	define('ST_DEBUG', 			1);

	//site yayında ise yayın veritabanını kullanır
	//0 local veritabanını kullan
	//1 yayındaki veritabanını kullan demektir
	//dikkat, yayındaki veritabanını lokalden kullanıyorsanız
	//beklenmedik hatalar ile karşılaşabilirsiniz
	define('ST_ONLINE', 		0);

	//site CDN hizmeti kullanıyorsa, CDN hizmetini açıp kapatmak için bu ayarı kullanıyoruz
	//0 CDN kapalı
	//1 CDN acık
	define('ST_CDN', 			0);

	//normalde define ataması yapıyorduk;
	//lakin iki defa define tanımlanamayacağı için
	//değişken olarak atamama yapıyoruz
	//0 pasif, 1 aktif demek oluyor
	$memcache_status 		= 1;
