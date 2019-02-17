<?php
	if(!defined('APP')) die('...');

	function myReq($key, $level = 1, $slash = 0)
	{
		/**
		| DEĞİŞKEN GETİRME FONKSİYONU
		*/

		/**
		| Keyleri request ile varsayılan olarak aldığımızı unutmamak lazım
		| Zaten fonksiyonun amacı keyleri hızlıca alıp değişkene dönüştürmek
		| istenirse bir seçenek daha eklenip, get post reguest veya metodu devre dışı bırakmak
		| imkanı da eklenebilir.
		|
		| $level, hangi seviyede işlem göreceğini
		| $slash, işlemin sonunda slash eklenip eklenmeyeceğini gösterir
		| Örnek Kullanım, doğru yöntem
		| $key = myReq($key,1,1)
		|
		| kullanımı kolaylaştırmak için şu şekillerde kullanma imkanı da var
		| lakin log dosyalarını şişirme ihtimali olduğunu unutmamak lazım
		|
		| $key = myReq($key,1);
		| $key = myReq($key);
		*/

		$key = $_REQUEST[$key];
		if($level == 0) $key = intval(trim($key));
		if($level == 1) $key = trim($key);
		if($level == 2) $key = trim(strip_tags($key));
		if($level == 3)
		{
			if($key == "on")
			{
				$key = 1;
			}
			else
			{
				$key = 0;
			}
		}
		if($slash == 1) $key = addslashes($key);
		return $key;
	}

	function format_url($text)
	{
		#-------------------------------------------------
		# phpBB Turkiye ekibi Alexis tarafından 2007 yılında yazılmıştır
		#-------------------------------------------------

		$text = trim($text);
		$text = str_replace("I","ı",$text);
		$text = mb_strtolower($text, 'UTF-8');

		$find = array(' ', '&quot;', '&amp;', '&', '\r\n', '\n', '/', '\\', '+', '<', '>');
		$text = str_replace ($find, '-', $text);

		$find = array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê');
		$text = str_replace ($find, 'e', $text);

		$find = array('í', 'ı', 'ì', 'î', 'ï', 'I', 'İ', 'Í', 'Ì', 'Î', 'Ï');
		$text = str_replace ($find, 'i', $text);

		$find = array('ó', 'ö', 'Ö', 'ò', 'ô', 'Ó', 'Ò', 'Ô');
		$text = str_replace ($find, 'o', $text);

		$find = array('á', 'ä', 'â', 'à', 'â', 'Ä', 'Â', 'Á', 'À', 'Â');
		$text = str_replace ($find, 'a', $text);

		$find = array('ú', 'ü', 'Ü', 'ù', 'û', 'Ú', 'Ù', 'Û');
		$text = str_replace ($find, 'u', $text);

		$find = array('ç', 'Ç');
		$text = str_replace ($find, 'c', $text);

		$find = array('ş', 'Ş');
		$text = str_replace ($find, 's', $text);

		$find = array('ğ', 'Ğ');
		$text = str_replace ($find, 'g', $text);

		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');

		$repl = array('', '-', '');

		$text = preg_replace ($find, $repl, $text);
		$text = str_replace ('--', '-', $text);

		//$text = $text;

		return $text;
	}


	function tr_strtolower($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.strtoupper.php#97667
		#-------------------------------------------------
		return mb_convert_case(str_replace('I','ı',$text), MB_CASE_LOWER, "UTF-8");
	}

	function tr_strtoupper($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.strtoupper.php#97667
		#-------------------------------------------------
		return mb_convert_case(str_replace('i','İ',$text), MB_CASE_UPPER, "UTF-8");
	}

	function tr_ucfirst($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.ucfirst.php#105435
		#-------------------------------------------------

		$text = str_replace("I","ı",$text);
		$text = mb_strtolower($text, 'UTF-8');

		if($text[0] == "i")
			$tr_text = "İ".substr($text, 1);
		else
			$tr_text = mb_convert_case($text, MB_CASE_TITLE, "UTF-8");

		return trim($tr_text);
	}

	function tr_ucwords($text)
	{
		#-------------------------------------------------
		# şu adresten alınmıştır
		# http://www.php.net/manual/en/function.ucfirst.php#105435
		#-------------------------------------------------
		$p = explode(" ",$text);
		if(is_array($p))
		{
			$tr_text = "";
			foreach($p AS $item)
				$tr_text .= " ".tr_ucfirst($item);

			return trim($tr_text);
		}
		else
			return tr_ucfirst($text);
	}

	function print_pre($s)
	{
		echo "<pre>";
		print_r($s);
		echo "</pre>";
	}


	function cleanText($content_text)
	{

		require_once SITEPATH.'vendor/HTMLPurifier/HTMLPurifier.auto.php';
		//iframe'e izin vermek için bulduğumuz ek class ekleniyor
		require_once SITEPATH.'vendor/HTMLPurifier/MyIframe.php';
		$config		= HTMLPurifier_Config::createDefault();

		//önce iframe'e izin verelim
		$config->set('Filter.Custom',  array( new HTMLPurifier_Filter_CustomIframesSupport() ));
		//sonra diğer ayarlar
 		$config->set('Core.Encoding', 'UTF-8'); 		// replace with your encoding
 		$config->set('Core.RemoveInvalidImg', true); 	// resim olamayacak şeyler silinsin
 		$config->set('CSS.AllowedProperties', array());
 		$config->set('Attr.AllowedClasses', array());
		$config->set('HTML.TidyLevel', 'heavy');

		$purifier		= new HTMLPurifier($config);
		$content_text	= $purifier->purify($content_text);

		//bellik azaltımı düşüncesiyle imha edelim
		unset($purifier);

		//bölünmez boşlukları düz boşluk yapalım
		$content_text = str_replace(array('&#160;', '&nbsp;'), ' ', $content_text);

		//h değerleri b yapalım
		$content_text = str_replace(array('<h1>', '<h2>', '<h3>', '<h4>', '<h5>', '<h6>'), '<p><b>', $content_text);
		$content_text = str_replace(array('</h1>', '</h2>', '</h3>', '</h4>', '</h5>', '</h6>'), '</b></p>', $content_text);

		//div değerleri p yapalım
		$content_text = str_replace('<div', '<p', $content_text);
		$content_text = str_replace('</div>', '</p>', $content_text);

		//boş elementleri temizleyelim
		$array = array(
			'src=""',
			'<p><img src="" /></p>', '<img src="" />', '<em></em>',
			"\t", '<p> </p>', '<span> </span>', '<div> </div>',
			'<div style="clear:both;width:100%;">&nbsp;</div>',
			'align="left"', 'align="center"', 'align="right"',
			'alt=""', 'lang="tr"', 'dir="ltr"', 'xml:lang="tr"',
			'<a href="">', '</a>', '<p><img src="" /></p>',
			'<strong></strong>', '<strong> </strong>',
			'<b></b>', '<b> </b>', '<p></p>', '<p> </p>'
		);
		$content_text = str_replace($array, '', $content_text);
		$content_text = str_replace($array, '', $content_text);
		$content_text = str_replace($array, '', $content_text);

		//raw gelen datayı entity edelim
		$content_text = htmlentities($content_text);
//		echo $content_text;

		$content_text = str_replace('&lt;p&gt;&#160;&lt;/p&gt;', '', $content_text);

		//DİV kabul ETMEYELİM
		$content_text = str_replace('&lt;div','<p',$content_text);
		$content_text = str_replace('&lt;/div&gt;','</p>',$content_text);

		//SPAN kabul ETMEYELİM
		$content_text = str_replace('&lt;span','<p',$content_text);
		$content_text = str_replace('&lt;span &gt;','<p>',$content_text);
		$content_text = str_replace('&lt;/span&gt;','</p>',$content_text);

		//bölünmez boşlukları kabul ETMEYELİM
		$content_text = str_replace('&nbsp;',' ',$content_text);
		$content_text = str_replace('&#160;',' ',$content_text);

		//çift br -> p olsun
		$content_text = str_replace('&lt;br /&gt;&lt;br /&gt;','</p><p>',$content_text);

		//daha beter olsun, tek br de p olsun
		$content_text = str_replace('&lt;br /&gt;','</p><p>',$content_text);

		//daha beter olsun, tek <br> de p olsun
		$content_text = str_replace('&lt;br&gt;','</p><p>',$content_text);

		//oh olsun, çift p de tek p olsun
		$content_text = str_replace('&lt;p&gt;&lt;p&gt;','<p>',$content_text);
		$content_text = str_replace('&lt;p/&gt;&lt;p/&gt;','</p>',$content_text);

		//hatalı paragraf
		$content_text = str_replace('&lt;p&gt;&lt;/p&gt;','',$content_text);
		$content_text = str_replace('&lt;p&gt; &lt;/p&gt;','',$content_text);

		//hatalı paragraf başına enter koymak
		$content_text = str_replace('&lt;p&gt;&lt;br /&gt;','<p>',$content_text);

		//hatalı paragraf sonuna enter koymak
		$content_text = str_replace('&lt;br /&gt;&lt;p&gt;','</p>',$content_text);

		//hatalı paragraf başı + boşluk
		$content_text = str_replace('&lt;p&gt; ','<p>',$content_text);
		$content_text = str_replace('&lt;p&gt;&nbsp;','<p>',$content_text);

		//hatalı br + boşluk
		$content_text = str_replace('&lt;br /&gt;&nbsp;','<br />',$content_text);
		$content_text = str_replace('&lt;br /&gt; ','<br />',$content_text);

		//hatalı etiket kapatma + boşluk
		$content_text = str_replace(' &gt;','>',$content_text);

		//bu değişimlerin sonuna
		//boşluk eklemek sorun yaratıyor
		//noktalı virgüller
		$content_text = str_replace(' ;',';',$content_text);

		//bu değişimler sorunsuz

		//nokta
		$content_text = str_replace(' .','.',$content_text);
		//hatalı üç nokta
		$content_text = str_replace('. . .','...',$content_text);
		//malum üç nokta
		$content_text = str_replace(' ...','...',$content_text);

		//virgüller
		$content_text = str_replace(' ,',',',$content_text);

		//çift nokta
		$content_text = str_replace(' :',':',$content_text);
		//soru işareti
		$content_text = str_replace(' ?','?',$content_text);

		//ünlem işareti
		$content_text = str_replace(' !','!',$content_text);

		//boşlukları 3 defa temizleyelim
		$content_text = str_replace('  ',' ',$content_text);
		$content_text = str_replace('  ',' ',$content_text);
		$content_text = str_replace('  ',' ',$content_text);

		//echo '<!--'.$content_text.'-->'."\n";

		//entitiy gelen datayı geri raw edelim
		$content_text = html_entity_decode($content_text);

		return $content_text;
	}

	function strpos_array($haystack, $needle, $offset=0)
	{
		/**
		* strpos un dizi ile çalışanı
		* ilk true gördüğünde direk dönüş yapar
		*/

		if(!is_array($needle)) $needle = array($needle);
		foreach($needle as $query)
		{
			if(strpos($haystack, $query, $offset) !== false)
			{
				return true; // stop on first true result
			}
		}
		return false;
	}

	function find_supdomain($url)
	{
		/**
		* url'den subdomain ayrıştırmaya yarar
		*/
		$url = trim($url);
		preg_match('/(?:http[s]*\:\/\/)*(.*?)\.(?=[^\/]*\..{2,5})/i', $url, $match);
		return $match[1];
	}

	function debug_min()
	{
		global $starttime;

		$endtime = microtime(true);
		$endtime = substr(($endtime - $starttime),0,6);

		$kullanim = memory_get_peak_usage(true);
		$kullanim = number_format($kullanim / 1024);

		$content = 'SÜS : '.$endtime.' | MEM : '.$kullanim.'<br/>';
		return $content;
	}

	function convert_to_turkish_time( $date, $hour = '-3 hour')
	{
		/**
		* Tarih ve Saatten tarih veya saat çıkartır veya ekler
		*/
		return date('Y-m-d H:i:s', strtotime($hour, strtotime($date)));
	}

	function curl_get_data($url, $follow = true)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL , $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if($follow <> false)
		{
			//url yi takip et
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		}

		//cloudFlare bypas edebilmek için user agent bilgisi göndermek zorunda kalıyoruz!
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0');
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
		$data = curl_exec($ch);

		curl_close($ch);
		return $data;
	}

	function curl_get_tweet_haber($username, $domain, $limit = 20)
	{
		$url = SITELINK.'vendor/Twitter/twit_haber.php?username='.$username.'&domain='.$domain.'&limit='.$limit;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		//url yi takip et
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		//cloudFlare bypas edebilmek için user agent bilgisi göndermek zorunda kalıyoruz!
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0');
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
		$data = curl_exec($ch);

		curl_close($ch);
		return $data;
	}

	function curl_url_follow($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL , $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		//url yi takip et
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		//cloudFlare bypas edebilmek için user agent bilgisi göndermek zorunda kalıyoruz!
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0');
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
		curl_exec($ch);

		$data = curl_getinfo($ch);

		curl_close($ch);
		return $data['url'];
	}

	function curl_test_url_status($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL , $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		//url yi takip et
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

		//cloudFlare bypas edebilmek için user agent bilgisi göndermek zorunda kalıyoruz!
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0');
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
		curl_exec($ch);

		$data = curl_getinfo($ch);

		curl_close($ch);
		return $data;
	}

	function curl_grab_image($url, $saveto, $id)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL , $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

		//url yi takip et
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		//cloudFlare bypas edebilmek için user agent bilgisi göndermek zorunda kalıyoruz!
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0');
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');

		$raw = curl_exec($ch);
		curl_close($ch);

		$data = curl_getinfo($ch);
		if($data['http_code'] == '301' or $data['http_code'] == '302' or $data['http_code'] == '404')
		{
			//hatalı hiç bulaşma
			return false;
		}
		else
		{
			if(file_exists($saveto))
			{
				unlink($saveto);
			}
			$fp = fopen($saveto,'x');
			fwrite($fp, $raw);
			fclose($fp);

			//doğru yoldasın
			return true;
		}
	}

	function remove_question_from_link($text)
	{
		$exploded = explode('?', $text);
		if($exploded[0] <> '')
		{
			$text = trim($exploded[0]);
		}
		return $text;
	}

	function create_local_image($id, $url, $folder, $query = 0, $fileExt = '')
	{
		//imajdaki query stringleri temizliyoruz

		if($query == 0)
		{
			$url_data = parse_url($url);
			$url = str_replace('?'.$url_data['query'], '', $url);
		}

		//url // ile başlıyorsa hatalıdır, http yapalım
		if($url[0].$url[1] == '//')
		{
			$url = str_replace('//','http://',$url);
		}

		//ilgili klasörü oluşturmaya çalışıyoruz
		mkdir(IMAGE_DIRECTORY.$folder);

		//dosya uzantısını url'den tespit ediyoruz
		if($fileExt == '')
		{
			$posNokta = strrpos(basename($url), '.');
			$fileName = substr(basename($url), 0, $posNokta);
			$fileExt = strtolower(substr(basename($url), $posNokta+1));
		}

		//dosyamızın adını ve konumunu belirliyoruz
		$filename 	= IMAGE_DIRECTORY.$folder.'/content_'.$id.'.'.$fileExt;
		$fileurl 	= G_IMAGES.$folder.'/content_'.$id.'.'.$fileExt;

		//resim urllerde boşluk bulunabiliyor
		//çekmeden önce well formated ediyoruz
		$url = str_replace(' ','%20',trim($url));

		//uzak resmi indiriyoruz
		$sonuc = curl_grab_image($url, $filename, $id);

		//resim hata vermişse işleme devam etmeyelim
		if($sonuc == false) return '';

		$sizes = getimagesize($filename);

		if($sizes['mime'] <> '')
		{
			try
			{
				//nesnemizi oluşturuyoruz
				$im = new imagick($filename);

				//resmin genişliğini hesaplıyoruz
				$im_width 	= $im->getImageWidth();
				$im_height 	= $im->getImageHeight();

				//resim beklediğimizden geniş ise resmi küçültüyoruz
				//değilse hiç işlem yapmıyoruz
				if($im_width > 320 && $im_height > 60)
				{
					//düz yöntemde direk resmi küçültüyoruz
					//$im->thumbnailImage(320, null, false);

					//bu yöntemde ise resmi küçültürken küçük bir lanczos filtresi uyguluyoruz
					$im->resizeImage (320, 320,  imagick::FILTER_LANCZOS, 0.9, true);

					//resmi tekrar yerine yazıyoruz
					$im->writeImage($filename);
				}

				//resim 60px den yüksek değil ise
				//boşa bizi yormasın, silip atalım
				if($im_height < 60)
				{
					unlink($filename);
					$fileurl = '';
				}

				//bellek boşaltmak için daima çalıştırıyoruz
				//çünkü resmin boyutlarını öğrenirken nesneyi zaten kullandık
				$im->destroy();

				return $fileurl;

			}
			catch(Exception $e)
			{
				//echo $e->getMessage();
			}
		}
		else
		{
			//resmi imha et, çünkü resim resim değil
			unlink($filename);
			return '';
		}
	}

	function twig_daycounter($datetime)
	{
		$time = time() - strtotime($datetime);
		$units = array (
			31536000	=> 'yıl',
			2592000		=> 'ay',
			86400 		=> 'gün',
			3600 		=> 'saat',
			60 			=> 'dakika',
			1 			=> 'saniye'
		);

		foreach ($units as $unit => $val)
		{
			if ($time < $unit) continue;
			return floor($time / $unit).' '.$val;
		}

	}
