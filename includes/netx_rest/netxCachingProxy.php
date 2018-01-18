<?php
/**
 * netxCachingProxy class file
 *
 * Contains definition for the netxCachingProxy class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Manages asset caching for proxied calls
 *
 * @package NetxRestAPI
 */
class netxCachingProxy extends netxAssetProxy {
	/**
	 * Cache lifetime
	 *
	 * @var int cache lifetime in seconds
	 * @access private
	 */
	private $cacheLifetime = 0;

	/**
	 * Cache path
	 *
	 * @var string path to cache directory
	 * @access private
	 */
	private $cachePath = '';

	/**
	 * Mapping of mime type to file suffix
	 *
	 * @var array mime type map to file suffix
	 * @access private
	 */
	private $mimeMap = array();

	/**
	 * Mapping of file suffix to mime type
	 *
	 * @var array mapping of file suffix to mime type
	 * @access private
	 */
	private $suffixMap = array();

	/**
	 * Constructor
	 *
	 * @param netxConnection $conn current connection to server
	 * @param string $cachePath path to cache directory
	 * @param int $cacheLifetime cache lifetime in seconds
	 * @return netxCachingProxy
	 */
	public function __construct(netxConnection $conn, $cachePath, $cacheLifetime) {
		parent::__construct($conn);

		$this->cacheLifetime = $cacheLifetime;
		if (!$cachePath) {
			$this->cachePath = RESTLIBDIR . '/cache/';
		} else {
			$this->cachePath = $cachePath;
		}

		if (!file_exists($this->cachePath)) {
			if (!$this->makeCachePath()) {
				throw new Exception('Could not create cache directory: ' . $this->cachePath);
			}
		}
		if (!is_writable($this->cachePath)) {
			throw new Exception('Cache path is not writable! (' . $this->cachePath . ')');
		}

		$this->initMimeMap();
	}

	/**
	 * Makes the REST call
	 *
	 * Retrieves the file, sets the appropriate headers, and streams the file
	 *
	 * @param string $cmdStr REST command string
	 * @param int $assetID NetX asset ID
	 * @param boolean $attachment set to true to return the file as an attachment
	 * @param string $filename assets original file name
	 * @return mixed outputs file to browser
	 */
	protected function getFile($cmdStr, $assetID, $attachment = false, $filename = "") {
		$this->log("netxCachingProxy: cmdStr = $cmdStr, assetID = $assetID, attachment = " . ($attachment ? 'true' : 'false'));

		if ($attachment) {
			$cacheFile = false;
		} else {
			$cacheFile = $this->checkCache($assetID);
		}

		if ($cacheFile !== false) {
			$mimeType = $this->getFileMimeType($cacheFile);
			$res = file_get_contents($cacheFile);

			$hdrs['Content-Length'] = filesize($cacheFile);
			$hdrs['Content-Type'] = $mimeType;
		} else {
			$res = $this->doCommand($cmdStr);

			$http = $this->connection->getHttp();
			$mimeType = $http->getResponseHeader('Content-Type');

			$this->log("netxCachingProxy: mimeType = $mimeType");

			$hdrs['Content-Type'] = $mimeType;
			$hdrs['Content-Length'] = $http->getResponseHeader('Content-Length');
			if ($attachment) {
				if (strlen($filename) < 1) {
					$dlFilename = $http->getResponseHeader('Content-Disposition');
					if (strlen($dlFilename) < 1) {
						$dlFilename = "file.dat";
					}
				} else {
					$dlFilename = $filename;
				}
				$hdrs['Content-Disposition'] = 'attachment; filename=' . $dlFilename;
			}

			$this->saveCacheFile($assetID, $mimeType, $res);
		}

		$this->log("netxCachingProxy: sending headers:");
		foreach ($hdrs as $hdr => $val) {
			$hdrOut = "$hdr: $val";
			$this->log("netxCachingProxy: $hdrOut");
		}

		$this->sendHeaders($hdrs);
		$this->log("netxCachingProxy: headers sent");

		$this->stream($res);
	}

	/**
	 * Create cache path if it doesn't exist already
	 *
	 * @return boolean success or failure
	 */
	private function makeCachePath() {
		return mkdir($this->cachePath);
	}

	/**
	 * Tests the cached files age, returns true if it is over the limit
	 *
	 * @param int $age mtime of cached file (Unix timestamp)
	 * @return boolean true if the cached file is too old
	 */
	protected function isOld($age) {
		$now = time();
		$age = $now - $age;
		if ($age >= $this->cacheLifetime) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks to see if there is we have the asset in the cache
	 *
	 * @param int $assetID NetX asset id of file to check
	 * @return mixed returns path to cached file if it exists, false otherwise
	 */
	protected function checkCache($assetID) {
		$contentList = array();
		$fileList = glob($this->makeFileGlob($assetID));
		foreach ($fileList as $idx => $filepath) {
			$stats = stat($filepath);
			$filetime = $stats['mtime'];
			if (!$this->isOld($filetime)) {
				array_push($contentList, $filepath);
			}
		}
		if (count($contentList) > 0) {
			return $contentList[0];
		} else {
			return false;
		}
	}

	/**
	 * Creates glob string for searching the file cache
	 *
	 * @param int $assetID NetX asset id of file to check
	 * @return mixed returns file glob string
	 */
	protected function makeFileGlob($assetID) {
		$fileglob = $this->cachePath . $assetID . '_' . $this->makeAssetViewName($this->assetType, $this->viewName) . '.*';
		return $fileglob;
	}

	/**
	 * Creates the name of the file to cache
	 *
	 * @param string $type thumb|preview|zoom|original|flv|view|page|keyframe
	 * @param string $viewName name of the view if the type is 'view'
	 * @return string view name for file name creation
	 */
	protected function makeAssetViewName($type, $viewName = '') {
		switch ($type) {
			case 'thumb':
				$viewName = 'thumb';
				break;
			case 'preview':
				$viewName = 'preview';
				break;
			case 'zoom':
				$viewName = 'zoom';
				break;
			case 'original':
				$viewName = 'original';
				break;
			case 'flv':
				$viewName = 'flv';
				break;
			case 'view':
				$viewName = 'view_' . $viewName;
				break;
			case 'page':
				$viewName = 'page_' . $this->pageNumber;
				break;
			case 'keyframe':
				$viewName = 'keyframe_' . $this->keyframeNumber;
				break;
		}
		return $viewName;
	}

	/**
	 * Saves a file retrieved from NetX to the cache
	 *
	 * @param int assetID id of NetX asset
	 * @param string $mimeType mime type of file
	 * @param string $res contents of file
	 * @return mixed number of bytes written, or false on failure
	 */
	protected function saveCacheFile($assetID, $mimeType, $res) {
		$this->log('netxCachingProxy: asset type = ' . $this->assetType);
		$filename = $this->makeFilename($assetID, $mimeType, $this->assetType, $this->viewName);
		$filepath = $this->cachePath . $filename;
		$fp = fopen($filepath, 'w');
		$written = fwrite($fp, $res);
		fclose($fp);
		return $written;
	}

	/**
	 * Creates file name for cached asset
	 *
	 * @param int assetID id of NetX asset
	 * @param string $mimeType mime type of file
	 * @param string $type thumb|preview|zoom|original|flv|view|page|keyframe
	 * @param string $viewName name of the view if the type is 'view'
	 * @return string filename to use for cached asset
	 */
	protected function makeFilename($assetID, $mimeType, $type, $viewName = '') {
		$filename = $assetID . '_' . $this->makeAssetViewName($type, $viewName) . '.' . $this->mimeTypeToFileSuffix($mimeType);
		return $filename;
	}

	/**
	 * Gets the mime type to return for a file based on file suffix
	 *
	 * @param string $filename name of file to get mime type of
	 * @return string mime type
	 */
	protected function getFileMimeType($filename) {
		$suffix = substr(strrchr($filename, '.'), 1);
		$mimeType = $this->fileSuffixToMimeType($suffix);
		return $mimeType;
	}

	/**
	 * Transform a mime type to file suffix
	 *
	 * @param string $mimeType mime type
	 * @return string filename suffix
	 */
	protected function mimeTypeToFileSuffix($mimeType) {
		if (isset($this->mimeMap[$mimeType])) {
			$fileSuffix = $this->mimeMap[$mimeType];
		} else {
			$fileSuffix = 'dat';
		}
		return $fileSuffix;
	}

	/**
	 * Transform a filename suffix to a mime type
	 *
	 * @param string $suffix file suffix
	 * @return string mime type
	 */
	protected function fileSuffixToMimeType($suffix) {
		if (isset($this->suffixMap[$suffix])) {
			$mimeType = $this->suffixMap[$suffix];
		} else {
			$mimeType = 'application/octet-stream';
		}
		return $mimeType;
	}

	/**
	 * Builds mime type to filename suffix mappings
	 *
	 */
	protected function initMimeMap() {
		$this->mimeMap['image/gif'] = 'gif';
		$this->mimeMap['image/jpg'] = 'jpg';
		$this->mimeMap['image/jpeg'] = 'jpg';
		$this->mimeMap['image/png'] = 'png';
		$this->mimeMap['application/pdf'] = 'pdf';

		$this->suffixMap = array_flip($this->mimeMap);
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxCachingProxy Object                         |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}
}
