<?php
	if(!defined('APP')) die('...');

	//-------------- GERİSİ KONTROL EDİLMEDİ --------------------------

	if($type == 'mynet_saatlik')
	{
		$_new = new mynet();

		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[1], $channel = 1, $cat_type = 2, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[2], $channel = 2, $cat_type = 2, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[8], $channel = 8, $cat_type = 2, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[10], $channel = 10, $cat_type = 1, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[19], $channel = 19, $cat_type = 1, $cat_name = 'none');
	}

	if($type == 'mynet_gunluk_bir')
	{
		$_new = new mynet();

		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[3], $channel = 3, $cat_type = 2, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[4], $channel = 4, $cat_type = 2, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[5], $channel = 5, $cat_type = 2, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[6], $channel = 6, $cat_type = 2, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[7], $channel = 7, $cat_type = 2, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[9], $channel = 9, $cat_type = 2, $cat_name = 'none');

		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[11], $channel = 11, $cat_type = 1, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[12], $channel = 12, $cat_type = 1, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[13], $channel = 13, $cat_type = 1, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[14], $channel = 14, $cat_type = 1, $cat_name = 'none');
	}

	if($type == 'mynet_gunluk_iki')
	{
		$_new = new mynet();

		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[15], $channel = 15, $cat_type = 1, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[16], $channel = 16, $cat_type = 1, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[17], $channel = 17, $cat_type = 1, $cat_name = 'none');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[18], $channel = 18, $cat_type = 1, $cat_name = 'none');
		//
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[20], $channel = 20, $cat_type = 0, $cat_name = 'spor');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[21], $channel = 21, $cat_type = 0, $cat_name = 'spor');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[22], $channel = 22, $cat_type = 0, $cat_name = 'spor');
		$_new->rss_fetch($feed_type = 'rss', $list_rss_source[23], $channel = 23, $cat_type = 0, $cat_name = 'spor');
	}

	if($type == 'mynet_gunluk_uc')
	{
		$_new = new mynet();
		$_new->rss_fetch($feed_type = 'rdf', $list_rss_source[24], $channel = 24, $cat_type = 0, $cat_name = 'sinema');
		$_new->rss_fetch($feed_type = 'rdf', $list_rss_source[25], $channel = 25, $cat_type = 0, $cat_name = 'sinema');
		$_new->rss_fetch($feed_type = 'rdf', $list_rss_source[26], $channel = 26, $cat_type = 0, $cat_name = 'sinema');
		$_new->rss_fetch($feed_type = 'rdf', $list_rss_source[27], $channel = 27, $cat_type = 0, $cat_name = 'sinema');
	}

	if($type == 'sabah')
	{
		//10-15 dakikada bir çalışsa faydalı olur
		$_new = new sabah();
		$_new->rss_fetch($list_rss_source[35], $channel = 35);
		$_new->rss_fetch($list_rss_source[36], $channel = 36);
	}

	if($type == 'sabah_saatlik')
	{
		$_new = new sabah();
		$_new->rss_fetch($list_rss_source[37], $channel = 37);
		$_new->rss_fetch($list_rss_source[38], $channel = 38);
		$_new->rss_fetch($list_rss_source[39], $channel = 39);
		$_new->rss_fetch($list_rss_source[40], $channel = 40);
		$_new->rss_fetch($list_rss_source[41], $channel = 41);
		$_new->rss_fetch($list_rss_source[42], $channel = 42);
		$_new->rss_fetch($list_rss_source[43], $channel = 43);
	}

	if($type == 'sabah_gunluk')
	{
		$_new = new sabah();
		$_new->rss_fetch($list_rss_source[44], $channel = 44);
		$_new->rss_fetch($list_rss_source[45], $channel = 45);
		$_new->rss_fetch($list_rss_source[46], $channel = 46);
		$_new->rss_fetch($list_rss_source[47], $channel = 47);
		$_new->rss_fetch($list_rss_source[48], $channel = 48);
		$_new->rss_fetch($list_rss_source[49], $channel = 49);
		$_new->rss_fetch($list_rss_source[50], $channel = 50);
		$_new->rss_fetch($list_rss_source[51], $channel = 51);
		$_new->rss_fetch($list_rss_source[52], $channel = 52);
	}

	if($type == 'akit')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[28], $channel = 28);
	}

	if($type == 'haberiyakala')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[29], $channel = 29);
	}

	if($type == 'mansetoku')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[30], $channel = 30);
	}

	if($type == 'ensonhaber')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[31], $channel = 31, $cat_type = 1, $cat_name = 'none');
		$_new->rss_fetch($list_rss_source[32], $channel = 32, $cat_type = 0, $cat_name = 'spor');
	}

	if($type == 'yenisafak')
	{
		//çok sık çalışması önerilir
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[33], $channel = 33);
	}

	if($type == 'star')
	{
		//sık çalışması önerilir
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[34], $channel = 34);
	}

	if($type == 'dunya')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[54], $channel = 54);
	}

	if($type == 'karar')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[55], $channel = 55);
	}

	if($type == 'milat')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[56], $channel = 56);
	}

	if($type == 'mustakil')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[57], $channel = 57);
	}

	if($type == 'takvim')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[58], $channel = 58);
		$_new->rss_fetch($list_rss_source[59], $channel = 59);
		$_new->rss_fetch($list_rss_source[60], $channel = 60);
		$_new->rss_fetch($list_rss_source[61], $channel = 61);
		$_new->rss_fetch($list_rss_source[62], $channel = 62);
		$_new->rss_fetch($list_rss_source[63], $channel = 63);
		$_new->rss_fetch($list_rss_source[64], $channel = 64);
	}

	if($type == 'vahdet')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[65], $channel = 65);
	}

	if($type == 'yeniyuzyil')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[66], $channel = 66);
		$_new->rss_fetch($list_rss_source[67], $channel = 67);
		$_new->rss_fetch($list_rss_source[68], $channel = 68);
		$_new->rss_fetch($list_rss_source[69], $channel = 69);
		$_new->rss_fetch($list_rss_source[70], $channel = 70);
		$_new->rss_fetch($list_rss_source[71], $channel = 71);
		$_new->rss_fetch($list_rss_source[72], $channel = 72);
		$_new->rss_fetch($list_rss_source[73], $channel = 73);
		$_new->rss_fetch($list_rss_source[74], $channel = 74);
		$_new->rss_fetch($list_rss_source[75], $channel = 75);
	}

	if($type == 'haberturk')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[76], $channel = 76);
	}

	if($type == 'hurriyet')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[77], $channel = 77, $cat_name = 'gundem');
		$_new->rss_fetch($list_rss_source[78], $channel = 78, $cat_name = 'egitim');
		$_new->rss_fetch($list_rss_source[79], $channel = 79, $cat_name = 'dunya');
		$_new->rss_fetch($list_rss_source[80], $channel = 80, $cat_name = 'dunya');
		$_new->rss_fetch($list_rss_source[81], $channel = 81, $cat_name = 'ekonomi');
		$_new->rss_fetch($list_rss_source[82], $channel = 82, $cat_name = 'ekonomi');
		$_new->rss_fetch($list_rss_source[83], $channel = 83, $cat_name = 'spor');
		$_new->rss_fetch($list_rss_source[84], $channel = 84, $cat_name = 'teknoloji');
		$_new->rss_fetch($list_rss_source[85], $channel = 85, $cat_name = 'none');
	}

	if($type == 'milliyet')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[87], $channel = 87, $cat_name = 'dunya');
		$_new->rss_fetch($list_rss_source[88], $channel = 88, $cat_name = 'ekonomi');
		$_new->rss_fetch($list_rss_source[89], $channel = 89, $cat_name = 'siyaset');
		$_new->rss_fetch($list_rss_source[90], $channel = 90, $cat_name = 'yasam');
		$_new->rss_fetch($list_rss_source[91], $channel = 91, $cat_name = 'magazin');
		$_new->rss_fetch($list_rss_source[92], $channel = 92, $cat_name = 'gundem');
		$_new->rss_fetch($list_rss_source[93], $channel = 93, $cat_name = 'egitim');
		$_new->rss_fetch($list_rss_source[94], $channel = 94, $cat_name = 'otomobil');
		$_new->rss_fetch($list_rss_source[95], $channel = 95, $cat_name = 'teknoloji');
		$_new->rss_fetch($list_rss_source[96], $channel = 96, $cat_name = 'konut');
		$_new->rss_fetch($list_rss_source[86], $channel = 86, $cat_name = 'none');
	}

	if($type == 'uzmanpara')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[97], $channel = 97);
	}

	if($type == 'oncevatan')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[98], $channel = 98);
	}

	if($type == 'posta')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[99], $channel = 99);
	}

	if($type == 'sozcu')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[100], $channel = 100);
	}

	if($type == 'zaman')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[102], $channel = 102);
	}

	if($type == 'taraf')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[103], $channel = 103);
	}

	if($type == 'fotomac')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[104], $channel = 104);
	}

	if($type == 'fanatik')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[105], $channel = 105);
	}

	if($type == 'turkiye')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[106], $channel = 106);
	}

	if($type == 'gunes')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[107], $channel = 107);
	}

	if($type == 'amkspor')
	{
		//yarım saatte bir çalışması yeterli
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[108], $channel = 108);
	}

	if($type == 'anayurt')
	{
		//yarım saatte bir çalışması yeterli
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[109], $channel = 109);
	}

	if($type == 'ortadogu')
	{
		//yarım saatte bir çalışması yeterli
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[110], $channel = 110);
	}

	if($type == 'yenicag')
	{
		//yarım saatte bir çalışması yeterli
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[111], $channel = 111);
	}

	if($type == 'vatan')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[112], $channel = 112, $cat_name = 'gundem');
		$_new->rss_fetch($list_rss_source[113], $channel = 113, $cat_name = 'ekonomi');
		$_new->rss_fetch($list_rss_source[114], $channel = 114, $cat_name = 'teknoloji');
		$_new->rss_fetch($list_rss_source[115], $channel = 115, $cat_name = 'yasam');
		$_new->rss_fetch($list_rss_source[116], $channel = 116, $cat_name = 'magazin');
		$_new->rss_fetch($list_rss_source[118], $channel = 118, $cat_name = 'dunya');
		$_new->rss_fetch($list_rss_source[119], $channel = 119, $cat_name = 'saglik');
		$_new->rss_fetch($list_rss_source[120], $channel = 120, $cat_name = 'egitim');
		$_new->rss_fetch($list_rss_source[117], $channel = 117, $cat_name = 'futbol');
	}

	if($type == 'cumhuriyet')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[121], $channel = 121);
	}

	if($type == 'yenimesaj')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[122], $channel = 122);
	}

	if($type == 'yeniasya')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[123], $channel = 123);
	}

	if($type == 'yarinabakis')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[124], $channel = 124);
	}

	if($type == 'yenihayat')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[125], $channel = 125);
	}

	if($type == 'meydan')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[126], $channel = 126);
	}

	if($type == 'yurt')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[127], $channel = 127);
	}

	if($type == 'aydinlik')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[128], $channel = 128);
	}

	if($type == 'odatv')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[130], $type);
	}

	if($type == 'superhaber')
	{
		$_new = new $type();
		$_new->rss_fetch($list_rss_source[131], $type);
	}
