<?php

	class ortadogu
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function rss_fetch($url, $channel = 1)
		{
			global $_cache;

			$data_curl = curl_get_data($url);

			//nesneye kolay erişim için küçük bir değişiklik yapıyoruz
			$data_curl = str_replace('news:', 'news_', $data_curl);
			$data = simplexml_load_string($data_curl);
			$adet = count($data->url);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['link']	= trim(strip_tags($data->url[$i]->loc));
					//saçma bir tarih döndüğü için tarihi hiç kabul etmeyelim
					$list[$i]['time']	= '';

					//kategori seçimi yapıyoruz
					$list[$i]['cat']	= 0;
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
						if(strpos_array($list[$i]['link'], array ( 'http://ortadogugazetesi.net/', 'http://www.ortadogugazetesi.net/' )) == true)
						{
							$parser	= 'ortadogu_www';
						}

						//belli url mantıklarına izin vermiyoruz
						//galeri içerik veya video dönüyorlar
						$array_url  = array(
							'http://ortadogugazetesi.net/yazarlar.php',
							'http://www.ortadogugazetesi.net/yazarlar.php',
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
								'cache_object'	=> 'ortadogu',	//hangi objeyi kullanacağı
								'cache_parser'	=> $parser,		//hangi parser kullanacağız
								'cache_channel'	=> $channel,	//hangi rss sağlayıcıdan geldi
								'cache_status'	=> 0,
							);
							//parser var ise
							if($parser <> '')
							{
								$rs = $this->conn->AutoExecute(T_CACHE, $record, 'INSERT');
								if($rs == false)
								{
									//print_pre($record);
									throw new Exception($this->conn->ErrorMsg());
								}
								echo '.';
							}
						}
					}
					else
					{
						echo '!';
					}
				}
			}
		}

		public function ortadogu_www($data, $type = 'text')
		{
			global $list_cat;

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

				//istenmeyen tüm STYLE inline atribue tanımlamalarını siliyoruz
				foreach($html->find("*[style]") as $e) 					$e->style = null;

				//en son metni alalım
				foreach($html->find('div#objectContent') as $e)			$text = $e->innertext;

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
				return trim(html_entity_decode($text));
			}

			if($type == 'title')
			{
				preg_match('/meta property="og:title" content="([\w\W]*?)"\/>/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					foreach($html->find('h1.hbr_title2') as $e)	$text = $e->plaintext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
 				preg_match('/meta property="og:image" content="([\w\W]*?)"\/>/', $data, $split);
 				$text = strip_tags($split[1]);

				return trim(strip_tags($text));
			}

			if($type == 'cat')
			{
				$html = str_get_html($data);

				foreach($html->find('h2.hbox_title2') as $e) $text = $e->plaintext;

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);

				//gereksiz karakteri ayıklıyoruz
				$text = trim(strip_tags(str_replace(array('Diğer','Haberleri'),'',$text)));

				return intval($list_cat[$t = format_url($text)]);
			}

			if($type == 'time')
			{

				//saçma bir şekilde ulaşamadığımız etiketin
				//kendisini değiştirerek ulaşılır kıldık
				$data = str_replace('<em> / ','<date>',$data);
				$data = str_replace('</em>','</date>',$data);

				$html = str_get_html($data);

				foreach($html->find('div.left date') as $e) 	$text = $e->plaintext;

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);

				return trim(strip_tags($text));
			}
		}
	}
