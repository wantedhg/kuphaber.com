<?php
	if(!defined('APP')) die('...');

	//+++ DİKKAT twitterdan parse ettiklerimiz ------------------------
	if($type == 'merlininkazani')
	{
		$_new = new $type();
		$_new->twitter_fetch($username = $type, $domain = 'merlininkazani.com', $limit = 25, $type);
	}
	//--- DİKKAT twitterdan parse ettiklerimiz ------------------------

	if($type == 'webrazzi')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[132], $type);
	}

	if($type == 'sosyalmedya')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[133], $type);
	}

	if($type == 'chip')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[134], $type);
	}

	if($type == 'donanimhaber')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[135], $type);
	}

	if($type == 'pcnet')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[136], $type);
	}

	if($type == 'aorhan')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[137], $type);
	}

	if($type == 'maxicep')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[139], $type);
	}

	if($type == 'technopat')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[140], $type);
	}

	if($type == 'shiftdelete')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[141], $type);
	}

	if($type == 'teknolojioku')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[142], $type);
	}

	if($type == 'teknoblog')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[143], $type);
	}

	if($type == 'teknokulis')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[144], $type);
	}

	if($type == 'teknolog')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[145], $type);
	}

	if($type == 'teknolojihaber')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[146], $type);
	}

	if($type == 'teknolojituru')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[147], $type);
	}

	if($type == 'teknoseyir')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[148], $type);
	}

	if($type == 'wearlogy')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[150], $type);
	}

	if($type == 'webtekno')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[151], $type);
	}

	if($type == 'indir')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[153], $type);
	}

	if($type == 'androidegel')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[162], $type);
	}

	if($type == 'stuff')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[168], $type);
	}

	if($type == 'tknlj')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[169], $type);
	}

	if($type == 'bigumigu')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[172], $type);
	}

	if($type == 'btgunlugu')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[173], $type);
	}

	if($type == 'ceotudent')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[174], $type);
	}

	if($type == 'cepkolik')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[175], $type);
	}

	if($type == 'dijitalajanslar')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[177], $type);
	}

	if($type == 'bolumsonucanavari')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[178], $type);
	}

	if($type == 'donanimgunlugu')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[179], $type);
	}

	if($type == 'dunyahalleri')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[180], $type);
	}

	if($type == 'eticaret')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[181], $type);
	}

	if($type == 'frpnet')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[184], $type);
	}

	if($type == 'girisimhaber')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[185], $type);
	}

	if($type == 'hardwareplus')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[187], $type);
	}

	if($type == 'level')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[190], $type);
	}

	if($type == 'log')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[191], $type);
	}

	if($type == 'scroll')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[194], $type);
	}

	if($type == 'techinside')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[197], $type);
	}

	if($type == 'technosfer')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[198], $type);
	}

	if($type == 'tekdozdijital')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[199], $type);
	}

	if($type == 'teknolojikanneler')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[204], $type);
	}

	if($type == 'teknolojigundem')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[206], $type);
	}

	if($type == 'digitalage')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[207], $type);
	}

	if($type == 'computerworldtr')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[209], $type);
	}

	if($type == 'technotoday')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[210], $type);
	}

	if($type == 'bthaber')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[211], $type);
	}

	if($type == 'tamindir')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[212], $type);
	}

	if($type == 'technolabs')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[216], $type);
	}

	if($type == 'iyzico')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[218], $type);
	}

	if($type == 'webtures')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[220], $type);
	}

	if($type == 'pchocasi')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[222], $type);
	}

	if($type == 'btnet')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[223], $type);
	}

	if($type == 'elektrikport')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[224], $type);
	}

	if($type == 'oyungezer')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[243], $type);
	}

	if($type == 'technologic')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[256], $type);
	}
