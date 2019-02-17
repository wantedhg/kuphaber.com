<?php
	if(!defined('APP')) die('...');

	//haber siteleri çağrıları
	if($type == 'aksam')
	{
		//sık çalışması önerilir
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[53], $type);
	}

