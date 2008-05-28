<?php
/**
 * @version		$Id: templates.php 9764 2007-12-30 07:48:11Z ircmaxell $
 * @package		Joomla
 * @subpackage	Menus
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Import library dependencies
require_once(dirname(__FILE__).DS.'extension.php');
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.installer.extension' );
jimport( 'joomla.installer.librarymanifest' );
jimport( 'joomla.filesystem.file' );

if(!defined('LIBRARY_MANIFEST_PATH')) {
	/** Path to Library Manifest Location */
	define('LIBRARY_MANIFEST_PATH',JPATH_ADMINISTRATOR . DS . 'manifests' . DS . 'libraries');
}

/**
 * Extension Manager Templates Model
 *
 * @package		Joomla
 * @subpackage	Installer
 * @since		1.5
 */
class InstallerModelLibraries extends InstallerModel
{
	/**
	 * Extension Type
	 * @var	string
	 */
	var $_type = 'library';

	/**
	 * Overridden constructor
	 * @access	protected
	 */
	function __construct()
	{
		global $mainframe;

		// Call the parent constructor
		parent::__construct();

		// Set state variables from the request
		$this->setState('filter.string', $mainframe->getUserStateFromRequest( "com_installer.libraries.string", 'filter', '', 'string' ));
		$this->setState('filter.client', $mainframe->getUserStateFromRequest( "com_installer.libraries.client", 'client', -1, 'int' ));
	}
	
	/**
	 * Load the data
	 */
	function _loadItems()
	{
		$files =  JFolder::files(LIBRARY_MANIFEST_PATH);
		$rows = Array();
		$file = $files[0];
		
		foreach($files as $file) {
			if(strtolower(JFile::getExt($file)) == 'xml') {
				$rows[] = new JLibraryManifest(LIBRARY_MANIFEST_PATH . DS . $file);
			}
		}
		
		$this->setState('pagination.total', count($rows));
		if($this->_state->get('pagination.limit') > 0) {
			$this->_items = array_slice( $rows, $this->_state->get('pagination.offset'), $this->_state->get('pagination.limit') );
		} else {
			$this->_items = $rows;
		}
	}
	
	/**
	 * Get the details of a given manifest file
	 * @param string file Path to file to load
	 * @return JLibraryManifest populated with data
	 */
	function &getDetails($file) {		
		$library = new JLibraryManifest();
		$retval = false;
		$library->manifest_filename = $file;
		if($library->loadManifestFromXML(LIBRARY_MANIFEST_PATH . DS . $file . '.xml')) 
			return $library;
		else 
			return $retval;
    }   
    	
}