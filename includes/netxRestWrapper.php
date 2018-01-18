<?php
/**
 * netxRestWrapper class file
 *
 * Contains definition for the netxRestWrapper class.
 *
 * @package NetxWPPlugin
 */

/**
 * NetX REST API Library
 */
require_once(dirname(__FILE__) . '/netx_rest/netx_rest_api.inc.php');
require_once(dirname(__FILE__) . '/netxWordpressAssetLoader.php');

/**
 * Implements asset list related API calls
 *
 * @package NetxWPPlugin
 */
class netxRestWrapper {
	/**
	 * NetX API object
	 *
	 * @var netxNetx NetX API object
	 * @access private
	 */
	private $netx = null;

	/**
	 * Constructor
	 *
	 * @return netxRestWrapper
	 */
	public function __construct() {
		$options = get_option('netx_options');
		$netxUsername = $options['netx_username'];
		$netxPassword = $options['netx_password'];
		$netxURI = $options['netx_uri'];
		$this->netx = new netxNetx($netxUsername, $netxPassword, $netxURI, true);
	}

	/**
	 *	Get netx API object
	 */
	public function getNetx(){
		return $this->netx;
	}

	/**
	 * Get list of categories from server
	 *
	 * @return array category list
	 */
	public function getCategoryPathList() {
		$list = $this->netx->getCategoryPathList();
		return $list;
	}

	/**
	 * Import a file into DAM
	 *
	 * @return string full path to file
	 */
	public function importFile($filePath) {
		$options = get_option('netx_options');
		$netxCategory = $options['netx_base_category_path'];
		$config = netxConfig::getInstance();
		$config->setDeleteAfterImport(false);
		$asset = $this->netx->fileImport($filePath, $netxCategory);
		return $asset;
	}

	public function netxThumbUrl($assetID) {
		$url = NETX_PLUGIN_URL . 'proxy.php?aid=' . $assetID . '&type=t';
		return $url;
	}

	public function netxPreviewUrl($assetID) {
		$url = NETX_PLUGIN_URL . 'proxy.php?aid=' . $assetID . '&type=p';
		return $url;
	}

	public function netxZoomUrl($assetID) {
		$url = NETX_PLUGIN_URL . 'proxy.php?aid=' . $assetID . '&type=z';
		return $url;
	}

	public function netxOriginalUrl($assetID) {
		$url = NETX_PLUGIN_URL . 'proxy.php?aid=' . $assetID . '&type=o';
		return $url;
	}

	public function getAssetData($assetID, $type, $isAttachment = false) {
		$conn = $this->netx->getConnection();
		$proxy = new netxWordpressAssetLoader($conn);

		switch ($type) {
			case 'o':
				$proxy->getOriginal($assetID, $isAttachment);
				break;
			case 't':
				$proxy->getThumbnail($assetID, $isAttachment);
				break;
			case 'p':
				$proxy->getPreview($assetID, $isAttachment);
				break;
			case 'z':
				$proxy->getZoom($assetID, $isAttachment);
				break;
			default:
				$proxy->getView($assetID, $type, $isAttachment);
				break;
		}

		$ret = array();
		$ret['file'] = $proxy->getUploadFilePath();
		$ret['url'] = $proxy->getUploadUrlPath();

		return $ret;
	}

	public function doProxy($assetID, $type, $isAttachment = false) {
		$conn = $this->netx->getConnection();

		$options = get_option('netx_options');
		$cacheDir = $options['netx_cache_path'] . '/';

		$cacheLifetime = $options['netx_cache_lifetime_seconds'];

		$proxy = new netxCachingProxy($conn, $cacheDir, $cacheLifetime);

		switch ($type) {
			case 'o':
				$proxy->getOriginal($assetID, $isAttachment);
				break;
			case 't':
				$proxy->getThumbnail($assetID, $isAttachment);
				break;
			case 'p':
				$proxy->getPreview($assetID, $isAttachment);
				break;
			case 'z':
				$proxy->getZoom($assetID, $isAttachment);
				break;
			default:
				$proxy->getView($assetID, $type, $isAttachment);
				break;
		}
	}

	public function streamAsset($assetID, $type, $isAttachment = false) {
		$conn = $this->netx->getConnection();

		$proxy = new netxAssetProxy($conn);

		switch ($type) {
			case 'o':
				$proxy->getOriginal($assetID, $isAttachment);
				break;
			case 't':
				$proxy->getThumbnail($assetID, $isAttachment);
				break;
			case 'p':
				$proxy->getPreview($assetID, $isAttachment);
				break;
			case 'z':
				$proxy->getZoom($assetID, $isAttachment);
				break;
			default:
				$proxy->getView($assetID, $type, $isAttachment);
				break;
		}

		if($proxy->getContentLength == 0) {
			$filename = dirname(__FILE__) . "/images/netx_nofile.png";
			$contents = file_get_contents($filename);
			
			echo($contents);
			header("content-type: image/png");
		}
	}
}

?>
