<?php if(!defined('APP')) die('...');

	$template = $twig->loadTemplate('site_footer.twig');
	$footer = $template->render
	(
		array
		(
			'site_canonical' => $site_canonical,
			'year'			 => date("Y"),
		)
	);
