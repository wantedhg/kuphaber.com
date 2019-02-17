<?php
	if(!defined('APP')) die('...');

	include 'lib/init.php';
	//init çağrısından önce kod olmaması tercihimdir

	//tema yolundan dosyalarımızı çağırıyoruz
	include 'Template/control/page_index.php';
	include 'Template/control/site_header.php';
	include 'Template/control/site_footer.php';

	echo $header.$content.$footer;
