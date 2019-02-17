<?php

	class content
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		private function create_limited_description($text)
		{
			//genel saçmalıkları temizleyelim
			$text = str_replace('[&#46;]', '', $text);
			$text = str_replace('[...]', '', $text);

			//patlatalım
			$exploded = explode('.', $text);
			if($exploded[0] <> '')
			{
				$text = trim($exploded[0].'.');
				//ilk explode 64 den küçük ise, ve ikinci explode metin içeriyorsa
				if(strlen($exploded[0]) < 64 && strlen($exploded[1]) > 0)
				{
					$text = trim($exploded[0].'. '.$exploded[1].'.');
				}

				if(strlen($text) < 64 && strlen($exploded[2]) > 0)
				{
					$text = trim($text.'. '.$exploded[2].'.');
				}

				if(strlen($text) < 64 && strlen($exploded[3]) > 0)
				{
					$text = trim($text.'. '.$exploded[3].'.');
				}

				//açıklamadan tab ve newline değerlerini silelim
				$text = str_replace("\t",'',$text);
				$text = str_replace("\n",' ' ,$text);
			}
			return $text;
		}

		public function content_add($query = 0, $fileExt = '')
		{
			foreach($_REQUEST as $k => $v) $_REQUEST[$k] = trim(strip_tags($v));

			//açıklamanın ilk cümlesini alıyoruz
			$_REQUEST['content_desc'] = self::create_limited_description($_REQUEST['content_desc']);

			$record = array(
				'content_title'		=> $_REQUEST['content_title'],
				'content_desc'		=> $_REQUEST['content_desc'],
				'content_image'		=> $_REQUEST['content_image'],
				'content_time'		=> $_REQUEST['content_time'],
				'content_link'		=> $_REQUEST['content_link'],		//yazının url kaynağı
				'content_source'	=> $_REQUEST['content_source'], 	//yazı hangi siteden alındı
				'content_cat'		=> $_REQUEST['content_cat'],		//yazı hangi kategoride yayınlanacak
			);
			//print_pre($record);

			//içerik kayıtlı mı diye bakıyoruz
			//içerik kayıtlı ise güncelleme sorgusu gönderiyoruz
			//içerik kayıtlı değilse ekleme sorgusu gönderiyoruz
			$sql = 'SELECT
						content_id
					FROM
						'.T_CONTENT.'
					WHERE
						content_link = "'.$_REQUEST['content_link'].'";';
			$content_id = $this->conn->GetOne($sql);

			if($content_id <> '')
			{
				//içerik daha önceden ekliymiş, sadece güncelleyelim
				$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$content_id);
				if($rs == false)
				{
					throw new Exception($this->conn->ErrorMsg());
				}
			}
			else
			{
				//içerik ekli değilmiş, ekleyelim
				$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'INSERT');
				$content_id = $this->conn->Insert_ID();

				if($rs == false)
				{
					//print_pre($record);
					throw new Exception($this->conn->ErrorMsg());
				}
			}

			//ekleme işlemi sonrası resmi de indirsin
			if($content_id <> '')
			{
				$_REQUEST['content_image_local'] = create_local_image
				(
					$content_id,
					$_REQUEST['content_image'],
					$_REQUEST['content_source'],
					$query,
					$fileExt
				);

				if($_REQUEST['content_image_local'] <> '')
				{
					//önce linki lokale dönüştürelim
					$url = parse_url($_REQUEST['content_image_local']);
					if($url['path'] <> '')
					{
						$link = str_replace('/assets/uploads/images/','',$url['path']);
						$link = IMAGE_DIRECTORY.$link;
					}

					//resmin boyutlarını da bulalım
					list($w, $h) = getimagesize($link);

					//sadece boyutlar varsa değer üretelim
					if($w <> '' && $h <> '')
					{
						$wh = $w.'x'.$h;
					}

					//resmi ve bilgilerini güncelleyelim
					$record = array(
						'content_image_local' 	=> $_REQUEST['content_image_local'],
						'content_image_wh' 		=> $wh
					);

					$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$content_id);
					if($rs == false)
					{
						throw new Exception($this->conn->ErrorMsg());
					}
				}
			}

		}

		public function url_source($keyword)
		{
			return SITELINK.'source/'.$keyword;
		}

		public function url_logo($keyword)
		{
			return SITELINK.'assets/logo/'.$keyword.'.png';
		}

		public function content_list_manset($page = 0, $limit = 100, $cat = 100, $source = 'none')
		{
			global $list_cat_name, $list_cat_url, $list_sources_big;

			if($cat <> 100 )
			{
				$sql_cat	= 'WHERE content_cat = '.$cat.' AND content_title <> ""';
			}

			if($source <> 'none' )
			{
				$sql_source	= 'WHERE content_source = "'.$source.'" AND content_title <> ""';
			}

			if($page == 0) $page = 1;
			$sql = 'SELECT
						content_id,
						content_title,
						content_desc,
						content_image_local as content_image,
						content_image_wh,
						content_source,
						content_link,
						content_cat,
						content_twitter,
						content_time
					FROM
						'.T_CONTENT.'
						'.$sql_cat.'
						'.$sql_source.'
					AND
						content_status = 1
					AND
						content_time < now()
					ORDER BY
						content_time DESC
					LIMIT
						'.(($page-1)*$limit).','.$limit;
			if(memcached == 0) $list = $this->conn->GetAll($sql);
			if(memcached == 1) $list = $this->conn->CacheGetAll(cachetime, $sql);
			//echo $sql;

			$adet = count($list);
			for($i = 0; $i < $adet; $i++)
			{

				//cdn aktif ise resim linklerini cdn e dönüştürelim
				if(ST_CDN == 1 && $list[$i]['content_image'] <> '')
				{
					$list[$i]['content_image'] = str_replace(SITELINK,SITELINK_CDN,$list[$i]['content_image']);
				}

				$list[$i]['content_source_name']	= $list_sources_big[$list[$i]['content_source']];
				$list[$i]['content_cat_name']		= $list_cat_name[$list[$i]['content_cat']];
				$list[$i]['content_cat_url']		= $list_cat_url[$list[$i]['content_cat']];
				$list[$i]['content_source_url'] 	= self::url_source($list[$i]['content_source']);
				$list[$i]['content_logo_url'] 		= self::url_logo($list[$i]['content_source']);
			}
			return $list;
		}

		public function content_delete($_id)
		{
			/*
			| Resimlerinin de varolması sebebiyle direk silmiyoruz
			| Önce resim silme fonksiyonuyla haberin resmini siliyoruz
			| Sonra haberin kendisini siliyoruz
			*/

			//içerik resmini sil
			self::content_delete_image($_id);

			//sonrasında içeriği silelim
			$sql = 'DELETE FROM '.T_CONTENT.' WHERE content_id= '.$_id;
			if($this->conn->Execute($sql) === false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function content_delete_soft($_id)
		{
			/*
			| Silme fonksiyonu değildir
			| Kimi URL'lerin sistemde kalması ama yayınlanmaması gerekebilir
			| bu amaçla yayın dışı olarak işaretliyoruz
			*/

			//içerik resmini sil
			self::content_delete_image($_id);

			$record = array('content_status' => 0);
			$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$_id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function content_delete_image($_id)
		{
			$sql = 'SELECT
						content_image_local
					FROM
						'.T_CONTENT.'
					WHERE
						content_id = '.$_id;
			$file_name = $this->conn->GetOne($sql);

			$url = parse_url($file_name);
			if($url['path'] <> '')
			{
				$link = str_replace('/assets/uploads/images/','',$url['path']);
				@unlink(IMAGE_DIRECTORY.$link);
			}
		}

		public function content_truncate_source($source)
		{
			/**
			| Kaynağa ait tüm içerikleri silme fonksiyonuna gönder
			*/
			$sql = 'SELECT
						content_id
					FROM
						'.T_CONTENT.'
					WHERE
						content_source = "'.$source.'"';
			$rs = $this->conn->GetAll($sql);

			foreach($rs as $k => $v)
			{
				self::content_delete($v['content_id']);
			}
			return true;
		}

		public function content_repair_url($id, $url)
		{
			/**
			| İçeriğe ait URL değişmiş, yenisiyle değiştirelim
			*/
			$record = array('content_link' => $url);
			$rs = $this->conn->AutoExecute(T_CONTENT, $record, 'UPDATE', 'content_id='.$id);
			if($rs == false)
			{
				throw new Exception($this->conn->ErrorMsg());
			}
		}

		public function content_repair_source($source)
		{
			/**
			| Kaynağa ait tüm Url'leri kontrol eder
			| URL yayında değilse silip atar
			*/
			$sql = 'SELECT
						content_id,
						content_link
					FROM
						'.T_CONTENT.'
					WHERE
						content_source = "'.$source.'"';
			$rs = $this->conn->GetAll($sql);

			foreach($rs as $k => $v)
			{

				$url = curl_test_url_status($v['content_link']);
				//print_pre($url);

				//200 kodu tam yerine geldin demek; hiçbir şey yapmaya gerek yok

				//301 kodu sayfa kalıcı olarak taşındı demek
				//elimizdeki linki yeni link ile güncelleyelim
				if($url['http_code'] == '301')
				{
					self::content_repair_url($v['content_id'], $url['redirect_url']);
				}

				if($url['http_code'] == '302' or $url['http_code'] == '404')
				{
					self::content_delete($v['content_id']);
				}
			}
		}

		public function content_url_list($source)
		{
			/**
			| Kaynağa ait tüm Url'leri listeler
			*/
			$sql = 'SELECT
						content_link
					FROM
						'.T_CONTENT.'
					WHERE
						content_source = "'.$source.'"';
			//echo $sql;
			$list = $this->conn->GetAll($sql);

			foreach($list as $k => $v)
			{
				$res[] = $v['content_link'];
			}
			return $res;
		}

		public function content_title_list($source)
		{
			/**
			| Kaynağa ait tüm title'leri listeler
			*/
			$sql = 'SELECT
						content_title
					FROM
						'.T_CONTENT.'
					WHERE
						content_source = "'.$source.'"';
			//echo $sql;
			$list = $this->conn->GetAll($sql);

			foreach($list as $k => $v)
			{
				$res[] = $v['content_title'];
			}
			return $res;
		}
	}
