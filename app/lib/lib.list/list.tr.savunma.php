<?php
	if(!defined('APP')) die('...');

	//+++ DİKKAT twitterdan parse ettiklerimiz ------------------------
	if($type == 'c4defence')
	{
		$_new = new $type();
		$_new->twitter_fetch($username = $type, $domain = $type, $limit = 25, $type);
	}

	if($type == 'kokpitaero')
	{
		$_new = new $type();
		$_new->twitter_fetch($username = $type, $domain = 'kokpit.aero', $limit = 25, $type);
	}
	//--- DİKKAT twitterdan parse ettiklerimiz ------------------------

	if($type == 'msi')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[167], $type);
	}

	if($type == 'savunmaveteknoloji')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[165], $type);
	}

	if($type == 'siyahgribeyaz')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[166], $type);
	}

	if($type == 'bagimsizhavacilar')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[240], $type);
	}

	if($type == 'airkule')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[241], $type);
	}

	if($type == 'airporthaber')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[242], $type);
	}

	if($type == 'airnewstimes')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[244], $type);
	}

	if($type == 'mydroneland')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[245], $type);
	}

	if($type == 'defenceturkey')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[246], $type);
	}

	if($type == 'monch')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[247], $type);
	}

	if($type == 'turkdefence')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[248], $type);
	}

	if($type == 'turksavunmasektoru')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[259], $type);
	}
