<?php
/**
 * netxBean class file
 *
 * Contains definition for the netxBean class.
 *
 * @link http://kb.netx.net/2010/06/rest-api/ REST API documentation
 * @package NetxRestAPI
 */

/**
 * Base class for classes built from XML returned by REST calls
 *
 * @package NetxRestAPI
 * @abstract
 */
abstract class netxBean {
	/**
	 * Parses a date string into a Unix timestamp
	 *
	 * @param string $dateStr date string
	 * @return int Unix timestamp
	 */
	public function mkTimestamp($dateStr) {
		$dateArray = date_parse($dateStr);
		if ($dateArray['error_count'] > 0) {
			$ts = 0;
		} else {
			$ts = mktime($dateArray['hour'], $dateArray['minute'], $dateArray['second'], $dateArray['month'], $dateArray['day'], $dateArray['year']);
		}
		return $ts;
	}

	/**
	 * Returns a formatted date string from a Unix timestamp
	 *
	 * Passes the format string directly to PHP's date() function, so
	 * any formatting valid for the date function can be given here.
	 *
	 * @link http://www.php.net/manual/en/function.date.php
	 * @param int $ts Unix timestamp
	 * @param string $format date format string
	 * @return string formatted date string
	 */
	public function getFormattedDate($ts, $format = 'c') {
		if ($ts == 0) {
			return '';
		} else {
			$dateStr = date($format, $ts);
			return $dateStr;
		}
	}
}
