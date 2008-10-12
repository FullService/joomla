<?php
/**
* @version		$Id$
* @package		Joomla.Framework
* @subpackage	Table
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Menu table
 *
 * @package 	Joomla.Framework
 * @subpackage		Table
 * @since	1.0
 */
class JTableMenu extends JTable
{
	/** @var int Primary key */
	public $id = null;
	/** @var string */
	public $menutype = null;
	/** @var string */
	public $name = null;
	/** @var string */
	public $alias = null;
	/** @var string */
	public $link = null;
	/** @var int */
	public $type = null;
	/** @var int */
	public $published = null;
	/** @var int */
	public $componentid = null;
	/** @var int */
	public $parent = null;
	/** @var int */
	public $sublevel = null;
	/** @var int */
	public $ordering = null;
	/** @var boolean */
	public $checked_out = 0;
	/** @var datetime */
	public $checked_out_time = 0;
	/** @var boolean */
	public $pollid = null;
	/** @var string */
	public $browserNav = null;
	/** @var int */
	public $access = null;
	/** @var int */
	public $utaccess = null;
	/** @var string */
	public $params = null;
	/** @var int Pre-order tree traversal - left value */
	public $lft = null;
	/** @var int Pre-order tree traversal - right value */
	public $rgt = null;
	/** @var int */
	public $home = null;

	/**
	 * Constructor
	 *
	 * @access protected
	 * @param database A database connector object
	 */
	protected function __construct( &$db ) {
		parent::__construct( '#__menu', 'id', $db );
	}

	/**
	 * Overloaded check function
	 *
	 * @access public
	 * @return boolean
	 * @see JTable::check
	 * @since 1.5
	 */
	public function check()
	{
		if(empty($this->alias)) {
			$this->alias = $this->name;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		if(trim(str_replace('-','',$this->alias)) == '') {
			$datenow =& JFactory::getDate();
			$this->alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
		}

		return true;
	}

	/**
	* Overloaded bind function
	*
	* @access public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/

	public function bind($array, $ignore = '')
	{
		if (is_array( $array['params'] ))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
}
