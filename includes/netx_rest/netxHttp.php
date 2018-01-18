<?php
/**
 * netxHttp class file
 *
 * Contains definition for the netxHttp class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * PEAR Logging class
 */
require_once('lib/PEAR/Log.php');

/**
 * Http connector for REST API
 *
 * This class is implemented as a singleton, so you can't create an instance
 * directly with 'new'. To use it, you should do something like:
 * <code>
 * $http = netxHttp::getInstance();
 * </code>
 *
 * @link http://www.php.net/manual/en/language.oop5.patterns.php
 * @package NetxRestAPI
 */
class netxHttp {
	/**
	 * cURL handle
	 * @var resource cURL handle
	 * @access private
	 */
	private $curlHandle = null;

	/**
	 * Last URI processed
	 * @var string last URI
	 * @access private
	 */
	private $lastURILoaded = '';

	/**
	 * Body of result of last transfer
	 * @var string last transfer body
	 * @access private
	 */
	private $lastCallBody = '';

	/**
	 * Raw header string from last transfer
	 * @var string raw headers from last transfer
	 * @access private
	 */
	private $lastCallHeadersRaw = '';

	/**
	 * Result headers from last transfer
	 *
	 * @var array headers from last transfer
	 * @access private
	 */
	private $lastCallHeaders = array();

	/**
	 * Request headers from last transfer
	 *
	 * @var array request headers from last transfer
	 * @access private
	 */
	private $lastCallRequestHeaders = array();

	/**
	 * Raw result string from last transfer (contains header and body)
	 * @var string raw result string from last transfer
	 * @access private
	 */
	private $lastCallRaw = '';

	/**
	 * Success flag
	 *
	 * Flag indicating whether the last call was successful or not.
	 *
	 * @var boolean indicates whether the last transfer was successful
	 * @access private
	 */
	private $lastCallSuccess = true;

	/**
	 * Information about the last HTTP call made
	 *
	 * This is the array returned from a curl_info call made just after
	 * the last curl_exec()
	 *
	 * @var array information about last call
	 * @access private
	 */
	private $lastCallInfo = array();

	/**
	 * HTTP result code
	 *
	 * HTTP result code from the last transfer
	 *
	 * @var int HTTP result code
	 * @access private
	 */
	private $lastCallHttpResultCode = 0;

	/**
	 * netxHttp instance
	 *
	 * Holds an instance of this class, since it is implemented as
	 * a singleton
	 *
	 * @var netxHttp instance of class
	 * @access private
	 */
	private static $instance = null;

	/**
	 * Handle to PEAR logger
	 *
	 * Handle to PEAR logger, to log HTTP events
	 *
	 * @var mixed PEAR logger
	 * @access private
	 */
	private $logger = null;

	/**
	 * Path to cookie jar file
	 *
	 * @var string path to cookiejar file
	 * @access private
	 */
	private $cookieFilepath = '';

	/**
	 * Is HTTP logging turned on?
	 *
	 * @var boolean HTTP logging flag
	 * @access private
	 */
	private $isLoggingOn = '';

	/**
	 * Path to HTTP log file
	 *
	 * @var string path to http log file
	 * @access private
	 */
	private $logFilePath = '';

	/**
	 * Constructor
	 *
	 * @return netxHttp
	 */
	private function __construct() {
		$config = netxConfig::getInstance();
		$this->isLoggingOn = $config->isHttpLoggingOn();
		$this->logFilePath = $config->getHttpLogPath();

		$this->startLogging();
		$this->log('Constructing netxHttp object...', PEAR_LOG_DEBUG);

		$cookieFile = $this->getCookieFile();
		$this->log('Setting cookie file to: ' . $cookieFile, PEAR_LOG_DEBUG);

		$this->curlHandle = curl_init();
		curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curlHandle, CURLOPT_HEADER, true);
		curl_setopt($this->curlHandle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->curlHandle, CURLINFO_HEADER_OUT, true);
		curl_setopt($this->curlHandle, CURLOPT_COOKIEFILE, $cookieFile);
	}

	/**
	 * Set username and password parameters in cURL for HTTP Basic Auth
	 *
	 * @link http://en.wikipedia.org/wiki/Basic_access_authentication
	 * @param string $username Netx username
	 * @param string $password password
	 */
	public function setHttpBasicAuthParams($username, $password) {
		curl_setopt($this->curlHandle, CURLOPT_USERPWD, "$username:$password");
	}

	/**
	 * Get cookie file path
	 *
	 * @return string cookie file path
	 */
	private function getCookieFile() {
		$this->cookieFilepath = tempnam(sys_get_temp_dir(), 'netx');
		return $this->cookieFilepath;
	}

	/**
	 * Set up logger
	 */
	private function startLogging() {
		if ($this->isLoggingOn) {
			$this->logger = Log::singleton('file', $this->logFilePath);
			$this->logger->setMask(PEAR_LOG_ALL);
		}
	}

	/**
	 * Send message to HTTP logger
	 *
	 * The logging level for the message is one of the constants
	 * defined by the PEAR Log class
	 *
	 * @link http://pear.php.net/
	 *
	 * @param string $logMsg message to send to log file
	 * @param int $logLevel log message level
	 */
	private function log($logMsg, $logLevel=PEAR_LOG_INFO) {
		if ($this->isLoggingOn) {
			$this->logger->log($logMsg, $logLevel);
		}
	}

	/**
	 * Destructor
	 *
	 * Closes cURL handle and deletes cookie file.
	 */
	public function __destruct() {
		curl_close($this->curlHandle);
		$this->log('Deleting cookie file (' . $this->cookieFilepath . ')', PEAR_LOG_DEBUG);
		unlink($this->cookieFilepath);
	}

	/**
	 * Returns instance of netxHttp.
	 *
	 * netxHttp is implemented as a singleton, so
	 * you can't create one directly, you have to get it this way.
	 *
	 * @return netxHttp http object
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			$class = __CLASS__;
			self::$instance = new $class;
		}
		return self::$instance;
	}

	/**
	 * Returns HTTP result code from last transfer
	 *
	 * @link http://en.wikipedia.org/wiki/Http
	 * @link http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	 * @return int HTTP result code from last transfer
	 */
	public function getHttpResultCode() {
		return $this->lastCallHttpResultCode;
	}

	/**
	 * Indicates whether last call was successful
	 *
	 * @return boolean true if last call was successful, false otherwise
	 */
	public function success() {
		return $this->lastCallSuccess;
	}

	/**
	 * HTTP GET
	 *
	 * @param string $uri URI to GET
	 * @return string body of result
	 */
	public function get($uri) {
		$this->log('GET ' . $uri, PEAR_LOG_INFO);

		$this->lastURILoaded = $uri;

		curl_setopt($this->curlHandle, CURLOPT_POST, false);
		curl_setopt($this->curlHandle, CURLOPT_HTTPGET, true);
		curl_setopt($this->curlHandle, CURLOPT_URL, $uri);

		$this->lastCallRaw = curl_exec($this->curlHandle);
		$this->lastCallInfo = curl_getinfo($this->curlHandle);
		$this->lastCallHttpResultCode = $this->lastCallInfo['http_code'];

		$this->log('HTTP Result: ' . $this->lastCallHttpResultCode, PEAR_LOG_INFO);

		if ($this->lastCallRaw === false) {
			$this->lastCallSuccess = false;
			return "";
		} else {
			$this->lastCallSuccess = true;

			$this->lastCallRequestHeaders = $this->httpParseHeaders($this->lastCallInfo['request_header']);

			$this->log('Request headers:', PEAR_LOG_DEBUG);
			foreach ($this->lastCallRequestHeaders as $header => $val) {
				$this->log('  ' . $header . ' : ' . $val, PEAR_LOG_DEBUG);
			}

			$this->log('Result headers:', PEAR_LOG_DEBUG);

			/**
			 * In the case of a server redirect, cURL is returning as the
			 * raw response body the set of headers from every request
			 * in the redirect chain, as well as the actual reponse body from the
			 * finalrequest. So we have to keep stripping off
			 * headers until we get to the set that is NOT a redirect.
			 *
			 * I think.
			 *
			 * This is what a 302 from the NetX call to return
			 * a view (GET http://poached.netx.net/file/asset/55/view/BigPreview/v_55)
			 * looks like:
			 *
			 * HTTP/1.1 302 Moved Temporarily
			 * Date: Tue, 15 Feb 2011 21:48:44 GMT
			 * Server: Apache/2.2.14 (Ubuntu)
			 * Set-Cookie: JSESSIONID=42E8829C3D73651561C7A8714BCE4F01.poached; Path=/
			 * Location: http://poached.netx.net/session/exaFaD6BHZYI2/view_2.jpg
			 * Content-Length: 0
			 * Content-Type: text/plain
			 *
			 * HTTP/1.1 200 OK
			 * Date: Tue, 15 Feb 2011 21:48:44 GMT
			 * Server: Apache/2.2.14 (Ubuntu)
			 * Accept-Ranges: bytes
			 * ETag: W/"172466-1297806524000"
			 * Last-Modified: Tue, 15 Feb 2011 21:48:44 GMT
			 * Content-Length: 172466
			 * Content-Type: image/jpeg
			 *
			 * <jpeg data here...>
			 *
			 *
			 * We were formerly splitting the raw response on the blank line that separates
			 * headers from body, and getting the initial header set. Since we're realying
			 * on the Content-Type header in the response from the NetX server to set the
			 * proper MIME type in the netxAssetProxy class, this breaks because we end
			 * up setting the Content-Type to 'text/plain' and then sending a JPEG out to
			 * the browser, which ends up being rather ugly (esp. since we're not JUST sending
			 * the JPEG out to the browser, but the binary JPEG data with another set of
			 * headers prepended to it...)
			 *
			 * Ok thanks for reading, mosty I thought this is going to end up looking
			 * rather odd and needs some sort of explanation.
			 */
			$rawResponse = $this->lastCallRaw;
			do {
				list($this->lastCallHeadersRaw, $this->lastCallBody) = explode("\r\n\r\n", $rawResponse, 2);
				$this->lastCallHeaders = $this->httpParseHeaders($this->lastCallHeadersRaw);
				$rawResponse = $this->lastCallBody;
				$this->log('  Last Call Response: ' . $this->lastCallHeaders['Response Code'], PEAR_LOG_DEBUG);
				foreach ($this->lastCallHeaders as $header => $val) {
					$this->log('  ' . $header . ' : ' . $val, PEAR_LOG_DEBUG);
				}
				$this->log('  ...', PEAR_LOG_DEBUG);
			} while ($this->lastCallHeaders['Response Code'] == '302');

			return $this->lastCallBody;
		}
	}

	/**
	 * Parse raw headers from response into associative array
	 *
	 * @param string $header raw header string to parse
	 * @return array parsed header data
	 */
	private function httpParseHeaders($header) {
		$retVal = array();
		$fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
		foreach( $fields as $field ) {
			if (preg_match('/([^:]+): (.+)/m', $field, $match)) {
				$match[1] = preg_replace_callback('/(?<=^|[\x09\x20\x2D])./', function($matches) { return strtoupper($matches[0]); }, strtolower(trim($match[1])));
				if (isset($retVal[$match[1]])) {
					$retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
				} else {
					$retVal[$match[1]] = trim($match[2]);
				}
			} else if (preg_match('/^HTTP.+([0-9]{3})/', $field, $statMatch)) {
				if (isset($statMatch[1])) {
					$retVal['Response Code'] = $statMatch[1];
				}
			}
		}
		return $retVal;
	}

	/**
	 * Get specific header value from last call
	 *
	 * @param string $header header to retrieve, i.e. 'Content-Type'
	 * @return string value of header, i.e. 'image/jpeg'
	 */
	public function getResponseHeader($header) {
		if (isset($this->lastCallHeaders[$header])) {
			return $this->lastCallHeaders[$header];
		} else {
			return '';
		}
	}

	/**
	 * HTTP POST file upload
	 *
	 * @param string $uri URI to upload file to
	 * @param array $filePath file to upload
	 * @return string body of result
	 */
	public function postFile($uri, $filePath) {
		$this->lastURILoaded = $uri;

		$fileDirectory = dirname($filePath);
		$fileName = basename($filePath);

		$uploadFilePath = '@' . $fileName;

		$data = array('file' => $uploadFilePath);

		$this->log('POST ' . $uri, PEAR_LOG_INFO);
		$this->log('  file => ' . $uploadFilePath, PEAR_LOG_DEBUG);

		curl_setopt($this->curlHandle, CURLOPT_HTTPGET, false);
		curl_setopt($this->curlHandle, CURLOPT_POST, true);
		curl_setopt($this->curlHandle, CURLOPT_URL, $uri);
		curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $data);
		curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, array('Expect:'));

		$cwd = getcwd();
		chdir($fileDirectory . '/');
		$this->lastCallRaw = curl_exec($this->curlHandle);
		chdir($cwd);

		$this->lastCallInfo = curl_getinfo($this->curlHandle);

		$this->lastCallHttpResultCode = $this->lastCallInfo['http_code'];

		$this->log('HTTP Result: ' . $this->lastCallHttpResultCode, PEAR_LOG_INFO);

		if ($this->lastCallRaw === false) {
			$this->lastCallSuccess = false;
			$this->log('File upload failed', PEAR_LOG_NOTICE);
			return false;
		} else {
			$this->log('File upload succeeded', PEAR_LOG_NOTICE);
			$this->lastCallSuccess = true;
			list($this->lastCallHeadersRaw, $this->lastCallBody) = explode("\r\n\r\n", $this->lastCallRaw, 2);
			$this->lastCallHeaders = $this->httpParseHeaders($this->lastCallHeadersRaw);

			$this->lastCallRequestHeaders = $this->httpParseHeaders($this->lastCallInfo['request_header']);

			$this->log('Request headers:', PEAR_LOG_DEBUG);
			foreach ($this->lastCallRequestHeaders as $header => $val) {
				$this->log('  ' . $header . ' : ' . $val, PEAR_LOG_DEBUG);
			}

			$this->log('Result headers:', PEAR_LOG_DEBUG);
			foreach ($this->lastCallHeaders as $header => $val) {
				$this->log('  ' . $header . ' : ' . $val, PEAR_LOG_DEBUG);
			}

			return true;
		}

		return false;
	}

	/**
	 * HTTP POST
	 *
	 * @param string $uri URI to POST to
	 * @param array $dataAr data to send in POST
	 * @return string body of result
	 */
	public function post($uri, $dataAr) {
		$this->logger->log('POST ' . $uri, PEAR_LOG_INFO);

		$this->lastURILoaded = $uri;

		return "";
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 *
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxHttp Object                                 |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= 'Last URI: ' . $this->lastURILoaded . "\n";
		$ostr .= 'Last transfer successful? ' . (($this->lastCallSuccess) ? 'Yes' : 'No') . "\n";
		$ostr .= 'Last transfer HTTP result code: ' . $this->lastCallHttpResultCode . "\n";
		$ostr .= 'cURL Info Array:' . "\n";
		foreach ($this->lastCallInfo as $key => $val) {
			$ostr .= "\t\t$key: " . $val . "\n";
		}
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
