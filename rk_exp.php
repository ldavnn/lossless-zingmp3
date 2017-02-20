<?php
	header("Content-Type: text/plain");
	ini_set('error_reporting', E_STRICT);

	CONST DOWNLOAD = 'http://mp3.zing.vn/json/song/get-download';

	require_once('lib/Zebra_cURL.php');
	$cURL = new Zebra_cURL();
	
	function Redirected($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://mp3.zing.vn'.$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_COOKIE, file_get_contents('cookie.txt'));
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$Page = curl_exec($ch);
		return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		curl_close($ch);
	}

	function LoadPage($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_COOKIE, file_get_contents('cookie.txt'));
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$Page = curl_exec($ch);
		return $Page;
		curl_close($ch);
	}

	$cURL->get($_GET['www'], function ($result){
		libxml_use_internal_errors(true);
		$document = new DOMDocument();
		$document->loadHTML($result->body);
		libxml_use_internal_errors(false);
		GLOBAL $tabService;
		$tabService = $document->getElementById('tabService')->attributes->getNamedItem('data-code')->value;
	});
	
	$document = json_decode(LoadPage(CONSTANT('DOWNLOAD').'?'.http_build_query(array('code' => $tabService))));
	$data = (object) array();
	if (isset($document->data->{128}->link)){
		$data->{128}->link = Redirected($document->data->{128}->link);
		$data->{128}->size = $document->data->{128}->size;
	}
	if (isset($document->data->{320}->link)){
		$data->{320}->link = Redirected($document->data->{320}->link);
		$data->{320}->size = $document->data->{320}->size;
	}
	if (isset($document->data->lossless->link)){
		$data->lossless->link = Redirected($document->data->lossless->link);
		$data->lossless->size = $document->data->lossless->size;
	}

	print_r(json_encode($data, JSON_PRETTY_PRINT));
?>
