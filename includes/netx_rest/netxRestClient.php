<?php
/**
 * netxRestClient class file
 *
 * Contains definition for the netxRestClient class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Base class for all classes that implement REST calls
 *
 * @package NetxRestAPI
 * @abstract
 */
abstract class netxRestClient {
	/**
	 * Current connection
	 *
	 * @var netxConnection current connection
	 */
	protected $connection = null;

	/**
	 * REST command string
	 *
	 * This is the REST command string. For example, if the URI is something
	 * like 'http://poached.netx.net/xml/login?username=blah&password=foo' the
	 * command is 'login'
	 *
	 * @var string REST command string
	 * @access private
	 */
	private $callStr = '';

	/**
	 * Paramater array for query strings
	 *
	 * Contains name/value pairs to be inserted in the query string
	 *
	 * @var array name/value pairs for query string
	 * @access private
	 */
	private $getVars = array();

	/**
	 * Return type for REST calls
	 *
	 * The REST API currently supports returntypes of 'xml' or 'json'
	 *
	 * @var string format of data returned by API calls
	 * @access private
	 */
	private $returnType = '';

	/**
	 * Handle to PEAR logger
	 *
	 * Handle to PEAR logger, to log API calls
	 *
	 * @var mixed PEAR logger
	 * @access private
	 */
	private $logger = null;

	/**
	 * Is API logging turned on?
	 *
	 * @var boolean API logging flag
	 * @access private
	 */
	private $isLoggingOn = '';

	/**
	 * Path to API log file
	 *
	 * @var string path to api log file
	 * @access private
	 */
	private $logFilePath = '';

	/**
	 * Constructor
	 *
	 * @param string $commandStr REST command string
	 * @param netxConnection $conn current connection
	 * @return netxRestClient
	 */
	protected function __construct($commandStr, netxConnection $conn, $returnType = 'xml') {
		$config = netxConfig::getInstance();
		$this->isLoggingOn = $config->isApiLoggingOn();
		$this->logFilePath = $config->getApiLogPath();

		$this->startLogging();

		$this->callStr = $commandStr;
		$this->connection = $conn;
		$this->returnType = $returnType;
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
	 * Send message to API logger
	 *
	 * The logging level for the message is one of the constants
	 * defined by the PEAR Log class
	 *
	 * @link http://pear.php.net/
	 *
	 * @param string $logMsg message to send to log file
	 * @param int $logLevel log message level
	 */
	protected function log($logMsg, $logLevel = PEAR_LOG_INFO) {
		if ($this->isLoggingOn) {
			$this->logger->log($logMsg, $logLevel);
		}
	}

	/**
	 * Set call return type
	 *
	 * Valid return types are 'xml' and 'json'
	 *
	 * @param string $returnType API call return type
	 */
	public function setReturnType($returnType) {
		$this->returnType = $returnType;
	}

	/**
	 * Get call return type
	 *
	 * @return string API call return type
	 */
	public function getReturnType() {
		return $this->returnType;
	}

	/**
	 * Get REST command string
	 *
	 * @return string REST command string
	 */
	public function getRESTCommand() {
		return $this->callStr;
	}

	/**
	 * Creates the URI for the REST call
	 *
	 * Creates the URI for the rest call, including base URI, command
	 * string, and query string. This function should return a URI that can
	 * be sent to the HTTP manager to grab.
	 *
	 * @param string $restCmdStr full rest command string, including return type and query vars (if any)
	 * @return string Full URI for REST call
	 */
	public function makeURI($restCmdStr = '') {
		$uri = $this->connection->getBaseURI() . '/' . $restCmdStr;
		return $uri;
	}

	/**
	 * Add a variable to the query string
	 *
	 * @param string $varname name of query string value
	 * @param mixed $val value of variable for query string
	 */
	protected function addGetVar($varname, $val) {
		$this->getVars[$varname] = $val;
	}

	/**
	 * Builds the query string portion of the URI
	 *
	 * @return string query string
	 */
	protected function makeQueryString() {
		if (count($this->getVars) > 0) {
			$first = true;
			$query = '';
			foreach ($this->getVars as $name => $val) {
				if ($first) {
					$query .= '?';
					$first = false;
				} else {
					$query .= '&';
				}
				$query .= $name . '=' . $val;
			}
		} else {
			$query = '';
		}
		return $query;
	}

	/**
	 * Executes REST command
	 *
	 * Builds the final URI, and calls the HTTP manager, and returns the results.
	 *
	 * @param string $cmdString REST command string
	 * @return string result body from REST call
	 */
	protected function doCommand($cmdString = '') {
		$callString = $this->makeRestCallString($cmdString);
		$this->log($callString);
		$uri = $this->makeURI($callString);
        $xml = $this->connection->getHttp()->get($uri);
        $this->log($xml);
		return $xml;
	}

	/**
	 * Builds the query string portion of the URI
	 *
	 * @param string $cmdString REST command portion of call
	 * @return string entire command string, including return type and query vars (if any)
	 */
	protected function makeRestCallString($cmdString = '') {
		$restCmdStr = $this->returnType . '/' . $this->callStr . $cmdString . $this->makeQueryString();
		return $restCmdStr;
	}
}
