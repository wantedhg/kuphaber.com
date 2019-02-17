<?php

	class mansetoku
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
			preg_match('/<channel>([\w\W]*?)<\/channel>/', $data_curl, $data);

			$data = simplexml_load_string($data[0]);
			$adet = count($data->item);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['link']	= trim(strip_tags($data->item[$i]->link));
					$list[$i]['time']	= date('Y-m-d H:i:s', strtotime(strip_tags($data->item[$i]->pubDate)));
					$list[$i]['cat']	= intval($list_cat[$t = format_url(strip_tags($data->item[$i]->category))]);
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
						if(strpos_array($list[$i]['link'], array('http://www.mansetoku.com.tr/')) == true)
						{
							$parser	= 'mansetoku_www';
						}

						//belli url mantıklarına izin vermiyoruz
						//galeri içerik veya video dönüyorlar
						$array_url  = array(
							'-galeri',
							'-video',
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
								'cache_object'	=> 'mansetoku',			//hangi objeyi kullanacağı
								'cache_parser'	=> $parser,				//hangi parser kullanacağız
								'cache_channel'	=> $channel,			//hangi rss sağlayıcıdan geldi
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

		public function mansetoku_www($data, $type = 'text')
		{
			if($data == '') return '';
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

				foreach($html->find('div.ilgiliHaber') as $e)			$e->outertext = '';

				//en son metni alalım
				foreach($html->find('div.detay_haber_text') as $e)		$text = $e->innertext;

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
				foreach($html->find('h3.detay_anabaslik') as $e) $text = $e->innertext;

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);
				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				$html = str_get_html($data);

				//spot alanındaki tarihi silelim
				foreach($html->find('span.detay_haber_tarihi') as $e)	$e->outertext = '';

				//spot bulalım
				foreach($html->find('div.detay_spot') as $e) 			$text = $e->innertext;

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);

				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
				preg_match('/meta property="og:image" content="([\w\W]*?)"\/>/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);


					foreach($html->find('figure.detay_resim img') as $e) 	$text = $e->src;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}
		}
	}
