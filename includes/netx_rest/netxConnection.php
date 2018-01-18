<?php
/**
 * netxConnection class file
 *
 * Contains definition for the netxConnection class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Active connection to NetX server
 *
 * You must use either netxConnection::ConnectLogin() or netxConnection::ConnectBasicAuth()
 * to create a new connection.
 *
 * @package NetxRestAPI
 */
class netxConnection extends netxRestClient {
	/**
	 * Currently connected user
	 *
	 * @var netxUser Currently connected user
	 * @access private
	 */
	private $user = null;

	/**
	 * HTTP manager
	 *
	 * @var netxHttp HTTP manager
	 * @access private
	 */
	private $http = null;

	/**
	 * Base URI for REST calls
	 *
	 * This is the base path for NetX REST calls used by all of the API calls,
	 * e.g. 'poached.netx.net'. If the base URI is in a subdirectoy, do NOT
	 * include the trailing / (e.g. poached.netx.net/rest).
	 *
	 * @var string Base URI for REST calls
	 * @access private
	 */
	private $restBaseURI = '';

	/**
	 * Base href
	 *
	 * @var string Base href
	 * @access private
	 */
	private $restBaseHref = '';

	/**
	 * netxConnection instance
	 *
	 * Holds an instance of this class, since it is implemented as
	 * a singleton
	 *
	 * @var netxConnection instance of class
	 * @access private
	 */
	private static $instance;

	/**
	 * Constructor
	 *
	 * @param string $baseURI Base REST URI
	 * @return netxConnection
	 */
	protected function __construct($baseURI) {
		parent::__construct('login', $this);

		$baseRestUri = 'http://' . $baseURI;
		$this->restBaseURI = $baseRestUri;

		$baseHref = 'http://' . $baseURI;
		$this->restBaseHref = $baseHref;

		$this->http = netxHttp::getInstance();
	}

	/**
	 * Get base URI
	 *
	 * @return string Base URI for all REST calls
	 */
	public function getBaseURI() {
		return $this->restBaseURI;
	}

	/**
	 * Get base href
	 *
	 * @return string base href
	 */
	public function getBaseHref() {
		return $this->restBaseHref;
	}

	/**
	 * Get HTTP manager
	 *
	 * @return netxHttp HTTP Manager
	 */
	public function getHttp() {
		return $this->http;
	}

	/**
	 * Get connected user
	 *
	 * @return netxUser connnected user
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Creates a connection object but does not connect to the server
	 *
	 * @link http://en.wikipedia.org/wiki/Basic_access_authentication
	 * @param string $username Netx username
	 * @param string $password password
	 * @param string $baseURI base REST URI (i.e. 'poached.netx.net')
	 * @return netxConnection
	 */
	public static function nullConnection($username, $password, $baseURI) {
		$conn = new netxConnection($baseURI);
		self::$instance = $conn;

		return self::$instance;
	}

	/**
	 * Creates a connection using HTTP Basic Authentication
	 *
	 * @link http://en.wikipedia.org/wiki/Basic_access_authentication
	 * @param string $username Netx username
	 * @param string $password password
	 * @param string $baseURI base REST URI (i.e. 'poached.netx.net')
	 * @return netxConnection
	 */
	public static function ConnectBasicAuth($username, $password, $baseURI) {
		$conn = new netxConnection($baseURI);

		$conn->doHttpBasicAuthConnect($username, $password);

		self::$instance = $conn;

		return self::$instance;
	}

	/**
	 * Creates a connection using REST API login call
	 *
	 * @param string $username Netx username
	 * @param string $password password
	 * @param string $baseURI base REST URI (i.e. 'poached.netx.net')
	 * @return netxConnection
	 */
	public static function ConnectLogin($username, $password, $baseURI) {
		$conn = new netxConnection($baseURI);

		$conn->doLoginConnect($username, $password);

		self::$instance = $conn;

		return self::$instance;
	}

	/**
	 * Create the connection using HTTP Basic Auth
	 *
	 * @link http://en.wikipedia.org/wiki/Basic_access_authentication
	 * @param string $username Netx username
	 * @param string $password password
	 */
	protected function doHttpBasicAuthConnect($username, $password) {
		$this->http->setHttpBasicAuthParams($username, $password);
	}

	/**
	 * Create the connection using the REST login call
	 *
	 * @param string $username Netx username
	 * @param string $password password
	 */
	protected function doLoginConnect($username, $password) {
		$this->addGetVar('username', $username);
		$this->addGetVar('password', $password);

		$xml = $this->doCommand();

		$this->user = netxBeanFactory::parseBeanXML($xml);
	}

	/**
	 * Returns string representation of object so you can just echo it to the page
	 * @return string String representation of the object
	 */
	public function __toString() {
		$ostr =  '===================================================' . "\n";
		$ostr .= '| netxConnection Object                           |' . "\n";
		$ostr .= '===================================================' . "\n";
		$ostr .= 'Base REST URI: ' . $this->restBaseURI . "\n\n";
		$ostr .= 'HTTP Manager:' . "\n";
		$ostr .= $this->http->__toString();
		$ostr .= 'Connected user:' . "\n";
		$ostr .= $this->user->__toString();
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
