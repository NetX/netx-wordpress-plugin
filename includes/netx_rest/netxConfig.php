<?php
/**
 * netxConfig class file
 *
 * Contains definition for the netxConfig class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Stores configuration settings
 *
 * This class is implemented as a singleton, so you can't create an instance
 * directly with 'new'. To use it, you should do something like:
 * <code>
 * $http = netxConfig::getInstance();
 * </code>
 *
 * @link http://www.php.net/manual/en/language.oop5.patterns.php
 * @package NetxRestAPI
 */
class netxConfig {
	/**
	 * netxConfig instance
	 *
	 * Holds an instance of this class, since it is implemented as
	 * a singleton
	 *
	 * @var netxConfig instance of class
	 * @access private
	 */
	private static $instance;

	/**
	 * Path to HTTP log file
	 *
	 * @var string HTTP log file path
	 * @access private
	 */
	private $httpLogPath = '';

	/**
	 * Path to API log file
	 *
	 * @var string API log file path
	 * @access private
	 */
	private $apiLogPath = '';

	/**
	 * HTTP logging flag
	 *
	 * @var boolean turn on HTTP logging
	 * @access private
	 */
	private $httpLoggingOn = false;

	/**
	 * API logging flag
	 *
	 * @var boolean turn on API logging
	 * @access private
	 */
	private $apiLoggingOn = false;

	/**
	 * File upload path
	 *
	 * @var string file upload path
	 * @access private
	 */
	private $uploadPath = '';

	/**
	 * Delete local file after import into NetX
	 *
	 * @var boolean delete file flag
	 * @access private
	 */
	private $deleteAfterImport = false;

	/**
	 * NetX username
	 *
	 * @var string NetX account username
	 * @access private
	 */
	private $netxUsername = '';

	/**
	 * NetX Account password
	 *
	 * @var string NetX account password
	 * @access private
	 */
	private $netxPassword = '';

	/**
	 * NetX host name
	 *
	 * @var string NetX host name
	 * @access private
	 */
	private $netxHostname = '';

	/**
	 * Directory to use for asset caching
	 *
	 * @var string cache directory
	 * @access private
	 */
	private $cacheDirectory = '';

	/**
	 * Caching flag
	 *
	 * @var boolean caching flag
	 * @access private
	 */
	private $cachingOn = false;

	/**
	 * Cache lifetime
	 *
	 * @var int cache lifetime in seconds
	 * @access private
	 */
	private $cacheLifetime = 3600;

	/**
	 * Constructor
	 *
	 * @return netxConfig
	 */
	private function __construct() {
		$this->httpLogPath = __DIR__ . '/logs/netx_http.log';
		$this->apiLogPath = __DIR__ . '/logs/netx_api.log';
		$this->uploadPath = __DIR__ . '/incoming/';
		$this->cacheDirectory = __DIR__ . '/cache/';
	}

	/**
	 * Get instance of netxConfig.
	 *
	 * netxConfig is implemented as a singleton, so
	 * you can't create one directly, you have to get it this way.
	 * @return netxConfig configuration object
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			$class = __CLASS__;
			self::$instance = new $class;
		}
		return self::$instance;
	}

	/**
	 * Get the HTTP log path
	 *
	 * @return string HTTP log file path
	 */
	public function getHttpLogPath() {
		return $this->httpLogPath;
	}

	/**
	 * Get the NetX username
	 *
	 * @return string username
	 */
	public function getUsername() {
		return $this->netxUsername;
	}

	/**
	 * Get the NetX password
	 *
	 * @return string password
	 */
	public function getPassword() {
		return $this->netxPassword;
	}

	/**
	 * Get the host name for NetX
	 *
	 * @return string NetX host name
	 */
	public function getHost() {
		return $this->netxHostname;
	}

	/**
	 * Is HTTP logging turned on?
	 *
	 * @return boolean http logging flag
	 */
	public function isHttpLoggingOn() {
		return $this->httpLoggingOn;
	}

	/**
	 * Get the API log path
	 *
	 * @return string api log file path
	 */
	public function getApiLogPath() {
		return $this->apiLogPath;
	}

	/**
	 * Is API logging turned on?
	 *
	 * @return boolean http logging flag
	 */
	public function isApiLoggingOn() {
		return $this->apiLoggingOn;
	}

	/**
	 * Delete local copy of files after import into NetX?
	 *
	 * @return boolean local file delete flag
	 */
	public function deleteFilesAfterImport() {
		return $this->deleteAfterImport;
	}

	/**
	 * Get the local files path
	 *
	 * @return string files path
	 */
	public function getFilesPath() {
		return $this->uploadPath;
	}

	/**
	 * Is caching turned on?
	 *
	 * @return boolean caching flag
	 */
	public function isCachingOn() {
		return $this->cachingOn;
	}

	/**
	 * Get the cache directory
	 *
	 * @return string cache directory
	 */
	public function getCacheDirectory() {
		return $this->cacheDirectory;
	}

	/**
	 * Get the cache lifetime
	 *
	 * @return int cache lifetime in seconds
	 */
	public function getCacheLifetime() {
		return $this->cacheLifetime;
	}

	/**
	 * Set the username for NetX authentication
	 *
	 * @param string $newUsername NetX username
	 */
	public function setUsername($newUsername) {
		$this->netxUsername = $newUsername;
	}

	/**
	 * Set the password for NetX authentication
	 *
	 * @param string $newPassword NetX password
	 */
	public function setPassword($newPassword) {
		$this->netxPassword = $newPassword;
	}

	/**
	 * Set the host name for NetX
	 *
	 * @param string $newHost NetX host name
	 */
	public function setHost($newHost) {
		$this->netxHostname = $newHost;
	}

	/**
	 * Set the path for file uploads
	 *
	 * @param string $newFilePath upload file path
	 */
	public function setFilesPath($newFilePath) {
		$this->uploadPath = $newFilePath;
	}

	/**
	 * Set whether http logging is on
	 *
	 * @param boolean $isHttpLoggingOn set whether http logging is on
	 */
	public function setHttpLoggingOn($isHttpLoggingOn) {
		$this->httpLoggingOn = $isHttpLoggingOn;
	}

	/**
	 * Set whether API logging is on
	 *
	 * @param boolean $isApiLoggingOn set whether API logging is on
	 */
	public function setApiLoggingOn($isApiLoggingOn) {
		$this->apiLoggingOn = $isApiLoggingOn;
	}

	/**
	 * Set API logging path
	 *
	 * @param string $newApiLogPath API logging path
	 */
	public function setApiLogPath($newApiLogPath) {
		$this->apiLogPath = $newApiLogPath;
	}

	/**
	 * Set the path for HTTP logging
	 *
	 * @param string $newHttpLoggingPath HTTP logging path
	 */
	public function setHttpLoggingPath($newHttpLoggingPath) {
		$this->httpLogPath = $newHttpLoggingPath;
	}

	/**
	 * Set the flag indicating whether files should be deleted after import
	 *
	 * @param boolean $deleteAfterImport true for delete after import, false otherwise
	 */
	public function setDeleteAfterImport($deleteAfterImport) {
		$this->deleteAfterImport = $deleteAfterImport;
	}

	/**
	 * Turn caching on or off
	 *
	 * @param boolean $cachingFlag caching flag
	 */
	public function setCachingFlag($cachingFlag) {
		$this->cachingOn = $cachingFlag;
	}

	/**
	 * Set the cache directory
	 *
	 * @param string $cacheDirectory caching flag
	 */
	public function setCacheDirectory($cacheDirectory) {
		$this->cacheDirectory = $cacheDirectory;
	}

	/**
	 * Set the cache lifetime
	 *
	 * @param int $cacheLifetime cache lifetime in seconds
	 */
	public function setCacheLifetime($cacheLifetime) {
		$this->cacheLifetime = $cacheLifetime;
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxConfig Object                               |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= '                      Username: ' . $this->netxUsername . "\n";
		$ostr .= '                      Password: ' . $this->netxPassword . "\n";
		$ostr .= '                     Host name: ' . $this->netxHostname . "\n";
		$ostr .= '            HTTP Log Turned On? ' . (($this->httpLoggingOn) ? "yes" : "no") . "\n";
		$ostr .= '                 HTTP Log Path: ' . $this->httpLogPath . "\n";
		$ostr .= '             API Log Turned On? ' . (($this->apiLoggingOn) ? "yes" : "no") . "\n";
		$ostr .= '                  API Log Path: ' . $this->apiLogPath . "\n";
		$ostr .= '                    Files Path: ' . $this->uploadPath . "\n";
		$ostr .= 'Delete files after NetX Import? ' . (($this->deleteAfterImport) ? "yes" : "no") . "\n";
		$ostr .= '                Cache Lifetime: ' . $this->cacheLifetime . "\n";
		$ostr .= '                    Cache Path: ' . $this->cacheDirectory . "\n";
		$ostr .= '                 Is caching on? ' . (($this->cachingFlag) ? "yes" : "no") . "\n";
		$ostr .= '===================================================' . "\n";
		return $ostr;
	}

	/**
	 * Overriding __clone since this is a singleton class
	 *
	 * Triggers an error.
	 */
	public function __clone() {
		trigger_error('You can\'t tune a fish! Or clone a singleton...', E_USER_ERROR);
	}
}
