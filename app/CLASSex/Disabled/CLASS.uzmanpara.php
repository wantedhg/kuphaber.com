<?php

	class uzmanpara
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function rss_fetch($url, $channel = 1)
		{
			global $_cache, $list_cat;

			$data_curl = curl_get_data($url);

			//gelen data iso olduğu için utf-8 e dönüştürüyoruz
			$data_curl = mb_convert_encoding($data_curl, "UTF-8", "windows-1254");

			//gelen datadan channel kısmını alıyoruz; diğer bilgilerde koruyucu kısımlar olabiliyor
			$data_curl = str_replace(array(
				'<?xml version="1.0" encoding="windows-1254" ?>',
				'<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:taxo="http://purl.org/rss/1.0/modules/taxonomy/" version="2.0">',
				'</rss>',
			), '', $data_curl);

			//echo $data_curl;

			$data_curl = preg_replace('/<dc:language>([\w\W]*?)<\/dc:language>/', '', $data_curl);
			$data_curl = preg_replace('/<dc:rights>([\w\W]*?)<\/dc:rights>/', '', $data_curl);
			$data_curl = preg_replace('/<copyright>([\w\W]*?)<\/copyright>/', '', $data_curl);
			$data_curl = preg_replace('/<language>([\w\W]*?)<\/language>/', '', $data_curl);
			$data_curl = preg_replace('/<image>([\w\W]*?)<\/image>/', '', $data_curl);
			$data_curl = preg_replace('/<description>([\w\W]*?)<\/description>/', '', $data_curl);
			$data_curl = preg_replace('/<path>([\w\W]*?)<\/path>/', '', $data_curl);
			$data_curl = preg_replace('/<title>([\w\W]*?)<\/title>/', '', $data_curl);
			$data_curl = preg_replace('/<guid isPermaLink="false">([\w\W]*?)<\/guid>/', '', $data_curl);

			$data = simplexml_load_string(trim($data_curl));
			$adet = count($data->item);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['link']	= trim(strip_tags($data->item[$i]->link));
					$list[$i]['time']	= date('Y-m-d H:i:s', strtotime(strip_tags($data->item[$i]->pubDate)));
					//kategori seçimi yapıyoruz
					$list[$i]['cat']	= 3;
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
						if(strpos_array($list[$i]['link'], array('http://uzmanpara.milliyet.com.tr/')) == true)
						{
							$parser	= 'uzmanpara_www';
						}

						//belli url mantıklarına izin vermiyoruz
						//galeri içerik veya video dönüyorlar
						$array_url  = array(
							'http://uzmanpara.milliyet.com.tr/haber-detay/yazarlar/',
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
								'cache_object'	=> 'uzmanpara',		//hangi objeyi kullanacağı
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

		public function uzmanpara_www($data, $type = 'text')
		{
			if($data == '') return '';

			//gelen data iso olduğu için utf-8 e dönüştürüyoruz
			$data = mb_convert_encoding($data, "UTF-8", "windows-1254");


			//tüm datayı etkileyen saçmalığı burada düzeltelim
			//$data = str_replace('http://uzmanpara.milliyet.com.tr//','http://uzmanpara.milliyet.com.tr/',$data);

			//gelen datadan ihtiyacımız olan kısmı alıyoruz
			//bu içerik türü için sadece metine ihtiyacımız olduğundan metin alıp geri dönüyoruz
			//html datayı çıkartırken simple_html_dom.php dosyasını kullanıyoruz
			if($type == 'text')
			{
				$html = str_get_html($data);

				//tüm linkleri temizleyelim
				//js scriptlerini temizleyelim
				//other-news benzer yazılarını temizleyelim
				foreach($html->find('a') as $e)							$e->href = '';
				foreach($html->find('script') as $e)					$e->outertext = '';
				foreach($html->find('img') as $e) 						$e->width = '';
				foreach($html->find('img') as $e) 						$e->height = '';
 				foreach($html->find('div#inread') as $e)				$e->outertext = '';
 				foreach($html->find('div.clear') as $e)					$e->outertext = '';
 				foreach($html->find('iframe') as $e)					$e->outertext = '';
				foreach($html->find('#budivi div.detTitle') as $e)		$e->outertext = '';
				foreach($html->find('#budivi div.detPic') as $e)		$e->outertext = '';
				foreach($html->find('#budivi div.clear') as $e)			$e->outertext = '';
				foreach($html->find('#text_reklam_1') as $e)			$e->outertext = '';
				foreach($html->find('div#Gad-853') as $e)				$e->outertext = '';

				//en son metni alalım
				foreach($html->find('div#budivi') as $e)					$text = $e->innertext;

				//alt ve title değerlerini temizleyelim
				$text = preg_replace('/alt="([\w\W]*?)"/', '', $text);
				$text = preg_replace('/title="([\w\W]*?)"/', '', $text);

				//kapanmayan etiketlerin önündeki boşlukları silelim
				$text = str_replace(array('  >', ' >'), '>', $text);

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
				return $text;
			}

			if($type == 'title')
			{
				$html = str_get_html($data);

				//h1 bulalım
				foreach($html->find('h1') as $e) $text = $e->plaintext;

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);

				return $text;
			}

			if($type == 'desc')
			{
				preg_match('/<meta name="twitter:description" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h2 bulalım
					foreach($html->find('div.detL b[1]') as $e) $text = $e->plaintext;

					//bellek boşaltıyoruz
					$html->clear();

					unset($html);
				}
				return $text;
			}

			if($type == 'image')
			{
				preg_match('/meta property="og:image" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);


					foreach($html->find('div.detPic img') as $e) $text = $e->src;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}

				return trim(strip_tags($text));
			}
		}
	}
