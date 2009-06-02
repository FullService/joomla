<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.database.tabletree');

/**
 * Menu table
 *
 * @package 	Joomla.Framework
 * @subpackage	Table
 * @since		1.0
 */
class JTableMenu extends JTableTree
{
	/**
	 * @var int Primary key
	 */
	var $id = null;

	/**
	 * @var string
	 */
	var $menutype = null;

	/**
	 * @var string
	 */
	var $title = null;

	/**
	 * @var string
	 */
	var $alias = null;

	/**
	 * @var string
	 */
	var $link = null;

	/**
	 * @var int
	 */
	var $type = null;

	/**
	 * @var int
	 */
	var $published = null;

	/**
	 * @var int
	 */
	var $component_id = null;

	/**
	 * @var int
	 */
	var $parent_id = null;

	/**
	 * @var int
	 */
	var $ordering = null;

	/**
	 * @var boolean
	 */
	var $checked_out = 0;

	/**
	 * @var datetime
	 */
	var $checked_out_time = 0;

	/**
	 * @var string
	 */
	var $browserNav = null;

	/**
	 * @var int
	 */
	var $access = null;

	/**
	 * @var string
	 */
	var $params = null;

	/**
	 * @var int
	 */
	var $home = null;

	/**
	 * @var int
	 */
	var $template_id = null;

	/**
	 * @var string The full tree path
	 */
	public $path = null;


	/**
	 * Constructor
	 *
	 * @access protected
	 * @param database A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__menu', 'id', $db);

		$this->access	= (int)JFactory::getConfig()->getValue('access');
	}

	/**
	 * Overloaded check function
	 *
	 * @access public
	 * @return boolean
	 * @see JTable::check
	 * @since 1.5
	 */
	function check()
	{
		if (empty($this->alias)) {
			$this->alias = $this->name;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		if (trim(str_replace('-','',$this->alias)) == '') {
			$datenow = &JFactory::getDate();
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
	function bind($array, $ignore = '')
	{
		if (is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
}
