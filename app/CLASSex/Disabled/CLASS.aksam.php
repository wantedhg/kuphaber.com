<?php

	class aksam
	{
		public function rss_fetch($url, $type)
		{
			global $_content;
			global $list_cat;

			$data_curl = curl_get_data($url);

			//gelen data iso olduğu için utf-8 e dönüştürüyoruz
			$data_curl = mb_convert_encoding($data_curl, "UTF-8", "ISO-8859-9");

			//gelen datadan channel kısmını alıyoruz; diğer bilgilerde koruyucu kısımlar olabiliyor
			preg_match('/<channel>([\w\W]*?)<\/channel>/', $data_curl, $data);

			$data = simplexml_load_string($data[0]);
			$adet = count($data->item);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['cache_time']			= date('Y-m-d H:i:s', strtotime(strip_tags($data->item[$i]->pubDate)));
					$list[$i]['cache_link']			= trim(strip_tags($data->item[$i]->link));
					$list[$i]['cache_title']		= trim(strip_tags($data->item[$i]->title));
					$list[$i]['cache_object']		= $type;

					//kategori seçimi yapıyoruz
					$tlink = str_replace('http://www.aksam.com.tr/','', $list[$i]['cache_link']);
					$tlink = explode('/', $tlink);

					$list[$i]['cache_cat']	= intval($list_cat[$t = format_url($tlink[0])]);
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
							'http://www.aksam.com.tr/video/',
							'http://www.aksam.com.tr/foto/',
							'http://www.aksam.com.tr/yazarlar/',
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
			self::twitter_fetch($username = 'aksam', $domain = 'aksam.com.tr', $limit = 30);
			self::twitter_fetch($username = 'aksamspor', $domain = 'aksam.com.tr', $limit = 20);
		}

		private function data_fetch($data, $type = 'image')
		{
			if($data == '') return '';

			if($type == 'image')
			{
				preg_match('/meta property="og:image" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				return trim(strip_tags($text));
			}
		}

		public function twitter_fetch($username = 'aksam', $domain = 'aksam.com.tr', $limit = 20)
		{
			global $_content;

			$data_curl = curl_get_tweet_haber($username, $domain, $limit);

			//gelen datadan channel kısmını alıyoruz
			preg_match('/<channel>([\w\W]*?)<\/channel>/', $data_curl, $data);

			$data = simplexml_load_string($data[0]);
			$adet = count($data->item);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['cache_link']	= trim(strip_tags($data->item[$i]->url));

					//url'yi kısa url olma ihtimaline karşı fonksiyona gönderiyoruz
					$tlink = curl_url_follow($list[$i]['cache_link']);
					//dönen url boş değil ise
					if($tlink <> '')
					{
						$list[$i] = $tlink;
					}
				}
			}
			//print_pre($list);
			//$_content->content_mark_tweet($list);
		}
	}
