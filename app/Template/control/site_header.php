<?php if(!defined('APP')) die('...');

	$template = $twig->loadTemplate('site_header.twig');
	$header = $template->render
	(
		array
		(
			//her sayfanın kendisinden gelecek olan değerler
			'site_title'		=> $site_title,
			'site_canonical'	=> $site_canonical,
			'array_cat_name'	=> $list_cat_name,
			'array_cat_url'		=> $list_cat_url,
		)
	);
