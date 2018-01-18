<?php
/**
 * netxAssetProxy class file
 *
 * Contains definition for the netxAssetProxy class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Provides proxy service for files contained in ImagePortal that are
 * not publicly viewable
 *
 * @package NetxRestAPI
 */
class netxAssetProxy extends netxRestClient {
	/**
	 * Asset type
	 *
	 * @var string type of asset (thumbnail, preview, original, zoom, view, etc)
	 * @access private
	 */
	protected $assetType = '';

	/**
	 * Name of view
	 *
	 * @var string view name, if the asset type is 'view'
	 * @access private
	 */
	protected $viewName = '';

	/**
	 * Page number of page constituent
	 *
	 * @var int page number, used if the asset type is 'page'
	 * @access private
	 */
	protected $pageNumber = 0;

	/**
	 * Keyframe number of keyframe constituent
	 *
	 * @var int keyframe number, used if the asset type is 'keyframe'
	 * @access private
	 */
	protected $keyframeNumber = 0;

	/**
	 * Constructor
	 *
	 * @param netxConnection $conn current connection to server
	 * @return netxAssetProxy
	 */
	public function __construct(netxConnection $conn) {
		parent::__construct('file', $conn);
	}

	/**
	 * Get document page image
	 *
	 * @param int $assetID id of asset to return
	 * @param int $pageNum page number to return image for (range is from 1 to total number of pages)
	 * @return mixed outputs page image to browser
	 */
	public function getPage($assetID, $pageNum) {
		$this->assetType = 'page';
		$this->viewName = '';
		$this->pageNumber = $pageNum;
		$cmdStr = $this->makePageCommandString($assetID, $pageNum);
		$this->getFile($cmdStr, $assetID, false);
	}


	/**
	 * Get document keyframe image
	 *
	 * @param int $assetID id of asset to return
	 * @param int $keyframeNumber keyframe number to return image for (range is from 1 to total number of keyframes)
	 * @return mixed outputs keyframe image to browser
	 */
	public function getKeyframe($assetID, $keyframeNumber) {
		$this->assetType = 'keyframe';
		$this->viewName = '';
		$this->keyframeNumber = $keyframeNumber;
		$cmdStr = $this->makeKeyframeCommandString($assetID, $keyframeNumber);
		$this->getFile($cmdStr, $assetID, false);
	}

	/**
	 * Get original file
	 *
	 * @param int $assetID id of asset to return
	 * @param boolean $attachment set to true to return the file as an attachment
	 * @param string $filename assets original filename
	 * @return mixed outputs original file to browser
	 */
	public function getOriginal($assetID, $attachment = false, $filename = "") {
		$this->assetType = 'original';
		$this->viewName = '';
		$cmdStr = $this->makeCommandString($assetID, 'original', $attachment);
		$this->getFile($cmdStr, $assetID, $attachment, $filename);
	}

	/**
	 * Get original file as an attachment
	 *
	 * @param int $assetID id of asset to return
	 * @return mixed outputs original file to browser as attachment
	 */
	public function getOriginalAsAttachment($assetID) {
		$this->getOriginal($assetID, true);
	}

	/**
	 * Get thumbnail file
	 *
	 * @param int $assetID id of asset to return
	 * @param boolean $attachment set to true to return the file as an attachment
	 * @param string $filename assets original filename
	 * @return mixed outputs thumbnail file to browser
	 */
	public function getThumbnail($assetID, $attachment = false, $filename = "") {
		$this->assetType = 'thumb';
		$this->viewName = '';
		$cmdStr = $this->makeCommandString($assetID, 'thumb', $attachment);
		$this->getFile($cmdStr, $assetID, $attachment, $filename);
	}

	/**
	 * Get thumbnail of file as an attachment
	 *
	 * @param int $assetID id of asset to return
	 * @return mixed outputs thumbnail file to browser as attachment
	 */
	public function getThumbnailAsAttachment($assetID) {
		$this->getThumbnail($assetID, true);
	}

	/**
	 * Get preview file
	 *
	 * @param int $assetID id of asset to return
	 * @param boolean $attachment set to true to return the file as an attachment
	 * @param string $filename assets original filename
	 * @return mixed outputs preview file to browser
	 */
	public function getPreview($assetID, $attachment = false, $filename = "") {
		$this->assetType = 'preview';
		$this->viewName = '';
		$cmdStr = $this->makeCommandString($assetID, 'preview', $attachment);
		$this->getFile($cmdStr, $assetID, $attachment, $filename);
	}

	/**
	 * Get preview file as an attachment
	 *
	 * @param int $assetID id of asset to return
	 * @return mixed outputs preview file to browser as attachment
	 */
	public function getPreviewAsAttachment($assetID) {
		$this->getPreview($assetID, true);
	}

	/**
	 * Get zoom file
	 *
	 * @param int $assetID id of asset to return
	 * @param boolean $attachment set to true to return the file as an attachment
	 * @param string $filename assets original filename
	 * @return mixed outputs zoom file to browser
	 */
	public function getZoom($assetID, $attachment = false, $filename = "") {
		$this->assetType = 'zoom';
		$this->viewName = '';
		$cmdStr = $this->makeCommandString($assetID, 'zoom', $attachment);
		$this->getFile($cmdStr, $assetID, $attachment, $filename);
	}

	/**
	 * Get zoom file as an attachment
	 *
	 * @param int $assetID id of asset to return
	 * @return mixed outputs zoom file to browser as attachment
	 */
	public function getZoomAsAttachment($assetID) {
		$this->getZoom($assetID, true);
	}

	/**
	 * Get view file
	 *
	 * @param int $assetID id of asset to return
	 * @param string $viewName name of view to return
	 * @param boolean $attachment set to true to return the file as an attachment
	 * @param string $filename assets original filename
	 * @return mixed outputs view file to browser
	 */
	public function getView($assetID, $viewName, $attachment = false, $filename = "") {
		$this->assetType = 'view';
		$this->viewName = $viewName;
		$cmdStr = $this->makeCommandString($assetID, 'view/' . urlencode($viewName), $attachment);
		$this->getFile($cmdStr, $assetID, $attachment, $filename);
	}

	/**
	 * Get view file as an attachment
	 *
	 * @param int $assetID id of asset to return
	 * @param string $viewName name of view to return
	 * @return mixed outputs view file to browser as attachment
	 */
	public function getViewAsAttachment($assetID, $viewName) {
		$this->getView($assetID, $viewName, true);
	}

	/**
	 * Get FLV file
	 *
	 * @param int $assetID id of asset to return
	 * @param boolean $attachment set to true to return the file as an attachment
	 * @return mixed outputs FLV file to browser
	 */
	public function getFLV($assetID, $attachment = false) {
		$this->assetType = 'flv';
		$this->viewName = '';
		$cmdStr = $this->makeCommandString($assetID, 'flv', $attachment);
		$this->getFile($cmdStr, $assetID, $attachment);
	}

	/**
	 * Get FLV file as an attachment
	 *
	 * @param int $assetID id of asset to return
	 * @return mixed outputs FLV file to browser as attachment
	 */
	public function getFLVAsAttachment($assetID) {
		$this->getFLV($assetID, true);
	}

	/**
	 * Makes the REST call
	 *
	 * Retrieves the file, sets the appropriate headers, and streams the file
	 *
	 * @param string $cmdStr REST command string
	 * @param int $assetID NetX asset ID
	 * @param boolean $attachment set to true to return the file as an attachment
	 * @param string $filename assets original filename
	 * @return mixed outputs file to browser
	 */
	protected function getFile($cmdStr, $assetID, $attachment = false, $filename = "") {
		$res = $this->doCommand($cmdStr);

		$http = $this->connection->getHttp();
		$hdrs['Content-Type'] = $http->getResponseHeader('Content-Type');
		$hdrs['Content-Length'] = $http->getResponseHeader('Content-Length');
		if ($attachment) {
			$hdrs['Content-Disposition'] = $http->getResponseHeader('Content-Disposition');
		}

		$this->sendHeaders($hdrs);
		$this->stream($res);
	}

	/**
	 * Creates the REST command string
	 *
	 * @param int $assetID id of asset to return
	 * @param string $fileType original|thumb|preview|zoom|flv
	 * @param boolean $attachment set to true to return the file as an attachment
	 * @return string REST command string
	 */
	protected function makeCommandString($assetID, $fileType, $attachment) {
		$attachmentParm = (($attachment) ? '/attachment' : '');
		$cmdStr = '/asset/' . $assetID . '/' . $fileType . $attachmentParm;
		return $cmdStr;
	}

	/**
	 * Creates the REST command string for page retrieval
	 *
	 * @param int $assetID id of asset to return
	 * @param int $page page number to return
	 * @return string REST command string
	 */
	protected function makePageCommandString($assetID, $page) {
		$cmdStr = '/asset/' . $assetID . '/constituent/page' . $page . '/' . $page . '.jpg';
		return $cmdStr;
	}

	/**
	 * Creates the REST command string for keyframe retrieval
	 *
	 * @param int $assetID id of asset to return
	 * @param int $page keyframe number to return
	 * @return string REST command string
	 */
	protected function makeKeyframeCommandString($assetID, $keyframe) {
		$cmdStr = '/asset/' . $assetID . '/constituent/keyframe' . $keyframe . '/' . $keyframe . '.jpg';
		return $cmdStr;
	}


	/**
	 * Sends browser headers
	 *
	 * @param array $hdrs array of headers to send to browser
	 */
	protected function sendHeaders($hdrs) {
		header('HTTP/1.1 200 OK');

		foreach ($hdrs as $hdr => $val) {
			$hdrOut = "$hdr: $val";
			//echo "$hdrOut<br />";
			header($hdrOut);
		}
	}

	/**
	 * Sends file to browser
	 *
	 * @param mixed $res image data to return to browser
	 */
	protected function stream($res) {
		echo $res;
	}

	/**
	 * Builds the query string portion of the URI
	 *
	 * @param string $cmdString REST command portion of call
	 * @return string entire command string, including return type and query vars (if any)
	 */
	protected function makeRestCallString($cmdString = '') {
		$restCmdStr = $this->getRESTCommand() . $cmdString . $this->makeQueryString();
		return $restCmdStr;
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxAssetProxy Object                           |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}
}

