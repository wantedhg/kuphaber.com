<?php
	if(!defined('APP')) die('...');

	//cache olmadan parse eden parserların
	//henüz klasörlere dağıtılmamış olanları
	if($type == 'kitaphaber')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[129], $type);
	}
