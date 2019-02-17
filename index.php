<?php
	/**
	| Root klasör altındaki php dosyaları SADECE çağrı dosyalarıdır
	| asıl dosyayı include etmekten başka görevleri yoktur
	| asıl dosyalarımız app/ klasörü altında bulunur
	| bu sayede yazdığımız gerçek kodlar tamamen app/ klasörü altında
	| depolanmış omaktadır
	*/

	define('APP', '1');
	require 'app/index.php';
