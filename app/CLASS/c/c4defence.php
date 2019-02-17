<?php

	class c4defence
	{
		public function twitter_fetch($username, $domain, $limit, $type)
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
					//zaman damgası olmadığı için, ilk görüldüğü zamanı yayın tarihi olarak kayıt ediyoruz
					$list[$i]['cache_time']			= date('Y-m-d H:i:s', time()+($adet-$i));
					$list[$i]['cache_link']			= trim(strip_tags($data->item[$i]->url));
					$list[$i]['cache_image']		= trim(strip_tags($data->item[$i]->image));
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
							'http://en.c4defence.com/',
							'c4defence.com/AFP/',
						);
						if(strpos_array($list[$i]['cache_link'], $array_url) == true)
						{
							$hata = 1;
						}

						if($list[$i]['cache_title'] == '' or $list[$i]['cache_title'] == '')
						{
							$data = curl_get_data($list[$i]['cache_link']);
						}

						if($hata <> 1)
						{
							//sayfa bilgilerini alalım
							$data = curl_get_data($list[$i]['cache_link']);

							//image bilgisini bulalım
							if($list[$i]['cache_image'] == '')
							{
								$text = self::data_fetch($data, $type = 'image');
								if($text <> '') $list[$i]['cache_image'] = trim($text);
							}

							//title bilgisini bulalım
							if($list[$i]['cache_title'] == '')
							{
								$text = self::data_fetch($data, $type = 'title');
								if($text <> '') $list[$i]['cache_title'] = trim($text);
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

							//başlıkta uzunluk kontrolü yapalım
							//5 karakterden kısa başlıklara izin vermeyelim
							if(strlen($_REQUEST['content_title']) > 5 )
							{
								$_content->content_add();
							}
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

			if($type == 'title')
			{
				preg_match('/meta property="og:title" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				$html = str_get_html($data);

				if($html <> '')
				{
					$text = $html->find('div#content p strong', 0)->plaintext;

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

				//linkten ? etiketini temizleyelim
				$text = remove_question_from_link($text);

				return urldecode(urlencode(trim(strip_tags($text))));
			}

		}
	}
