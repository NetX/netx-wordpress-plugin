<?php

define( 'WP_USE_THEMES', false );
require_once( '../../../../wp-load.php' );

$options = get_option('netx_options');

$uri = $options['netx_uri'];
$assetId = $_GET["assetID"];
$sizeStr = $_GET["sizeStr"];
$filename = $_GET["filename"];
$token = $options['netx_access_token'];


$downloadUrl = "https://" . $uri . "/file/asset/" . $assetId . '/' . $sizeStr . '/' . $filename . '?token=' . $token;

$fp = fopen($downloadUrl, "r");

if($fp) {
	header("Content-Disposition: attachment; filename=" . $filename);
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Description: File Transfer");
	ob_flush();
	flush();

	while (!feof($fp)) {
		echo fread($fp, 65536);
		ob_flush();
		flush();
	}

	fclose($fp);
} else {
	http_response_code(404);
	ob_flush();
	flush();

	print("netx asset not found.");

	die();
}