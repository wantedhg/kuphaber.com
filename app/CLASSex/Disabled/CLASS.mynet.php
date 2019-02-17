<?php

	class mynet
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function rss_fetch($feed_type = 'rss', $url, $channel = 1, $cat_type = 1, $cat_name = 'none')
		{
			global $_cache, $list_cat;

			$data_curl = curl_get_data($url);

			/**********************************************************
			* datalarımız RSS formatına uyumlu dönüyorsa
			**********************************************************/
			if($feed_type == 'rss')
			{
				//gelen datadan channel kısmını alıyoruz; diğer bilgilerde koruyucu kısımlar olabiliyor
				preg_match('/<channel>([\w\W]*?)<\/channel>/', $data_curl, $data);

				//echo $data[1];
				//dönen datayı
				$data = simplexml_load_string($data[0]);
				$adet = count($data->item);
				if($adet > 0)
				{
					for($i = 0; $i < $adet; $i++)
					{
											$list[$i]['link']		= trim(strip_tags($data->item[$i]->link));
											$list[$i]['time']		= date('Y-m-d H:i:s', strtotime(strip_tags($data->item[$i]->pubDate)));
						//kategori değerini ana kategoriden bulalım
						if($cat_type == 1)	$list[$i]['cat']		= intval($list_cat[$t = format_url(strip_tags($data->item[$i]->maincategory))]);
						//kategori değerini alt kategoriden bulalım
						if($cat_type == 2)	$list[$i]['cat']		= intval($list_cat[$t = format_url(strip_tags($data->item[$i]->subcategory))]);
						//kategori değerini cat_name değerinden bulalım
						if($cat_type == 0)	$list[$i]['cat']		= intval($list_cat[$t = format_url($cat_name)]);
					}
				}
			}
			/**********************************************************
			* datalarımız RDF formatına uyumlu dönüyorsa
			**********************************************************/
			if($feed_type == 'rdf')
			{
				//dc:date erişimi zor olduğu için pubDate diye değiştiriyoruz
				$data_curl = str_replace('dc:date', 'pubDate', $data_curl);

				$data = simplexml_load_string($data_curl);
				$adet = count($data->item);
				if($adet > 0)
				{
					for($i = 0; $i < $adet; $i++)
					{
						$list[$i]['link']	= trim(strip_tags($data->item[$i]->link));
						$list[$i]['time']	= date('Y-m-d H:i:s', strtotime(strip_tags($data->item[$i]->pubDate)));
						$list[$i]['cat']	= intval($list_cat[$t = format_url($cat_name)]);
					}
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

						if(strpos_array($list[$i]['link'], array('http://finans.mynet.com/', 'http://finanshaber.mynet.com/')) == true)
						{
							$parser	= 'mynet_finans';
						}

						if(strpos_array($list[$i]['link'], array('http://spor.mynet.com/')) == true)
						{
							$parser	= 'mynet_spor';
						}

						if(strpos_array($list[$i]['link'], array('http://sinema.mynet.com/')) == true)
						{
							$parser	= 'mynet_sinema';
						}

						if(strpos_array($list[$i]['link'], array('http://www.mynet.com/')) == true)
						{
							$parser	= 'mynet_www';
						}

						if(strpos_array($list[$i]['link'], array('http://www.mynet.com/magazin/')) == true)
						{
							$parser	= 'mynet_www_magazin';
						}

						//belli url mantıklarına izin vermiyoruz
						//galeri içerik veya video dönüyorlar
						$array_url  = array(
							'http://www.mynet.com/haber/gazeteler/',
							'http://spor.mynet.com/canli-mac-anlatimi-ve-sonuclari/',
							'http://sinema.mynet.com/film/',
							'http://okey.mynet.com/',
							'http://www.mynet.com/tv/',
							'http://galeri.mynet.com',
							'http://otomobil.mynet.com'
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
								'cache_object'	=> 'mynet',				//hangi objeyi kullanacağı
								'cache_parser'	=> $parser,				//hangi parser kullanacağız
								'cache_channel'	=> $channel,			//hangi rss sağlayıcıdan geldi
								'cache_status'	=> 0,
							);
							if($parser <> '')
							{
								$rs = $this->conn->AutoExecute(T_CACHE, $record, 'INSERT');
							}
							else
							{
// 								$uri	= $parser;
// 								$uri.= file_get_contents('cache/parser.errors');
// 								file_put_contents('cache/parser.errors', $uri);
							}
							if($rs == false)
							{
								//print_pre($record);
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

		public function mynet_finans($data, $type = 'text')
		{
			//gelen datadan ihtiyacımız olan kısmı alıyoruz
			//bu içerik türü için sadece metine ihtiyacımız olduğundan metin alıp geri dönüyoruz
			//html datayı çıkartırken simple_html_dom.php dosyasını kullanıyoruz
			if($type == 'text')
			{
				$html = str_get_html($data);

				//tüm linkleri temizleyelim
				//js scriptlerini temizleyelim
				//other-news benzer yazılarını temizleyelim
				foreach($html->find('a') as $e)					$e->href = '';
				foreach($html->find('script') as $e)			$e->outertext = '';
				foreach($html->find('img') as $e) 				$e->width = '';
				foreach($html->find('img') as $e) 				$e->height = '';

				foreach($html->find('div.others-news') as $e)	$e->outertext = '';

				//reklam katmanlarını temizleyelim
				foreach($html->find('div#contentAdv') as $e)	$e->outertext = '';

				//metni alalım
				foreach($html->find('div#contextual') as $e)	$text = $e->innertext;

				//kapanmayan etiketlerin önündeki boşlukları silelim
				$text = str_replace(array('  >', ' >'), '>', $text);

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
				preg_match('/meta property="og:title" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h1 bulalım
					foreach($html->find('h1') as $e) $text = $e->innertext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				preg_match('/<meta property="og:description" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h2 bulalım
					foreach($html->find('h2') as $e) $text = $e->innertext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
				preg_match('/meta property="og:image" content="([\w\W]*?)"/', $data, $split);
				$text = $split[1];

				if($text == '')
				{
					$html = str_get_html($data);


					foreach($html->find('.image img') as $e) $text = $e->src;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}
		}

		public function mynet_spor($data, $type = 'text')
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
				foreach($html->find('a') as $e)					$e->href = '';
				foreach($html->find('script') as $e)			$e->outertext = '';
				foreach($html->find('img') as $e) 				$e->width = '';
				foreach($html->find('img') as $e) 				$e->height = '';

				foreach($html->find('div.others-news') as $e)	$e->outertext = '';

				//reklam katmanlarını temizleyelim
				foreach($html->find('div#contentAdv') as $e)	$e->outertext = '';

				//metni alalım
				foreach($html->find('div#text') as $e)			$text = $e->innertext;

				//kapanmayan etiketlerin önündeki boşlukları silelim
				$text = str_replace(array('  >', ' >'), '>', $text);

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
				preg_match('/<h1>([\w\W]*?)<\/h1>/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h1 bulalım
					foreach($html->find('h1') as $e) $text = $e->innertext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				preg_match('/<h2>([\w\W]*?)<\/h2>/', $data, $split);
				return strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h2 bulalım
					foreach($html->find('h2') as $e) $text = $e->innertext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
				preg_match('/<meta name="twitter:image" content="([\w\W]*?)"/', $data, $split);
				$text = $split[1];

				if($text == '')
				{
					$html = str_get_html($data);


					foreach($html->find('#resim img') as $e) $text = $e->src;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}
		}

		public function mynet_sinema($data, $type = 'text')
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
				foreach($html->find('a') as $e)								$e->href = '';
				foreach($html->find('script') as $e)						$e->outertext = '';
				foreach($html->find('img') as $e) 							$e->width = '';
				foreach($html->find('img') as $e) 							$e->height = '';

				foreach($html->find('div.others-news') as $e)				$e->outertext = '';

				//reklam katmanlarını temizleyelim
				foreach($html->find('div#contentAdv') as $e)				$e->outertext = '';

				//metni alalım
				foreach($html->find('div[itemprop="articleBody"]') as $e) 	$text = $e->innertext;

				//kapanmayan etiketlerin önündeki boşlukları silelim
				$text = str_replace(array('  >', ' >'), '>', $text);

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
				preg_match('/<h1 itemprop="name">([\w\W]*?)<\/h1>/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h1 bulalım
					foreach($html->find('h1') as $e) $text = $e->innertext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				preg_match('/<meta property="og:description" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h2 bulalım
					foreach($html->find('h2') as $e) $text = $e->innertext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
				preg_match('/meta property="og:image" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);


					foreach($html->find('.icerik-imaj-metin-avatar img') as $e) $text = $e->src;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}
		}

		public function mynet_www($data, $type = 'text')
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
				foreach($html->find('a') as $e)								$e->href = '';
				foreach($html->find('script') as $e)						$e->outertext = '';
				foreach($html->find('img') as $e) 							$e->width = '';
				foreach($html->find('img') as $e) 							$e->height = '';

				foreach($html->find('div.others-news') as $e)				$e->outertext = '';

				//banner kodlarını temizleyelim
				foreach($html->find('#div-gpt-ad-1410962145047-2') as $e)	$e->outertext = '';
				foreach($html->find('#300x250body1') as $e)					$e->outertext = '';
				foreach($html->find('#300x250body2') as $e)					$e->outertext = '';
				foreach($html->find('#contentAdv') as $e)					$e->outertext = '';
				foreach($html->find('#readTextdiv') as $e)					$e->outertext = '';
				foreach($html->find('.detailInlineBanner300') as $e)		$e->outertext = '';
				foreach($html->find('.detilInlineBanner300') as $e)			$e->outertext = '';
				foreach($html->find('.detailInlineBannerBox') as $e)		$e->outertext = '';
				foreach($html->find('.newsInfo') as $e)						$e->outertext = '';

				//en son metni alalım
				foreach($html->find('div#contextual') as $e)				$text = $e->innertext;

				//metin boş ise diğer alternatifi deneyelim
				if($text == '')
				{
					//alternatif metni alalım
					foreach($html->find('div.dNewsDetailContainer') as $e)	$text = $e->innertext;
				}

				//kapanmayan etiketlerin önündeki boşlukları silelim
				$text = str_replace(array('  >', ' >'), '>', $text);

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
				preg_match('/meta property="og:title" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h1 bulalım
					foreach($html->find('h1') as $e) $text = $e->innertext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				preg_match('/<meta property="og:description" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h2 bulalım
					foreach($html->find('h2 itemprop["description"]') as $e) $text = $e->innertext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
				preg_match('/meta property="og:image" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);


					foreach($html->find('#imageContent div img') as $e) $text = $e->src;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}
		}

		public function mynet_www_magazin($data, $type = 'text')
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
				foreach($html->find('a') as $e)								$e->href = '';
				foreach($html->find('script') as $e)						$e->outertext = '';
				foreach($html->find('img') as $e) 							$e->width = '';
				foreach($html->find('img') as $e) 							$e->height = '';

				foreach($html->find('div.others-news') as $e)				$e->outertext = '';

				//banner kodlarını temizleyelim
				foreach($html->find('span#300x250body1') as $e)				$e->outertext = '';
				foreach($html->find('span#300x250body2') as $e)				$e->outertext = '';
				foreach($html->find('div#contentAdv') as $e)				$e->outertext = '';
				foreach($html->find('div.detailInlineBanner300') as $e)		$e->outertext = '';
				foreach($html->find('#div-gpt-ad-1410962145047-2') as $e)	$e->outertext = '';

				//img -> alt taglarını temizleyelim
				foreach($html->find('img') as $e)							$e->alt = '';

				//en son metni alalım
				foreach($html->find('span#contextual') as $e)				$text = $e->innertext;

				//kapanmayan etiketlerin önündeki boşlukları silelim
				$text = str_replace(array('  >', ' >'), '>', $text);

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
				return html_entity_decode($text);
			}

			if($type == 'title')
			{
				preg_match('/meta property="og:title" content="([\w\W]*?)"/', $data, $split);

				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h1 bulalım
					foreach($html->find('h1') as $e) $text = $e->innertext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return html_entity_decode($text);
			}

			if($type == 'desc')
			{
				preg_match('/<meta property="og:description" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);

					//h2 bulalım
					foreach($html->find('h2') as $e) $text = $e->innertext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return html_entity_decode($text);
			}

			if($type == 'image')
			{
				preg_match('/<meta name="twitter:image" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				if($text == '')
				{
					$html = str_get_html($data);


					foreach($html->find('#imageContent div img') as $e) $text = $e->src;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}
		}
	}
