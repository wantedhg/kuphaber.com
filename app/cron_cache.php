<?php
	if(!defined('APP')) die('...');

	include 'lib/init.php';

	//class yolu
	include SITEPATH.'vendor/class/simple_html_dom.php';

	//tüm dosya içeriği lib içine alındı
	//mcv yapısına uygun bir sonuç dönmediği ve
	//lib.source ile aynı klasörde durması kullanım kolaylığı olduğu için

	//Türkçe
	include 'lib/lib.list/list.tr.main.php';
	include 'lib/lib.list/list.tr.teknoloji.php';
	include 'lib/lib.list/list.tr.savunma.php';
	include 'lib/lib.list/list.tr.bilim.php';

	//ingilizce
	include 'lib/lib.list/list.en.technology.php';
