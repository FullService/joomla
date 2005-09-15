<?php
/**
* @version $Id: globals.php 137 2005-09-12 10:21:17Z eddieajau $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
* Emulates register globals = off
*/
function unregister_globals () {
	$REQUEST 	= $_REQUEST;
	$GET 		= $_GET;
	$POST 		= $_POST;
	$COOKIE 	= $_COOKIE;

	if (isset ( $_SESSION )) {
		$SESSION = $_SESSION;
	}

	$FILES 	= $_FILES;
	$ENV 	= $_ENV;
	$SERVER = $_SERVER;

	foreach ($GLOBALS as $key => $value) {
		if ( $key != 'GLOBALS' ) {
			unset ( $GLOBALS [ $key ] );
		}
	}

	$_REQUEST 	= $REQUEST;
	$_GET 		= $GET;
	$_POST 		= $POST;
	$_COOKIE 	= $COOKIE;

	if (isset ( $SESSION )) {
		$_SESSION = $SESSION;
	}

	$_FILES 	= $FILES;
	$_ENV 		= $ENV;
	$_SERVER 	= $SERVER;

	// Support for IIS which does not support $_SERVER['REQUEST_URI']
	if ( strlen( $_SERVER['REQUEST_URI'] ) == 0 ) {
 		$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
	}
}
unregister_globals ();
?>