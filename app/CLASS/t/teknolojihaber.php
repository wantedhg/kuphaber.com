<?php

	class teknolojihaber
	{
		public function rss_fetch($url, $type)
		{
			global $_content;

			$data_curl = curl_get_data($url);

			$data_curl = str_replace('feedburner:origLink','feedburner_origLink',$data_curl);

			//gelen datadan channel kısmını alıyoruz; diğer bilgilerde koruyucu kısımlar olabiliyor
			preg_match('/<channel>([\w\W]*?)<\/channel>/', $data_curl, $data);

			$data = simplexml_load_string($data[0]);
			//print_pre($data);
			$adet = count($data->item);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['cache_time']			= date('Y-m-d H:i:s', strtotime(strip_tags($data->item[$i]->pubDate)));
					$list[$i]['cache_link']			= trim(strip_tags($data->item[$i]->feedburner_origLink));
					$list[$i]['cache_title']		= trim(strip_tags($data->item[$i]->title));
					$list[$i]['cache_object']		= $type;

					//kategori seçimi yapıyoruz
					$list[$i]['cache_cat']			= 9;

					//desc yakala
					$text = '';
					if($text == '')
					{
						$html = str_get_html($data->item[$i]->description);

						if($html <> '')
						{
							//imaj kaldıralım
							foreach($html->find('img') as $e)  $e->outertext = '';

							//tüm metni salt metin olarak alalım
							$text = $html->plaintext;

							//bellek boşaltıyoruz
							$html->clear();
							unset($html);
						}

						//değişkene atıyoruz
						$list[$i]['cache_desc'] = trim($text);
					}
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
							'http://www.teknoloji-haber.net/yazarlar/',
						);
						if(strpos_array($list[$i]['cache_link'], $array_url) == true)
						{
							$hata = 1;
						}

						if($hata <> 1)
						{
							//resim yoksa resmi ilgili sayfadan parse edelim
							if($list[$i]['cache_image'] == '')
							{
								$data = curl_get_data($list[$i]['cache_link']);
								$text = self::data_fetch($data, $type = 'image');
								if($text <> '')
								{
									$list[$i]['cache_image'] = trim($text);
								}
							}

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

			if($type == 'image')
			{
				preg_match('/a href="http:\/\/pinterest\.com([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				//explode edip asıl linki alalım
				$exploded = explode('media=', $text);
				if($exploded[1] <> '')
				{
					$text = trim($exploded[1]);
				}

				//linkten ? etiketini temizleyelim
				$text = remove_question_from_link($text);

				return urldecode(urlencode(trim(strip_tags($text))));
			}
		}
	}
