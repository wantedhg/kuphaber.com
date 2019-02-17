<?php
	if(!defined('APP')) die('...');

	//ingilizce teknoloji siteleri çağrıları
	if($type == 'arstechnica')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[228], $type);
	}

	if($type == 'zdnet')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[234], $type);
	}

	if($type == 'techcrunch')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[235], $type);
	}

	if($type == 'theverge')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[236], $type);
	}

	if($type == 'audioholics')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[237], $type);
	}

	if($type == 'buzzfeed')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[238], $type);
	}

	if($type == 'destructoid')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[239], $type);
	}

	if($type == 'engadget')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[249], $type);
	}

	if($type == 'gizmodo')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[250], $type);
	}

	if($type == 'kotaku')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[251], $type);
	}

	if($type == 'macworld')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[252], $type);
	}

	if($type == 'pcworld')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[253], $type);
	}

	if($type == 'computerworld')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[254], $type);
	}

	if($type == 'cio')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[255], $type);
	}
