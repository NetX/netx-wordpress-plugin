<?php
/**
 * Main include file for NetX REST API classes.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

define('NETX_RESTAPI_VERSION', '1.0.2');

if (!defined('__DIR__')) {
	$iPos = strrpos(__FILE__, '/');
	define('__DIR__', substr(__FILE__, 0, $iPos));
}

define('RESTLIBDIR', __DIR__);

/**
 * netxConfig class definition
 */
require_once('netxConfig.php');

/**
 * netxBean class definition
 */
require_once('netxBean.php');

/**
 * netxNetX class definition
 */
require_once('netxNetX.php');

/**
 * netxRestClient class definition
 */
require_once('netxRestClient.php');

/**
 * netxCategoryProc class definition
 */
require_once('netxCategoryProc.php');

/**
 * netxAssetProc class definition
 */
require_once('netxAssetProc.php');

/**
 * netxHttp class definition
 */
require_once('netxHttp.php');

/**
 * netxUser class definition
 */
require_once('netxUser.php');

/**
 * netxCategory class definition
 */
require_once('netxCategory.php');

/**
 * netxCart class definition
 */
require_once('netxCart.php');

/**
 * netxAsset class definition
 */
require_once('netxAsset.php');

/**
 * netxAssetList class definition
 */
require_once('netxAssetList.php');

/**
 * netxAssetListProc class definition
 */
require_once('netxAssetListProc.php');

/**
 * netxBeanFactory class definition
 */
require_once('netxBeanFactory.php');

/**
 * netxConnection class definition
 */
require_once('netxConnection.php');

/**
 * netxImporter class definition
 */
require_once('netxImporter.php');

/**
 * netxAssetProxy class definition
 */
require_once('netxAssetProxy.php');

/**
 * netxCachingProxy class definition
 */
require_once('netxCachingProxy.php');
