<?php
/**
 * @package NetxWPPlugin
 */

require_once(dirname(__FILE__) . '/../../../wp-blog-header.php');
require_once(dirname(__FILE__) . '/includes/netxRestWrapper.php');

if (isset($_GET['aid'])) {
	$assetID = $_GET['aid'];
} else {
	$assetID = 44;
}

if (isset($_GET['type'])) {
	$type = $_GET['type'];
} else {
	$type = 'o';
}

if (isset($_GET['dl'])) {
	$attachment = true;
} else {
	$attachment = false;
}

$netx = new netxRestWrapper();

$netx->streamAsset($assetID, $type, $attachment);

?>
