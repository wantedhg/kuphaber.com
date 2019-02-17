<?php

	class yeniyuzyil
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function rss_fetch($url, $channel = 1)
		{
			global $_cache, $list_cat;

			$data_curl = curl_get_data($url);

			//gelen datadan channel kısmını alıyoruz; diğer bilgilerde koruyucu kısımlar olabiliyor
			$data_curl = str_replace(array(
				'&',
				'<?xml version=\'1.0\' encoding=\'UTF-8\' ?> ',
				'<rss version="2.0">','</rss>',
			), '', $data_curl);

			$data_curl = preg_replace('/<description>([\w\W]*?)<\/description>/', '', $data_curl);
			$data_curl = preg_replace('/<path>([\w\W]*?)<\/path>/', '', $data_curl);
			$data_curl = preg_replace('/<title>([\w\W]*?)<\/title>/', '', $data_curl);

			$data = simplexml_load_string(trim($data_curl));
			$adet = count($data->item);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['link']	= trim(strip_tags($data->item[$i]->link));
					$list[$i]['time']	= date('Y-m-d H:i:s', strtotime(strip_tags($data->item[$i]->pubDate)));

					//kategori seçimi yapıyoruz
					$tlink = str_replace('http://www.gazeteyeniyuzyil.com/','', $list[$i]['link']);
					$tlink = explode('/', $tlink);

					$list[$i]['cat']	= intval($list_cat[$t = format_url($tlink[1])]);

					//sunucu saati farkı sebebiyle rss tarihleri hatalı
					//onları bizim tarih dilimine çevirelim
					$list[$i]['time'] = convert_to_turkish_time( $list[$i]['time'], $hour = '-3 hour');
				}
			}
			//rss'i parse ettik, artık veritabanına alabiliriz
 			//print_pre($list);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					//kayıttın önce bu içerik belleklenmiş mi kontrol edelim
					if($_cache->content_cached($list[$i]['link']) == false)
					{
						//hata sayısını sıfırlayalım ki break edip hepsi hata diye kırılmasın
						$hata = 0;

						//parser seçimi yapıyoruz
						if(strpos_array($list[$i]['link'], array('http://www.gazeteyeniyuzyil.com/')) == true)
						{
							$parser	= 'yeniyuzyil_www';
						}

						//belli url mantıklarına izin vermiyoruz
						//galeri içerik veya video dönüyorlar
						$array_url  = array(
							'http://www.gazeteyeniyuzyil.com/foto-galeri/',
							'http://www.gazeteyeniyuzyil.com/makale/',
						);
						if(strpos_array($list[$i]['link'], $array_url) == true)
						{
							$hata = 1;
						}


						if($hata <> 1)
						{
							//içeriği belleğe alalım
							$record = array(
								'cache_link'	=> $list[$i]['link'],
								'cache_time'	=> $list[$i]['time'],
								'cache_cat'		=> $list[$i]['cat'],
								'cache_object'	=> 'yeniyuzyil',	//hangi objeyi kullanacağı
								'cache_parser'	=> $parser,			//hangi parser kullanacağız
								'cache_channel'	=> $channel,		//hangi rss sağlayıcıdan geldi
								'cache_status'	=> 0,
							);
 							$rs = $this->conn->AutoExecute(T_CACHE, $record, 'INSERT');
							if($rs == false)
							{
								print_pre($record);
								throw new Exception($this->conn->ErrorMsg());
							}
							echo '.';
						}
					}
					else
					{
						echo '!';
					}
				}
			}
		}

		public function yeniyuzyil_www($data, $type = 'text')
		{
			if($data == '') return '';

			//tüm datayı etkileyen saçmalığı burada düzeltelim
			$data = str_replace('http://www.gazeteyeniyuzyil.com//','http://www.gazeteyeniyuzyil.com/',$data);

			//gelen datadan ihtiyacımız olan kısmı alıyoruz
			//bu içerik türü için sadece metine ihtiyacımız olduğundan metin alıp geri dönüyoruz
			//html datayı çıkartırken simple_html_dom.php dosyasını kullanıyoruz
			if($type == 'text')
			{
				//kafa karıştıran kimi şeyleri baştan değiştirelim
				$data = str_replace( array(
						'<br type="_moz" />',
						'#Sayfa#<br />',
						'<!-- start:row-highlights -->',
						'<div class="row row-highlights notices">',
						'<div class="col-sm-9">',
				),'',$data );
				$data = html_entity_decode($data);
				$html = str_get_html($data);

				//echo $data;

				//tüm linkleri temizleyelim
				//js scriptlerini temizleyelim
				//other-news benzer yazılarını temizleyelim
				foreach($html->find('a') as $e)							$e->href = '';
				foreach($html->find('script') as $e)					$e->outertext = '';
				foreach($html->find('img') as $e) 						$e->width = '';
				foreach($html->find('img') as $e) 						$e->height = '';
				//ihtiyaç fazlası şeyler temizleniyor
				foreach($html->find('*.lead') as $e)					$e->outertext = '';
				foreach($html->find('*.head-image') as $e)				$e->outertext = '';
				foreach($html->find('*.highlights') as $e)				$e->outertext = '';


				//en son metni alalım
				foreach($html->find('article#article-post') as $e)		$text = $e->innertext;

				//metin yoksa alternatif metin
				if($text == '')
				{
					//foreach($html->find('div.row div.col-sm-12') as $e)	$text = $e->innertext;
				}

				//alt ve title değerlerini temizleyelim
				$text = preg_replace('/alt="([\w\W]*?)"/', '', $text);
				$text = preg_replace('/title="([\w\W]*?)"/', '', $text);

				//resim yollarını düzenleyelim
				$text = str_replace( '/img/news//', '/img/news/', $text);
				$text = str_replace( '/img/detay//', '/img/detay/', $text);

				$text = str_replace( '/img/news/', 'http://www.gazeteyeniyuzyil.com/img/news/', $text);
				$text = str_replace( '/img/detay/', 'http://www.gazeteyeniyuzyil.com/img/detay/', $text);

				//kapanmayan etiketlerin önündeki boşlukları silelim
				$text = str_replace(array('  >', ' >'), '>', $text);

				//footnote kaldıralım
				$text = str_replace('YENİ YÜZYIL GAZETESİ', '', $text);

				//metin çok kısa ise boş verelim,
				//bunun için de tagları temizleyip metni ölçelim
				if( strlen(strip_tags($text)) < 200)
				{
					$text = '';
				}

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);

				//sonucu dönüyoruz
				return trim(html_entity_decode($text));
			}

			if($type == 'title')
			{
				preg_match('/<meta name="twitter:title" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h1 bulalım
					foreach($html->find('header h1') as $e) $text = $e->plaintext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				$html = str_get_html($data);

				//h2 bulalım
				foreach($html->find('article#article-post *.lead') as $e) $text = $e->plaintext;

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);

				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
				preg_match('/<meta name="twitter:image" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);


					foreach($html->find('img.img-responsive') as $e) $text = $e->src;

					if($text <> '')
					{
						$text = 'http://www.gazeteyeniyuzyil.com'.$text;
					}

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}

				return trim(strip_tags($text));
			}
		}
	}
