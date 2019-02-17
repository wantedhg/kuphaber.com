<?php

	class mydroneland
	{

		public function rss_fetch($url, $type)
		{
			global $_content;

			$data = curl_get_data($url);

			$html = str_get_html($data);

			if($html <> '')
			{
				foreach($html->find('p.readmore a') as $e) 				$link[] = 'http://www.mydroneland.com'.$e->href;

				//print_pre($link);
				//bellek boşaltıyoruz
				$html->clear();
				unset($html);
			}

			$adet = count($link);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					//zaman damgası olmadığı için, ilk görüldüğü zamanı yayın tarihi olarak kayıt ediyoruz
					$list[$i]['cache_time']			= '';
					$list[$i]['cache_link']			= $link[$i];
					$list[$i]['cache_title']		= '';
					$list[$i]['cache_desc']			= '';
					$list[$i]['cache_image']		= '';
					$list[$i]['cache_object']		= $type;

					//kategori seçimi yapıyoruz
					$list[$i]['cache_cat']			= 12;
				}
			}

			//rss'i parse ettik, artık veritabanına alabiliriz
 			//print_pre($list);
			if($adet > 0)
			{
				$list_url = $_content->content_url_list($type);
				//print_pre($list_url);

				for($i = 0; $i < $adet; $i++)
				{
					//kayıttın önce bu içerik eklenmiş mi diye kontrol ediyoruz
 					if(!in_array($list[$i]['cache_link'], $list_url))
					{
						//hata sayısını sıfırlayalım ki break edip hepsi hata diye kırılmasın
						$hata = 0;

						//belli url mantıklarına izin vermiyoruz
						//galeri içerik veya video dönüyorlar
						$array_url  = array(
							'canli-izle',
							'http://www.mydroneland.com/yazarlar/',
						);
						if(strpos_array($list[$i]['cache_link'], $array_url) == true)
						{
							$hata = 1;
						}

						if($hata <> 1)
						{
							$data = curl_get_data($list[$i]['cache_link']);

							//eksik dataları haberin sayfasından tamamlayalım
							$text = self::data_fetch($data, $type = 'image');
							if($text <> '') $list[$i]['cache_image'] = trim($text);

							$text = self::data_fetch($data, $type = 'title');
							if($text <> '') $list[$i]['cache_title'] = trim($text);

							$text = self::data_fetch($data, $type = 'desc');
							if($text <> '') $list[$i]['cache_desc'] = trim($text);

							$text = self::data_fetch($data, $type = 'time');
							if($text <> '') $list[$i]['cache_time'] = trim($text);

							$_REQUEST['content_link']		= $list[$i]['cache_link'];
							$_REQUEST['content_title']		= $list[$i]['cache_title'];
							$_REQUEST['content_desc']		= $list[$i]['cache_desc'];
							$_REQUEST['content_image']		= $list[$i]['cache_image'];
							$_REQUEST['content_time']		= $list[$i]['cache_time'];
							$_REQUEST['content_cat']		= $list[$i]['cache_cat'];
							$_REQUEST['content_source']		= $list[$i]['cache_object'];
							//ekliyoruz
							//print_pre($list[$i]);
							$_content->content_add();
							//eklendi işareti dönüyoruz
							echo '.';

							//üst diziye url'yi ekleyelim
							$list_url[] = $list[$i]['cache_link'];
							//print_pre($list_url);
						}
					}
					else
					{
						echo '!';
					}
				}
			}
		}

		private function data_fetch($data, $type = 'image')
		{
			if($data == '') return '';

			if($type == 'time')
			{
				preg_match('/<time datetime="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				$text = str_replace(array('T','+03:00'),' ',$text);

				return trim(strip_tags($text));
			}

			if($type == 'title')
			{
				$html = str_get_html($data);

				if($html <> '')
				{
					//resmi yakalamaya çalışıyoruz
					$text = $html->find('h1',0)->plaintext;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}

				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				preg_match('/meta name="description" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
				$html = str_get_html($data);

				if($html <> '')
				{
					//resmi yakalamaya çalışıyoruz
					$text = $html->find('div.itemBody img', 0)->src;

					$t = $text[0].$text[1].$text[2].$text[3];
					if($t <> '' && $t <> 'http') $text = 'http://www.mydroneland.com/'.$text;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}

				return urldecode(urlencode(trim(strip_tags($text))));
			}
		}
	}
