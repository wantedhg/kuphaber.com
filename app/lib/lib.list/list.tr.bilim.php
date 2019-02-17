<?php
	if(!defined('APP')) die('...');

	//cache olmadan parse eden parserların
	//bilim kategorisine ait olanlar

	if($type == 'gercekbilim')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[152], $type);
	}

	if($type == 'haberbilimteknoloji')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[164], $type);
	}

	if($type == 'arkeofili')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[260], $type);
	}

	if($type == 'bilimfili')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[261], $type);
	}

	if($type == 'evrimagaci')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[262], $type);
	}

	if($type == 'kozmikanafor')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[263], $type);
	}

	if($type == 'kuark')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[264], $type);
	}

	if($type == 'popscitr')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[265], $type);
	}

	if($type == 'euronewsbilim')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[266], $type);
	}

	if($type == 'fizikist')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[267], $type);
	}

	if($type == 'egitimpedia')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[268], $type);
	}

	if($type == 'nbeyin')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[269], $type);
	}

	if($type == 'bilimorg')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[270], $type);
	}

