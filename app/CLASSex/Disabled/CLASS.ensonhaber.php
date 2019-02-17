<?php

	class ensonhaber
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function rss_fetch($url, $channel = 1, $cat_type = 1, $cat_name = 'none')
		{
			global $_cache, $list_cat;

			$data_curl = curl_get_data($url);

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
					//kategori değerini bulacak bir değer rss ile ulaşmadığından 0 atıyoruz
					if($cat_type == 1)	$list[$i]['cat']		= 0;
					//kategori değerini cat_name değerinden bulalım
					if($cat_type == 0)	$list[$i]['cat']		= intval($list_cat[$t = format_url($cat_name)]);
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

						if(strpos_array($list[$i]['link'], array('http://kralspor.ensonhaber.com/')) == true)
						{
							$parser	= 'ensonhaber_kralspor';
						}

						if(strpos_array($list[$i]['link'], array('http://www.ensonhaber.com/')) == true)
						{
							$parser	= 'ensonhaber_www';
						}

						//belli url mantıklarına izin vermiyoruz
						//galeri içerik veya video dönüyorlar
						$array_url  = array(
							'http://www.ensonhaber.com/galeri/',
							'http://videonuz.ensonhaber.com/'
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
								'cache_object'	=> 'ensonhaber',		//hangi objeyi kullanacağı
								'cache_parser'	=> $parser,				//hangi parser kullanacağız
								'cache_channel'	=> $channel,			//hangi rss sağlayıcıdan geldi
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

		public function ensonhaber_www($data, $type = 'text')
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

				//banner kodlarını temizleyelim
				foreach($html->find('img#mansetresmi') as $e)			$e->outertext = '';
				foreach($html->find('img.detayResim') as $e)			$e->outertext = '';

				//en son metni alalım
				foreach($html->find('article') as $e)					$text = $e->innertext;

				//manşet resmi kendi içinde tekrar geçmesin
				$image = $this->ensonhaber_www($data, $type = 'image');
				$text = str_replace($image,'', $text);

				//kullanılmadığına emin olduğumuz çöpleri temizleyelim
				$text = str_replace(array(
							'<div style="clear:both;"></div>',
							'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
							'<html><body>', '</body>', '</html>',
							'class="eshtag"', 'target="_blank"',
							'<meta itemprop="height" content="315">',
							'<meta itemprop="width" content="620">',
							'itemprop="image"',
							'itemscope itemtype="http://schema.org/ImageObject"',
							'width="620" height="315"',
							'style="margin: 0 auto; display: block; margin-bottom: 20px;"',
						),'',$text);

				//kapanmayan etiketlerin önündeki boşlukları silelim
				$text = str_replace(array('  >', ' >'), '>', $text);

				//http olmayan i resimleri düzeltelim
				$text = str_replace('//i.cdn', 'http://i.cdn', $text);
				$text = str_replace('http:http:', 'http:', $text);

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

				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				preg_match('/<meta property="og:description" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
				//og:title ile gelen imaj var mı
				preg_match('/meta property="og:image" content="([\w\W]*?)"/', $data, $split);
				$img_og = 'http:'.strip_tags($split[1]);

				$html = str_get_html($data);


				foreach($html->find('img#mansetresmi') as $e) $img_big = $e->src;

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);

				if($img_big == '') $text = $img_og;
				if($img_big <> '') $text = $img_big;

				return trim(strip_tags($text));
			}

			if($type == 'cat')
			{
				//og:title ile gelen imaj var mı
				preg_match('/<meta itemprop="articleSection" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				return intval($list_cat[$t = format_url($text)]);
			}
		}

		public function ensonhaber_kralspor($data, $type = 'text')
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
				foreach($html->find('a') as $e)							$e->href = '';
				foreach($html->find('script') as $e)					$e->outertext = '';
				foreach($html->find('img') as $e) 						$e->width = '';
				foreach($html->find('img') as $e) 						$e->height = '';

				//banner kodlarını temizleyelim
				foreach($html->find('img#mansetresmi') as $e)			$e->outertext = '';
				foreach($html->find('img.detayResim') as $e)			$e->outertext = '';
				foreach($html->find('a.twitter-share-button') as $e)	$e->outertext = '';
				foreach($html->find('div.g-plus') as $e)				$e->outertext = '';
				foreach($html->find('a.yorumati') as $e)				$e->outertext = '';
				//ilk frame etiketini siliyoruz; böylece fb like için oluşturulmuş iframe de kayboluyor ;)
 				foreach($html->find('div iframe[1]') as $e)				$e->outertext = '';

				//en son metni alalım
				foreach($html->find('article') as $e)					$text = $e->innertext;

				//manşet resmi kendi içinde tekrar geçmesin
				$image = $this->ensonhaber_kralspor($data, $type = 'image');
				$text = str_replace($image,'', $text);

				//kullanılmadığına emin olduğumuz çöpleri temizleyelim
				$text = str_replace(array(
							'itemprop="articleBody"', 'style="margin-bottom:15px;"',
							'<div style="clear:both;"></div>', '<div style="height:40px;">',
							'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
							'<html><body>', '</body>', '</html>',
							'class="eshtag"', 'target="_blank"',
							'<meta itemprop="height" content="315">',
							'<meta itemprop="width" content="620">',
							'itemprop="image"',
							'itemscope itemtype="http://schema.org/ImageObject"',
							'width="620" height="315"',
							'style="margin: 0 auto; display: block; margin-bottom: 20px;"',
						),'',$text);

				//kapanmayan etiketlerin önündeki boşlukları silelim
				$text = str_replace(array('  >', ' >'), '>', $text);

				//http olmayan i resimleri düzeltelim
				$text = str_replace('//i.cdn', 'http://i.cdn', $text);
				$text = str_replace('http:http:', 'http:', $text);

				//köke uzanan saçmalıkı düzeltelim
				$text = str_replace('src="../../resimler/kok/', 'src="http://kralspor.ensonhaber.com/resimler/kok/', $text);
				//alt ve title değerlerini temizleyelim
				$text = preg_replace('/alt="([\w\W]*?)"/', '', $text);
				$text = preg_replace('/title="([\w\W]*?)"/', '', $text);

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

				return trim(strip_tags($text));
			}

			if($type == 'desc')
			{
				preg_match('/<meta property="og:description" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				return trim(strip_tags($text));
			}

			if($type == 'image')
			{
				//og:title ile gelen imaj var mı
				preg_match('/meta property="og:image" content="([\w\W]*?)"/', $data, $split);
				$img_og = 'http:'.strip_tags($split[1]);

				$html = str_get_html($data);


				foreach($html->find('img#mansetresmi') as $e) $img_big = $e->src;

				//bellek boşaltıyoruz
				$html->clear();
				unset($html);

				if($img_big == '') $text = $img_og;
				if($img_big <> '') $text = $img_big;

				$text = str_replace('http:http:', 'http:', $text);

				return trim(strip_tags($text));
			}

			if($type == 'cat')
			{
				//og:title ile gelen imaj var mı
				preg_match('/<meta itemprop="articleSection" content="([\w\W]*?)"/', $data, $split);
				$text = strip_tags($split[1]);

				return intval($list_cat[$t = format_url($text)]);
			}

		}
	}
