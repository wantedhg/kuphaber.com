<?php if(!defined('APP')) die('...');

	$template = $twig->loadTemplate('page_index.twig');

	if($_REQUEST['type'] == 'index')
	{
		$site_title				= $list_cat_name[$_id].' - '.$L['pIndex_Company'];
		$site_canonical 		= $list_cat_url[$_id];

		$list = $_content->content_list_manset(
			$page 		= 0,
			$limit 		= 250,
			$cat		= $_id,
			$source		= 'none'
		);
	}

	if($_REQUEST['type'] == 'source')
	{
		//source belli bir kategori işaret etmemişse
		$tlink = explode('_', $_key);
		$cat = intval($list_cat[$t = format_url($tlink[1])]);

		//kategori tanımlanmamışsa
		if($cat == '')
		{
			$list = $_content->content_list_manset(
				$page 		= 0,
				$limit 		= 100,
				$cat		= 100,
				$source		= $_key
			);
			$site_title				= $list_sources_big[$_key].' - '.$L['pIndex_Company'];
			$site_canonical 		= $_content->url_source($_key);

		}
		else
		{
			//belli bir kategori talebi var ise
			$list = $_content->content_list_manset(
				$page 		= 0,
				$limit 		= 100,
				$cat		= $cat,
				$source		= $_key
			);

			$site_title				= $list_sources_big[$tlink[0]].' '.$list_cat_name[$cat].' - '.$L['pIndex_Company'];
			$site_canonical 		= $_content->url_source($_key);
		}

	}

	$content = $template->render
	(
		array
		(
			'list_content'		=> $list,
		)
	);
