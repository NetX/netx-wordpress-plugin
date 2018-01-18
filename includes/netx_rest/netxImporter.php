<?php
/**
 * netxImporter class file
 *
 * Contains definition for the netxImporter class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @link http://www.faqs.org/rfcs/rfc1867.html
 * @link http://commons.apache.org/fileupload/
 * @package NetxRestAPI
 */

/**
 * For importing files into NetX
 *
 * @package NetxRestAPI
 */
class netxImporter extends netxRestClient {
	/**
	 * Should local copies deleted after import
	 *
	 * @var boolean Local file deletion flag
	 * @access private
	 */
	private $deleteFilesAfterImport = false;

	/**
	 * Constructor
	 *
	 * @param netxConnection $conn current connection to server
	 * @return netxImporter
	 */
	public function __construct(netxConnection $conn) {
		parent::__construct('import', $conn);

		$config = netxConfig::getInstance();
		$this->deleteFilesAfterImport = $config->deleteFilesAfterImport();
		$this->localPath = $config->getFilesPath();
	}

	/**
	 * Import a file into NetX
	 *
	 * If configured, deletes the local copy of the file after import.
	 *
	 * @param string $localFilePath path of local file to import into NetX
	 * @param string $netxCategoryPath NetX category path to import to
	 * @return netxAsset asset object created by NetX
	 */
	public function import($localFilePath, $netxCategoryPath) {
		if ($this->fileExists($localFilePath)) {
			$filename = $this->getFileName($localFilePath);

			if (!$this->uploadFile($localFilePath)) {
				return false;
			}
			$asset = $this->importFile($filename, $netxCategoryPath);

			if ($this->deleteFilesAfterImport) {
				$this->removeLocalFile($localFilePath);
			}

			return $asset;
		} else {
			throw new Exception('File not found: ' . $localFilePath);
		}
	}

	/**
	 * Remove the local file
	 */
	private function removeLocalFile($filePath) {
		unlink($filePath);
	}

	/**
	 * Get the filename portion of the path
	 *
	 * @return string filename
	 */
	private function getFileName($filePath) {
		return basename($filePath);
	}

	/**
	 * Check to make sure the local file exists
	 *
	 * @return boolean true if successful
	 */
	private function fileExists($filePath) {
		return file_exists($filePath);
	}

	/**
	 * Do the file upload step
	 *
	 * @param string $localFilePath file to upload
	 * @return boolean true if successful
	 */
	private function uploadFile($localFilePath) {
		$cmdString = '/servlet/FileUploader';
		$uri = $this->connection->getBaseHref() . $cmdString;
		$this->log($cmdString);
		$http = $this->connection->getHttp();
		return $http->postFile($uri, $localFilePath);
	}

	/**
	 * Do the NetX import step
	 *
	 * @param string $filename name of the file being imported
	 * @param string $netxCategoryPath category path to upload to
	 * @return netxAsset returns the asset object created by NetX
	 */
	private function importFile($filename, $netxCategoryPath) {
		//$uri = $this->makeURI() . '/' . $netxCategoryPath . '/' . $filename;
		//$xml = $this->connection->getHttp()->get($uri);
		$cmdString = '/' . $netxCategoryPath . '/' . $filename;
		$xml = $this->doCommand($cmdString);
		if ($xml) {
			$asset = netxBeanFactory::parseBeanXML($xml);
			return $asset;
		} else {
			return null;
		}
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxImporter Object                             |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= 'Delete after import? ' . (($this->deleteFilesAfterImport) ? 'true' : 'false') . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}
}
