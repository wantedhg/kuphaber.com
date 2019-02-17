<?php

	/**
	* Blogspot yayınlarını parse eden örnek bir çalışma
	* Blogspot üstünden de kendi domainleriyle yayın yapan siteleri parse ederken işe yarayacaktır
	*/

	class siyahgribeyaz
	{
		public function rss_fetch($url, $type)
		{
			global $_content;

			$data_curl = curl_get_data($url);

			//media:thumbnail ayarlıyoruz
			$data_curl = str_replace(' xmlns:media="http://search.yahoo.com/mrss/" url="', '>', $data_curl);
  			$data_curl = str_replace('" height="72" width="72"/>', '</media:thumbnail>', $data_curl);

  			//küçük resim büyük resimle yer değiştiriyor
			$data_curl = str_replace('/s72-c/','/s1600/',$data_curl);

			//media yolu yeniden düzenleniyor
			$data_curl = str_replace('media:thumbnail','media_thumbnail',$data_curl);

 			$data = simplexml_load_string($data_curl);
 			//print_pre($data);
			$adet = count($data->channel->item);
			if($adet > 0)
			{
				for($i = 0; $i < $adet; $i++)
				{
					$list[$i]['cache_title']		= trim(strip_tags($data->channel->item[$i]->title));
					$list[$i]['cache_time']			= date('Y-m-d H:i:s', strtotime(strip_tags($data->channel->item[$i]->pubDate)));
					$list[$i]['cache_link']			= trim(strip_tags($data->channel->item[$i]->link));
					$list[$i]['cache_image']		= trim(strip_tags($data->channel->item[$i]->media_thumbnail));
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
							'http://www.siyahgribeyaz.com/yazarlar/',
						);
						if(strpos_array($list[$i]['cache_link'], $array_url) == true)
						{
							$hata = 1;
						}

						if($hata <> 1)
						{
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
	}
