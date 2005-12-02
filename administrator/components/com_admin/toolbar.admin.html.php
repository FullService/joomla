<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Admin
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage Admin
*/
class TOOLBAR_admin {
	function _SYSINFO() {

		mosMenuBar::startTable();
		mosMenuBar::title( JText::_( 'Information' ), 'systeminfo.png' );
		mosMenuBar::help( 'screen.system.info' );
		mosMenuBar::endTable();
	}

	function _CPANEL() {

		mosMenuBar::startTable();
		mosMenuBar::title( JText::_( 'Control Panel' ), 'cpanel.png' );
		mosMenuBar::help( 'screen.cpanel' );
		mosMenuBar::endTable();
	}

	function _HELP() {

		mosMenuBar::startTable();
		mosMenuBar::title( JText::_( 'Help' ), 'help_header.png' );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function _PREVIEW() {

		mosMenuBar::startTable();
		mosMenuBar::title( JText::_( 'Preview' ) );
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}
}
?>