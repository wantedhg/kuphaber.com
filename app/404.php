<?php
	if(!defined('APP')) die('...');

	include 'lib/init.php';
	//init çağrısından önce kod olmaması tercihimdir

	$uri	= $_SERVER['REQUEST_URI']."\n";
	$uri.= file_get_contents('cache/404.errors');
	file_put_contents('cache/404.errors', $uri);

