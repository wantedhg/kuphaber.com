<?php

	class btnet
	{

		public function rss_fetch($url, $type)
		{
			global $_content;

			$data = curl_get_data($url);

			$html = str_get_html($data);

			if($html <> '')
			{
				//bu kısım sadece temizlik yapıyor
				//lakin aradığımız değerler o kadar güzel yerde ki
				//temizlik yapmaya bile gerek kalmadı
				//foreach($html->find('script') as $e)					$e->outertext = '';
				//foreach($html->find('noscript') as $e)				$e->outertext = '';
				//foreach($html->find('input') as $e)					$e->outertext = '';
				//
				//foreach($html->find('div.divLogo') as $e)				$e->outertext = '';
				//foreach($html->find('nav.divMenuUst') as $e)			$e->outertext = '';
				//foreach($html->find('div.divArama') as $e)			$e->outertext = '';
				//
				//foreach($html->find('footer') as $e)					$e->outertext = '';
				//foreach($html->find('div.divCopyright') as $e)		$e->outertext = '';
				//foreach($html->find('div#ctl00_UPProgress') as $e)	$e->outertext = '';
				//foreach($html->find('div.divAnaSag') as $e)			$e->outertext = '';

				//bu kısımda kalan iki divdeki değerleri okuyoruz
				foreach($html->find('div.divSlideAna a') as $e) 		$link[] = $e->href;
				foreach($html->find('div.divHaberG1 a') as $e) 			$link[] = 'http://www.btnet.com.tr/'.$e->href;

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
					$list[$i]['cache_time']			= date('Y-m-d H:i:s', time()+($adet-$i));
					$list[$i]['cache_link']			= $link[$i];
					$list[$i]['cache_title']		= '';
					$list[$i]['cache_desc']			= '';
					$list[$i]['cache_image']		= '';
					$list[$i]['cache_object']		= $type;

					//kategori seçimi yapıyoruz
					$list[$i]['cache_cat']			= 9;
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
							'http://www.btnet.com/yazarlar/',
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

							//başlıktan | işaretlerini temizliyoruz
							$tlink = explode('|', $list[$i]['cache_title']);
							if($tlink[0] <> '')
							{
								$list[$i]['cache_title']	= trim($tlink[0]);
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

			if($type == 'title')
			{
				preg_match('/meta property="og:title" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

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
				preg_match('/meta property="og:image" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				//linkten ? etiketini temizleyelim
				$text = remove_question_from_link($text);

				return urldecode(urlencode(trim(strip_tags($text))));
			}
		}
	}
