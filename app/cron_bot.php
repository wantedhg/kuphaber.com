<?php
	if(!defined('APP')) die('...');

	error_reporting('E_ALL');

	/**
	|--------------------------------------------------------------
	| Geçerli talep tipleri ve talep şekilleri
	|--------------------------------------------------------------
	|
	|--------------------------------------------------------------
	*/

	use Abraham\TwitterOAuth\TwitterOAuth;

	//init çağrısından önce kod olmaması tercihimdir
	include 'lib/init.php';
	require SITEPATH.'vendor/Twitter/autoload.php';

	$type		= htmlspecialchars($_REQUEST["type"]);

	if($type == 'teknoloji') 	$i_cat = 9;
	if($type == 'technology') 	$i_cat = 59;
	if($type == 'savunma') 		$i_cat = 12;
	if($type == 'bilim') 		$i_cat = 11;

	//hangi api anahtarlarıyla bağlanacağız
	if($type == 'technology')
	{
		/**
		* Owner					: ownerid
		* Owner ID				: ownerid
		*/
		$CONSUMER_KEY			= '';
		$CONSUMER_SECRET		= '';
		$access_token			= '';
		$access_token_secret	= '';

		$array_restrict = array(
			'gizmodo',
			'destructoid',
			'kotaku',
			'cio',
		);

		$restrict = '"xxx"';
		foreach($array_restrict as $k => $v) $restrict.= ',"'.$v.'"';

		$time = date('Y-m-d 00:00:00',strtotime("-1 day"));
	}

	//hangi api anahtarlarıyla bağlanacağız
	if($type == 'teknoloji')
	{
		/**
		* Owner					: ownerid
		* Owner ID				: ownerid
		*/
		$CONSUMER_KEY			= '';
		$CONSUMER_SECRET		= '';
		$access_token			= '';
		$access_token_secret	= '';

		$array_restrict = array(
			'maxicep',
			'tamindir',
			'androidegel',
			'aorhan',
			'bolumsonucanavari',
			'ceotudent',
			'cepkolik',
			'frpnet',
			'indir',
			'level',
			'elektrikport',
			'oyungezer',
			'merlininkazani',
			'girisimhaber',
		);

		$restrict = '"xxx"';
		foreach($array_restrict as $k => $v) $restrict.= ',"'.$v.'"';

		$time = date('Y-m-d 00:00:00',strtotime("-1 day"));
	}

	if($type <> '' && $CONSUMER_KEY <> '')
	{
		//subquery kullanalım
		$sql = 'SELECT
					content_id,
					content_title,
					content_link
				FROM
					app_content
				WHERE
					(content_source, content_time)
				IN
				(
					SELECT
						content_source,
						max(content_time)
					FROM
						app_content
					WHERE
						content_time >= "'.$time.'"
					AND
						content_cat = ?
					AND
						content_twitter = 0
					AND
						content_source NOT IN ('.$restrict.')
					GROUP BY
						content_source
				)
				ORDER BY rand()
				LIMIT 0,1
				';
		//echo $sql;
		$list = $conn->GetAll($sql, array($i_cat));
//  		print_pre($list);

		$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $access_token, $access_token_secret);

		$adet = count($list);
		for($i = 0; $i < $adet; $i++)
		{
// 			print_pre($list[$i]);
			if($list[$i]['content_title'] <> '' && $list[$i]['content_link'] <> '')
			{
				$status = $list[$i]['content_title']."\n".$list[$i]['content_link'];
				$parameters = ['status' => $status];
				$result = $connection->post('statuses/update', $parameters);

				//print_pre($result);
				if($result->id <> '')
				{
					$sqlic = 'UPDATE '.T_CONTENT.' SET content_twitter = 1 WHERE content_id = '.$list[$i]['content_id'];
					//echo $sqlic;
					$conn->Execute($sqlic);
				}

				echo '.';

				//az biraz bekle
				sleep(10);

				//nesneyi boşaltalım
				unset($result);
			}
		}
	}
