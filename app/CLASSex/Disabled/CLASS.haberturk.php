<?php

	class haberturk
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
					$tlink = str_replace('http://www.haberturk.com/','', $list[$i]['link']);
					$tlink = explode('/', $tlink);

					$list[$i]['cat']	= intval($list_cat[$t = format_url($tlink[0])]);
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
						if(strpos_array($list[$i]['link'], array('http://www.haberturk.com/')) == true)
						{
							$parser	= 'haberturk_www';
						}

						//belli url mantıklarına izin vermiyoruz
						//galeri içerik veya video dönüyorlar
						$array_url  = array(
							'/galeri/',
							'http://www.haberturk.com/Yazarlar',
							'http://www.haberturk.com/webtv/',
							'http://www.haberturk.com/multimedya/',
							'http://www.haberturk.com/astroloji?',
							'http://www.haberturk.com/oyun/',
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
								'cache_object'	=> 'haberturk',		//hangi objeyi kullanacağı
								'cache_parser'	=> $parser,			//hangi parser kullanacağız
								'cache_channel'	=> $channel,		//hangi rss sağlayıcıdan geldi
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

		public function haberturk_www($data, $type = 'text')
		{
			if($data == '') return '';

			//gelen datadan ihtiyacımız olan kısmı alıyoruz
			//bu içerik türü için sadece metine ihtiyacımız olduğundan metin alıp geri dönüyoruz
			//html datayı çıkartırken simple_html_dom.php dosyasını kullanıyoruz
			if($type == 'text')
			{
				//kafa karıştıran kimi şeyleri baştan değiştirelim
				$data = str_replace( array(
						'style="display: none;"',
				),'',$data );
				$html = str_get_html($data);

				//tüm linkleri temizleyelim
				//js scriptlerini temizleyelim
				//other-news benzer yazılarını temizleyelim
				foreach($html->find('a') as $e)								$e->href = '';
				foreach($html->find('script') as $e)						$e->outertext = '';
				foreach($html->find('img') as $e) 							$e->width = '';
				foreach($html->find('img') as $e) 							$e->height = '';

				foreach($html->find('div.news-wd') as $e) 					$e->outertext = '';
				foreach($html->find('div.photoNewsImgCountWrap') as $e)		$e->outertext = '';
				foreach($html->find('div.photoNewsSocialBoxWrap') as $e)	$e->outertext = '';
				foreach($html->find('blockquote') as $e)					$e->outertext = '';
				foreach($html->find('img.added-image') as $e)				$e->outertext = '';

				//normal metin gibi parse etmeye çalışalım
				foreach($html->find('div.news-content-text') as $e)			$text = $e->innertext;

				//normal metin yoksa alternatif metine bakalım
				if($text == '')
				{
					foreach($html->find('div.scroll-image') as $e)			$text = $e->innertext;
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
				$html = str_get_html($data);

				//h1 bulalım
				foreach($html->find('div.news-tt h1') as $e) $text = $e->plaintext;

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);

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
					foreach($html->find('div.news-spots h2') as $e) $text = $e->plaintext;

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


					foreach($html->find('img.news-image') as $e) $text = $e->src;

					//bellek boşaltıyoruz
					$html->clear();
					unset($html);
				}
				return trim(strip_tags($text));
			}
		}
	}