<?php

	class sabah
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

					//kategori seçimi yapıyoruz
					$tlink = str_replace('http://www.sabah.com.tr/','', $list[$i]['link']);
					$tlink = explode('/', $tlink);

					$list[$i]['cat']	= intval($list_cat[$t = format_url($tlink[0])]);

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
						if(strpos_array($list[$i]['link'], array('http://www.sabah.com.tr/')) == true)
						{
							$parser	= 'sabah_www';
						}

						//belli url mantıklarına izin vermiyoruz
						//galeri içerik veya video dönüyorlar
						$array_url  = array(
							'http://www.sabah.com.tr/webtv/',
							'http://www.sabah.com.tr/galeri/',
							'http://www.sabah.com.tr/yazarlar/',
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
								'cache_object'	=> 'sabah',		//hangi objeyi kullanacağı
								'cache_parser'	=> $parser,		//hangi parser kullanacağız
								'cache_channel'	=> $channel,	//hangi rss sağlayıcıdan geldi
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

		public function sabah_www($data, $type = 'text')
		{
			if($data == '') return '';

			//gelen datadan ihtiyacımız olan kısmı alıyoruz
			//bu içerik türü için sadece metine ihtiyacımız olduğundan metin alıp geri dönüyoruz
			//html datayı çıkartırken simple_html_dom.php dosyasını kullanıyoruz
			if($type == 'text')
			{
				//kafa karıştıran kimi şeyleri baştan değiştirelim
				$data = str_replace( array(
						'target="_blank" class="tkktLnk" rel="tag"', '��',
				),'',$data );
				$html = str_get_html($data);

				//echo $data;

				//tüm linkleri temizleyelim
				//js scriptlerini temizleyelim
				//other-news benzer yazılarını temizleyelim
				foreach($html->find('a') as $e)							$e->href = '';
				foreach($html->find('script') as $e)					$e->outertext = '';
				foreach($html->find('img') as $e) 						$e->width = '';
				foreach($html->find('img') as $e) 						$e->height = '';

				foreach($html->find('article#contextual h2') as $e)		$e->outertext = '';
				foreach($html->find('.sty1') as $e)						$e->outertext = '';
				foreach($html->find('.sty2') as $e)						$e->outertext = '';
				foreach($html->find('.sty3') as $e)						$e->outertext = '';
				foreach($html->find('.photo') as $e)					$e->outertext = '';
				foreach($html->find('noscript') as $e)					$e->outertext = '';
				foreach($html->find('article h2') as $e)				$e->outertext = '';
				foreach($html->find('article div.banner') as $e)		$e->outertext = '';

				//en son metni alalım
				foreach($html->find('article#contextual') as $e)		$text = $e->innertext;
				if($text == '')
				{
					foreach($html->find('article') as $e)				$text = $e->innertext;
				}

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
				preg_match('/<meta name="twitter:title" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h1 bulalım
					foreach($html->find('.detay h1') as $e) $text = $e->plaintext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				preg_match('/<meta name="twitter:description" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h2 bulalım
					foreach($html->find('article#contextual h2') as $e) $text = $e->plaintext;

					//bellek boşaltıyoruz
					$html->clear();

					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
				$html = str_get_html($data);

				foreach($html->find('div.image img') as $e) $text = $e->src;

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);

				return trim(strip_tags($text));
			}
		}
	}
