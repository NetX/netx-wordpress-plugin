<?php

class netxWordpressAssetLoader extends netxAssetProxy {

	private $uploadPath = '';
	private $uploadUrl = '';
    private $uploadFilePath = '';
	private $uploadUrlPath = '';

	public function __construct(netxConnection $conn) {
		parent::__construct($conn);

        $netx_upload_dir = wp_upload_dir()['basedir'].'/netx';
        if ( ! file_exists( $netx_upload_dir ) ) {
            wp_mkdir_p( $netx_upload_dir );
        }

        $this->uploadPath = $netx_upload_dir . '/';
		$this->uploadUrl = wp_upload_dir()['baseurl'].'/netx/';
	}

    public function getUploadFilePath() {
        return $this->uploadFilePath;
    }

    public function getUploadUrlPath() {
        return $this->uploadUrlPath;
    }

	protected function getFile($cmdStr, $assetID, $attachment = false, $filename = "") {
		$res = $this->doCommand($cmdStr);

		$http = $this->connection->getHttp();
		$mimeType = $http->getResponseHeader('Content-Type');

        $this->saveCacheFile($assetID, $mimeType, $res);
	}

	protected function saveCacheFile($assetID, $mimeType, $res) {
        //build filename / path
		$counter = 0;
		do {
			$filename = $this->makeFilename($assetID, $mimeType, $this->assetType, $this->viewName, $counter++);

			$this->uploadFilePath = $this->uploadPath . $filename;
			$this->uploadUrlPath = $this->uploadUrl . $filename;
		} while (file_exists($this->uploadFilePath));


        //store file
		$fp = fopen($this->uploadFilePath, 'w');
		$written = fwrite($fp, $res);
		fclose($fp);

		return $written;
	}

	protected function makeFilename($assetID, $mimeType, $type, $viewName = '', $counter = 0) {
		$filename = $assetID . '_' . $this->makeAssetViewName($type, $viewName) . ($counter > 0 ? ('_' . $counter. '_') : '') . '.' . $this->mimeTypeToFileSuffix($mimeType);
		return $filename;
	}


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

	protected function mimeTypeToFileSuffix($mimeType) {
		if (isset($this->getMimeMap()[$mimeType])) {
			$fileSuffix = $this->getMimeMap()[$mimeType];
		} else {
			$fileSuffix = 'dat';
		}
		return $fileSuffix;
	}


	protected function getMimeMap() {
        $mimeMap = array();

		$mimeMap['image/gif'] = 'gif';
		$mimeMap['image/jpg'] = 'jpg';
		$mimeMap['image/jpeg'] = 'jpg';
		$mimeMap['image/png'] = 'png';
		$mimeMap['application/pdf'] = 'pdf';

        return $mimeMap;
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxWordpressAssetLoader Object                 |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}
}

