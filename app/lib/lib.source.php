<?php
	if(!defined('APP')) die('...');

	$list_cat = array(
		'guncel'				=> 1,
		'gundem'				=> 1,
		'gundem-haberleri'		=> 1,
		'roportaj'				=> 1,
		'sondakika'				=> 1,
		'son-dakika'			=> 1,
		'yorum'					=> 1,
		'gunun-icinden' 		=> 1,
		'editorunsectikleri'	=> 1,
		'yurt-haber'			=> 1,
		'turkiye'				=> 1,

		'spor'					=> 2,
		'spor-haberleri'		=> 2,
		'futbol'				=> 2,
		'basketbol'				=> 2,
		'turkiye-kupa'			=> 2,

		'ekonomi'				=> 3,
		'ekonomi-haberleri'		=> 3,
		'emlak'					=> 3,
		'emlakoto'				=> 3,
		'turizm'				=> 3,
		'finans'				=> 3,
		'kobi'					=> 3,
		'sirketler'				=> 3,
		'ihracat'				=> 3,
		'sgk'					=> 3,
		'otomobil'				=> 3,
		'tatil'					=> 3,
		'tatilturizm'			=> 3,
		'konut'					=> 3,

		'siyaset'				=> 4,
		'politika'				=> 4,

		'dunya'					=> 5,
		'dunyadan'				=> 5,
		'dunya-haberleri'		=> 5,
		'global'				=> 5,

		'yasam'					=> 6,
		'hayat'					=> 6,
		'hayat-haberleri'		=> 6,
		'saglik'				=> 6,
		'egitim'				=> 6,
		'doga'					=> 6,
		'hayvan'				=> 6,
		'doga-hayvan'			=> 6,
		'aile-saglik'			=> 6,

		'kadin'						=> 7,
		'kadinsaglik'				=> 7,
		'moda'						=> 7,
		'magazin'					=> 7,
		'life'						=> 7,
		'cumartesi'					=> 7,
		'pazar'						=> 7,
		'saklambac'					=> 7,
		'magazin-haberleri'			=> 7,
		'son-moda'					=> 7,
		'gurme'						=> 7,
		'seyahat'					=> 7,

		'sinema'					=> 8,
		'sinema-tv'					=> 8,
		'televizyon'				=> 8,

		'teknoloji'					=> 9,
		'teknoloji-haberleri'		=> 9,
		'bilim'						=> 9,
		'bilim-teknik'				=> 9,
		'bilim-teknoloji'			=> 9,
		'bilim-ve-teknoloji'		=> 9,

		'kultur'					=> 10,
		'sanat'						=> 10,
		'edebiyat'					=> 10,
		'kultursanat'				=> 10,
		'kultur_sanat'				=> 10,
		'kultur-sanat'				=> 10,
		'kultur-ve-sanat' 			=> 10,
		'kultur-sanat-haberleri'	=> 10,
	);

	$list_rss_source = array(
		/*
		//mynet genel
		1	=> 'http://haber.mynet.com/rss/sondakika',
		2	=> 'http://haber.mynet.com/rss/gununozeti',
		3	=> 'http://haber.mynet.com/rss/kategori/guncel',
		4	=> 'http://haber.mynet.com/rss/kategori/politika',
		5	=> 'http://haber.mynet.com/rss/kategori/teknoloji',
		6	=> 'http://haber.mynet.com/rss/kategori/dunya',
		7	=> 'http://haber.mynet.com/rss/kategori/yasam',
		8	=> 'http://haber.mynet.com/rss/kategori/magazin',
		9	=> 'http://haber.mynet.com/rss/kategori/saglik',
		//mynet finans
		10	=> 'http://finans.mynet.com/haber/rss/sondakika',
		11	=> 'http://finans.mynet.com/haber/rss/gununozeti',
		12	=> 'http://finans.mynet.com/haber/rss/kategori/borsa',
		13	=> 'http://finans.mynet.com/haber/rss/kategori/ekonomi',
		14	=> 'http://finans.mynet.com/haber/rss/kategori/dunya',
		15	=> 'http://finans.mynet.com/haber/rss/kategori/doviz',
		16	=> 'http://finans.mynet.com/haber/rss/kategori/emlak',
		17	=> 'http://finans.mynet.com/haber/rss/kategori/otomotiv',
		18	=> 'http://finans.mynet.com/haber/rss/kategori/turizm',
		19	=> 'http://finans.mynet.com/haber/rss/kategori/analiz',
		//mynet spor
		20 => 'http://spor.mynet.com/rss/',
		21 => 'http://spor.mynet.com/rss.aspx?cat=Football',
		22 => 'http://spor.mynet.com/rss.aspx?cat=Basketball',
		23 => 'http://spor.mynet.com/rss.aspx?cat=MotorSports',
		//mynet sinema
		24 => 'http://sinema.mynet.com/rss/RSS-haber/rss.xml',
		25 => 'http://sinema.mynet.com/rss/RSS-gelecekprogram/rss.xml',
		26 => 'http://sinema.mynet.com/rss/RSS-vizyon/rss.xml',
		27 => 'http://sinema.mynet.com/rss/RSS-enyeniler/rss.xml',
		//yeni akit
		28 => 'http://www.yeniakit.com.tr/haber/rss',
		//haberi yakala
		29 => 'http://www.haberiyakala.com/feed',
		//manşet oku
		30 => 'http://www.mansetoku.com.tr/feed',
		//en son haber
		31 => 'http://www.ensonhaber.com/rss.xml',
		//ensonhaber kralspor
		32 => 'http://kralspor.ensonhaber.com/rss.xml',
		//yeni şafak
		33 => 'http://www.yenisafak.com/rss',
		//star gazetesi
		34 => 'http://www.star.com.tr/rss/rss.asp',
		//sabah ve MALESEF ekleri
		//10 dakikada bir mutlaka çalışmalı
		35 => 'http://www.sabah.com.tr/rss/sondakika.xml',
		36 => 'http://www.sabah.com.tr/rss/anasayfa.xml',
		//bu kısım saatlik çalışabilir
		37 => 'http://www.sabah.com.tr/rss/ekonomi.xml',
		38 => 'http://www.sabah.com.tr/rss/gundem.xml',
		39 => 'http://www.sabah.com.tr/rss/spor.xml',
		40 => 'http://www.sabah.com.tr/rss/dunya.xml',
		41 => 'http://www.sabah.com.tr/rss/gununicinden.xml',
		42 => 'http://www.sabah.com.tr/rss/yasam.xml',
		43 => 'http://www.sabah.com.tr/rss/saglik.xml',
		//bu ekler günlük çalışsa yeter
		44 => 'http://www.sabah.com.tr/rss/teknoloji.xml',
		45 => 'http://www.sabah.com.tr/rss/turizm.xml',
		46 => 'http://www.sabah.com.tr/rss/otomobil.xml',
		47 => 'http://www.sabah.com.tr/rss/emlak.xml',
		48 => 'http://www.sabah.com.tr/rss/egitim.xml',
		49 => 'http://www.sabah.com.tr/rss/kultur_sanat.xml',
		50 => 'http://www.sabah.com.tr/rss/gunaydin.xml',
		51 => 'http://www.sabah.com.tr/rss/cumartesi.xml',
		52 => 'http://www.sabah.com.tr/rss/pazar.xml',
		//akşam gazetesi
		53 => 'http://www.aksam.com.tr/cache/rss/googleNewsStand.xml',
		//dünya
		54 => 'http://www.dunya.com/rss/',
		//karar
		55 => 'http://www.karar.com/rss',
		//müstakil gazete
		57 => 'http://mustakilgazete.com/feed/',
		//takvim
		58 => 'http://www.takvim.com.tr/rss/AnaSayfa.xml',
		59 => 'http://www.takvim.com.tr/rss/Guncel.xml',
		60 => 'http://www.takvim.com.tr/rss/Ekonomi.xml',
		61 => 'http://www.takvim.com.tr/rss/SosyalGuvenlik.xml',
		62 => 'http://www.takvim.com.tr/rss/Saklambac.xml',
		63 => 'http://www.takvim.com.tr/rss/Spor.xml',
		64 => 'http://www.takvim.com.tr/rss/Yasam.xml',
		//vahdet
		65 => 'http://www.vahdetgazetesi.com/rss.php',
		//yeni yüzlık
		66 => 'http://www.gazeteyeniyuzyil.com/rss-content.php?kategori=guncel',
		67 => 'http://www.gazeteyeniyuzyil.com/rss-content.php?kategori=siyaset',
		68 => 'http://www.gazeteyeniyuzyil.com/rss-content.php?kategori=yasam',
		69 => 'http://www.gazeteyeniyuzyil.com/rss-content.php?kategori=ekonomi',
		70 => 'http://www.gazeteyeniyuzyil.com/rss-content.php?kategori=global',
		71 => 'http://www.gazeteyeniyuzyil.com/rss-content.php?kategori=yorum',
		72 => 'http://www.gazeteyeniyuzyil.com/rss-content.php?kategori=spor',
		73 => 'http://www.gazeteyeniyuzyil.com/rss-content.php?kategori=magazin',
		74 => 'http://www.gazeteyeniyuzyil.com/rss-content.php?kategori=egitim',
		75 => 'http://www.gazeteyeniyuzyil.com/rss-content.php?kategori=saglik',
		//habertürk
		76 => 'http://www.haberturk.com/rss',
		//hürriyet
		77 => 'http://www.hurriyet.com.tr/rss/gundem',
		78 => 'http://www.hurriyet.com.tr/rss/egitim',
		79 => 'http://www.hurriyet.com.tr/rss/dunya',
		80 => 'http://www.hurriyet.com.tr/rss/avrupa',
		81 => 'http://www.hurriyet.com.tr/rss/ekonomi',
		82 => 'http://www.hurriyet.com.tr/rss/ik',
		83 => 'http://www.hurriyet.com.tr/rss/spor',
		84 => 'http://www.hurriyet.com.tr/rss/teknoloji',
		85 => 'http://www.hurriyet.com.tr/rss/son-dakika',
		//milliyet
		86 => 'http://www.milliyet.com.tr/D/rss/rss/RssSD.xml',
		87 => 'http://www.milliyet.com.tr/D/rss/rss/Rss_2.xml',
		88 => 'http://www.milliyet.com.tr/D/rss/rss/Rss_3.xml',
		89 => 'http://www.milliyet.com.tr/D/rss/rss/Rss_4.xml',
		90 => 'http://www.milliyet.com.tr/D/rss/rss/Rss_5.xml',
		91 => 'http://www.milliyet.com.tr/D/rss/rss/Rss_23.xml',
		92 => 'http://www.milliyet.com.tr/D/rss/rss/Rss_24.xml',
		93 => 'http://www.milliyet.com.tr/D/rss/rss/Rss_98.xml',
		94 => 'http://www.milliyet.com.tr/D/rss/rss/Rss_32.xml',
		95 => 'http://www.milliyet.com.tr/D/rss/rss/Rss_36.xml',
		96 => 'http://www.milliyet.com.tr/D/rss/rss/Rss_204.xml',
		//uzmanpara
		97 => 'http://uzmanpara.milliyet.com.tr/haber_rss/',
		//öncevatan
		98 => 'http://www.oncevatan.com.tr/rss.php',
		//posta
		99 => 'http://www.posta.com.tr/xml/rss/rss_1_0.xml',
		//sözcü
		100 => 'http://www.sozcu.com.tr/rss.xml',
		//taraf
		103 => 'http://www.taraf.com.tr/feed/',
		//fotomac
		104 => 'http://www.fotomac.com.tr/rss/son24saat.xml',
		//fanatik
		105 => 'http://www.fanatik.com.tr/sitemap/sitemap-news.xml',
		//türkiye
		106 => 'http://www.turkiyegazetesi.com.tr/sitemaps/Sitemap_google_news.xml',
		//güneş
		107 => 'http://www.gunes.com/Xml/googlenews/500',
		//amkspor
		108 => 'http://amkspor.sozcu.com.tr/feed/',
		//anayurt
		109 => 'http://www.anayurtgazetesi.com/sondakika.xml',
		//ortadoğu
		110 => 'http://www.ortadogugazetesi.net/habersitemap.xml',
		//yeniçağ
		111 => 'http://www.yenicaggazetesi.com.tr/rss/',
		//vatan
		112 => 'http://www.gazetevatan.com/rss/gundem.xml',
		113 => 'http://www.gazetevatan.com/rss/ekonomi.xml',
		114 => 'http://www.gazetevatan.com/rss/teknoloji.xml',
		115 => 'http://www.gazetevatan.com/rss/yasam.xml',
		116 => 'http://www.gazetevatan.com/rss/magazin.xml',
		117 => 'http://www.gazetevatan.com/rss/futbol.xml',
		118 => 'http://www.gazetevatan.com/rss/dunya.xml',
		119 => 'http://www.gazetevatan.com/rss/saglik.xml',
		120 => 'http://www.gazetevatan.com/rss/egitim.xml',
		//cumhuriyet
		121 => 'http://www.cumhuriyet.com.tr/rss/son_dakika.xml',
		//Yeni Mesaj
		122 => 'http://www.yenimesaj.com.tr/rss.php',
		//yeni asya
		123 => 'http://www.yeniasya.com.tr/rss/son-dakika',
		//yarına bakış
		124 => 'https://www.yarinabakis.com/feed/',
		//yeni hayat
		125 => 'https://www.yenihayatgazetesi.com/feed',
		//meydan
		126 => 'http://www.meydangazetesi.com.tr/rss.php',
		//yurt
		127 => 'http://yurtgazetesi.com.tr/rss.php',
		//yurt
		128 => 'http://www.aydinlikgazete.com/rss.php',
		*/
		//kitap haber
		129 => 'http://www.kitaphaber.com.tr/feed',
		//Webrazzi
		132 => 'https://webrazzi.com/feed/',
		//sosyalmedya.co
		133 => 'http://sosyalmedya.co/feed/',
		//chip.com.tr
		134 => 'https://servis.chip.com.tr/chiponline.xml',
		//donanım haber
		135 => 'https://www.donanimhaber.com/Rss/Gunluk/Default.aspx',
		//PC Net
		136 => 'https://www.pcnet.com.tr/feed/',
		//PC Net
		137 => 'https://www.aorhan.com/feed',
		//Maxicep.com
		139 => 'https://www.maxicep.com/rss',
		//Technopat
		140 => 'http://feedpress.me/technopat',
		//Shift Delete
		141 => 'http://shiftdelete.net/rss/haberler_yeniden_eskiye',
		//Teknolojioku.com
		142 => 'http://www.teknolojioku.com/teknoloji-haberleri.rss',
		//Tekno Blog
		143 => 'https://www.teknoblog.com/feed/',
		//Teknokulis
		144 => 'http://www.teknokulis.com/rss/anasayfa.xml',
		//Teknolog
		145 => 'http://www.teknolog.com/feed/',
		//teknolojihaber
		146 => 'http://feeds.feedburner.com/teknoloji-haber/teknoloji-haberleri-sitesi?format=xml',
		//teknoloji turu
		147 => 'http://teknolojituru.com/feed/',
		//teknoseyir
		148 => 'https://teknoseyir.com/feed',
		//wear logy
		150 => 'https://www.wearlogy.com/feed/',
		//webtekno.com
		151 => 'http://feeds.feedburner.com/webteknocom?format=xml',
		//gerçek bilim
		152 => 'http://www.gercekbilim.com/feed/',
		//indir.com
		153 => 'http://www.indir.com/rss/haber',
		//androidegel.com
		162 => 'http://www.androidegel.com/feed',
		//haber bilim teknoloji
		164 => 'http://haberbilimteknoloji.com/feed/',
		//savunma ve teknoloji
		165 => 'http://savunmaveteknoloji.com/feed/',
		//siyah gri beyaz
		166 => 'http://www.siyahgribeyaz.com/feeds/posts/default?alt=rss',
		//MSI Dergi
		167 => 'http://www.milscint.com/tr/rss',
		//Stuff
		168 => 'https://www.stuff.com.tr/feed/',
		//TKNLJ
		169 => 'http://www.tknlj.com/feed/',
		//Turkcell Blog
		170 => 'http://blog.turkcell.com.tr/feed',
		//Bigumigu
		172 => 'http://feeds.feedburner.com/bigumigu/TdKf?format=xml',
		//bt gunlugu
		173 => 'http://www.btgunlugu.com/feed/',
		//CEOtudent
		174 => 'https://ceotudent.com/feed/',
		//Cepkolik
		175 => 'https://cepkolik.com/feed/',
		//Dijital Ajanslar
		177 => 'http://www.dijitalajanslar.com/feed/',
		//Bölüm Sonu Canavarı
		178 => 'https://www.bolumsonucanavari.com/Rss/BSC.htm',
		//Donanım Günlüğü
		179 => 'https://donanimgunlugu.com/feed/',
		//Dünya Halleri
		180 => 'https://www.dunyahalleri.com/feed/',
		//Eticaret.com
		181 => 'https://www.eticaret.com/feed/',
		//Fikir Çok
		183 => 'http://fikircok.net/feed',
		//FRPNet
		184 => 'https://frpnet.net/feed',
		//Girişim Haber
		185 => 'http://www.girisimhaber.com/syndication.axd?format=rss',
		//Hardware Plus
		187 => 'https://hwp.com.tr/feed',
		//Level Dergisi
		190 => 'https://www.level.com.tr/feed',
		//LOG
		191 => 'http://www.log.com.tr/feed/',
		//Scroll
		194 => 'http://www.scroll.com.tr/feed/',
		//Tech Inside
		197 => 'https://www.techinside.com/feed/',
		//Technosfer
		198 => 'https://technosfer.com/feed/',
		//Tek Doz Dijital
		199 => 'http://www.tekdozdijital.com/feed',
		//Teknolojik Anneler
		204 => 'http://teknolojikanneler.com/feed/',
		//Teknoloji Gündem
		206 => 'http://www.teknolojigundem.com/rss',
		//Dijital Age
		207 => 'http://digitalage.com.tr/feed/',
		//Computer World
		209 => 'http://www.computerworld.com.tr/feed/',
		//Techno Today
		210 => 'http://technotoday.com.tr/rss',
		//BT Haber
		211 => 'http://www.bthaber.com/rss.aspx',
		//tamindir.com
		212 => 'http://feeds.feedburner.com/tamindir/stream?format=xml',
		//Technolabs
		216 => 'http://www.technolabs.net/rss.asp',
		//İyziCo
		218 => 'https://www.iyzico.com/blog/feed/',
		//Webtures
		220 => 'https://www.webtures.com.tr/blog/feed/',
		//PC Hocası
		222 => 'https://pchocasi.com.tr/feed/',
		//Bt Net
		223 => 'http://www.btnet.com.tr/feed/',
		//Elektrik Port
		224 => 'http://www.elektrikport.com',
		//Ars Technica
		228 => 'http://feeds.arstechnica.com/arstechnica/index?format=xml',
		//ZD Net
		234 => 'http://www.zdnet.com/news/rss.xml',
		//Tech Crunch
		235 => 'https://techcrunch.com/feed/',
		//The Verge
		236 => 'https://www.theverge.com/tech/rss/index.xml',
		//Audio Holics
		237 => 'http://www.audioholics.com/rss.xml',
		//Buzfeed Tech
		238 => 'https://www.buzzfeed.com/tech.xml',
		//Destructoid
		239 => 'https://feeds.feedburner.com/Destructoid-Rss?format=xml',
		//Bağımsız Havacılar
		240 => 'http://www.bagimsizhavacilar.com/feed/',
		//Air Kule
		241 => 'http://www.airkule.com/sondakika.xml',
		//Airport Haber
		242 => 'http://www.airporthaber.com/rss/',
		//Oyungezer
		243 => 'http://oyungezer.com.tr/haber?format=feed&type=rss',
		//airnewstimes
		244 => 'http://www.airnewstimes.com/rss/',
		//My Drone Land
		245 => 'http://mydroneland.com',
		//Defence Turkey
		246 => 'http://www.defenceturkey.com/rss.xml',
		//Mönch Türkiye
		247 => 'http://monch.com.tr/TR,1/ana-sayfa.html',
		//Turk Defence News
		248 => 'http://news.turkdefence.com/feed',
		//Engadget
		249 => 'https://www.engadget.com/rss.xml',
		//Gizmodo
		250 => 'http://gizmodo.com/rss',
		//Kotaku
		251 => 'https://kotaku.com/rss',
		//MacWorld
		252 => 'https://www.macworld.com/index.rss',
		//Pc World
		253 => 'https://www.pcworld.com/index.rss',
		//Computer World
		254 => 'https://www.computerworld.com/index.rss',
		//CİO
		255 => 'https://www.cio.com/index.rss',
		//Technologic
		256 => 'http://feeds.feedburner.com/technologictr?format=xml',
		//Defence by Trex
		259 => 'http://turksavunmasektoru.com/feed/',
		//Arkeofili
		260 => 'http://arkeofili.com/feed/',
		//BilimFili
		261 => 'https://bilimfili.com/feed/',
		//Evrim Ağacı
		262 => 'http://www.evrimagaci.org/rss.xml',
		//Kozmik Anafor
		263 => 'http://www.kozmikanafor.com/feed/',
		//Kuark Bilim Topluluğu
		264 => 'http://www.kuark.org/feed/',
		//PopSci
		265 => 'https://popsci.com.tr/feed/',
		//EoroNews Bilim
		266 => 'http://feeds.feedburner.com/euronews/tr/sci-tech/',
		//Fizikist
		267 => 'https://www.fizikist.com/rss/',
		//Eğitim Pedia
		268 => 'http://www.egitimpedia.com/feed/',
		//nBeyin
		269 => 'https://nbeyin.com.tr/feed/',
		//Bilim.org
		270 => 'http://www.bilim.org/feed/',
	);

	$list_sources['gazete']	= array(
// 		'aksam' 				=> 'Akşam',
// 		'anayurt' 				=> 'Anayurt',
// 		'aydinlik' 				=> 'Aydınlık',
// 		'cumhuriyet' 			=> 'Cumhuriyet',
// 		'dunya' 				=> 'Dünya',
// 		'gunes' 				=> 'Güneş',
// 		'haberturk' 			=> 'Haber Türk',
// 		'hurriyet' 				=> 'Hürriyet',
// 		'karar' 				=> 'Karar',
// 		'milliyet' 				=> 'Milliyet',
// 		'mustakil' 				=> 'Müstakil',
// 		'ortadogu' 				=> 'Ortadoğu',
// 		'oncevatan' 			=> 'Önce Vatan',
// 		'posta' 				=> 'Posta',
// 		'sabah' 				=> 'Sabah',
// 		'sozcu' 				=> 'Sözcü',
// 		'star' 					=> 'Star',
// 		'takvim' 				=> 'Takvim',
// 		'turkiye' 				=> 'Türkiye',
// 		'vahdet' 				=> 'Vahdet',
// 		'vatan' 				=> 'Vatan',
// 		'yarinabakis' 			=> 'Yarına Bakış',
// 		'yenicag' 				=> 'Yeniçağ',
// 		'akit' 					=> 'Yeni Akit',
// 		'yeniasya' 				=> 'Yeni Asya',
// 		'yenihayat' 			=> 'Yeni Hayat',
// 		'yenimesaj' 			=> 'Yeni Mesaj',
// 		'yenisafak' 			=> 'Yeni Şafak',
// 		'yeniyuzyil' 			=> 'Yeni Yüzyıl',
// 		'yurt' 					=> 'Yurt',
	);

	$list_sources['gazete_spor']	= array(
// 		'amkspor' 				=> 'AMK Spor',
// 		'fanatik' 				=> 'Fanatik',
// 		'fotomac' 				=> 'Fotomaç',
	);

	$list_sources['internet']	= array(
// 		'ensonhaber' 			=> 'En Son Haber',
// 		'haberiyakala' 			=> 'Haberi Yakala',
// 		'mansetoku' 			=> 'Manşet Oku',
// 		'mynet' 				=> 'Mynet',
// 		'odatv' 				=> 'ODA TV',
// 		'superhaber' 			=> 'Süper Haber',
// 		'uzmanpara' 			=> 'Uzman Para',
	);

	$list_sources['teknoloji']	= array(
		'androidegel' 			=> 'Androide Gel',
		'aorhan' 				=> 'Aorhan Blog',
		'bigumigu' 				=> 'Bigumigu',
		'bolumsonucanavari' 	=> 'Bölüm Sonu Canavarı',
		'btgunlugu' 			=> 'BT Günlüğü',
		'bthaber' 				=> 'BT Haber',
		'btnet' 				=> 'BTnet.com.tr',
		'ceotudent' 			=> 'CEOtudent',
		'cepkolik' 				=> 'Cepkolik',
		'chip' 					=> 'Chip',
		'computerworldtr' 		=> 'Computer World',
		'digitalage' 			=> 'Digital Age',
		'dijitalajanslar' 		=> 'Dijital Ajanslar',
		'donanimgunlugu' 		=> 'Donanım Günlüğü',
		'donanimhaber' 			=> 'Donanım Haber',
		'dunyahalleri' 			=> 'Dünya Halleri',
		'elektrikport' 			=> 'Elektrik Port',
		'eticaret' 				=> 'Eticaret.com',
		'frpnet' 				=> 'FRPNET',
		'girisimhaber' 			=> 'Girişim Haber',
		'hardwareplus' 			=> 'Hardware Plus',
		'indir' 				=> 'İndir.com',
		'iyzico' 				=> 'İyzico',
		'level' 				=> 'Level',
		'log' 					=> 'LOG',
		'maxicep' 				=> 'Maxicep',
		'merlininkazani' 		=> 'Merlinin Kazanı',
		'oyungezer' 			=> 'Oyungezer',
		'pcnet' 				=> 'Pcnet',
		'pchocasi' 				=> 'PC Hocası',
		'scroll' 				=> 'Scroll',
		'shiftdelete' 			=> 'Shift Delete',
		'sosyalmedya' 			=> 'Sosyalmedya.co',
		'stuff' 				=> 'Stuff',
		'tamindir' 				=> 'Tamindir.com',
		'techinside' 			=> 'Tech Inside',
		'technolabs' 			=> 'TechnoLabs',
		'technologic' 			=> 'TechnoLogic',
		'technopat' 			=> 'Technopat',
		'technosfer' 			=> 'Technosfer',
		'technotoday' 			=> 'Techno Today',
		'tekdozdijital' 		=> 'Tek Doz Dijital',
		'teknoblog' 			=> 'Tekno Blog',
		'teknokulis' 			=> 'Tekno Kulis',
		'teknolog' 				=> 'Teknolog',
		'teknolojigundem' 		=> 'Teknoloji Gündem',
		'teknolojihaber' 		=> 'Teknoloji Haber',
		'teknolojikanneler' 	=> 'Teknolojik Anneler',
		'teknolojioku' 			=> 'Teknoloji Oku',
		'teknolojituru' 		=> 'Teknoloji Turu',
		'teknoseyir' 			=> 'Tekno Seyir',
		'tknlj' 				=> 'TKNLJ',
		'wearlogy' 				=> 'Wearlogy',
		'webrazzi' 				=> 'Webrazzi',
		'webtekno' 				=> 'Webtekno',
		'webtures' 				=> 'Webtures',
	);

	$list_sources['savunma']	= array(
		'airkule' 				=> 'Air Kule',
		'airnewstimes' 			=> 'Air News Times',
		'airporthaber' 			=> 'Airport Haber',
		'bagimsizhavacilar' 	=> 'Bağımsız Havacılar',
		'c4defence' 			=> 'C4 Defence',
		'defenceturkey' 		=> 'Defence Turkey',
		'kokpitaero' 			=> 'Kokpit.aero',
		'msi' 					=> 'MSI',
		'mydroneland' 			=> 'My Drone Land',
		'monch' 				=> 'Mönch Türkiye',
		'savunmaveteknoloji' 	=> 'Savunma ve Teknoloji',
		'siyahgribeyaz' 		=> 'Siyah Gri Beyaz',
		'turkdefence' 			=> 'Turk Defence',
 		'turksavunmasektoru' 	=> 'Türk Savunma Sektörü',
	);

	$list_sources['technology']	= array(
		'arstechnica' 			=> 'Ars Technica',
		'audioholics' 			=> 'Audioholics',
		'buzzfeed' 				=> 'BuzzFeed Tech',
		'cio'			 		=> 'CIO',
		'computerworld' 		=> 'Computer World',
		'destructoid' 			=> 'Destructoid',
		'engadget' 				=> 'Engadget',
		'gizmodo' 				=> 'Gizmodo',
		'kotaku' 				=> 'Kotaku',
		'macworld' 				=> 'Mac World',
		'pcworld' 				=> 'Pc World',
		'techcrunch' 			=> 'TechCrunch',
		'theverge' 				=> 'The Verge',
		'zdnet' 				=> 'ZDNet',
	);

	$list_sources['kultursanat']	= array(
		'kitaphaber' 			=> 'Kitap Haber',
	);

	$list_sources['bilim']	= array(
		'arkeofili' 			=> 'Arkeofili',
		'bilimfili' 			=> 'Bilimfili',
		'bilimorg' 				=> 'Bilim.org',
		'egitimpedia' 			=> 'Eğitim Pedia',
		'euronewsbilim' 		=> 'EuroNews Bilim Teknik',
		'evrimagaci' 			=> 'Evrim Ağacı',
		'fizikist' 				=> 'Fizikist',
		'gercekbilim' 			=> 'Gerçek Bilim',
		'haberbilimteknoloji' 	=> 'Haber Bilim Teknoloji',
		'kozmikanafor'			=> 'Kozmik Anafor',
		'kuark'					=> 'Kuark Bilim Topluluğu',
		'nbeyin'				=> 'nBeyin',
		'popscitr'				=> 'Populer Science Türkiye',
	);

	$list_sources_big = array_merge(
// 		$list_sources['gazete'],
// 		$list_sources['gazete_spor'],
// 		$list_sources['internet'],
		$list_sources['teknoloji'],
		$list_sources['savunma'],
		$list_sources['bilim'],
		$list_sources['kultursanat'],
		$list_sources['technology']
	);


	$list_sources_extended['teknoloji']	= array(
// 		'hurriyet_teknoloji' 	=> 'Hürriyet Teknoloji',
// 		'sabah_teknoloji' 		=> 'Sabah Teknoloji'
	);
