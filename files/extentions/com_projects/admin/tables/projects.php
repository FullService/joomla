<?php
/**
 * @version     $Id$
 * @package     Joomla.Administrator
 * @subpackage	jCamp
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	jCamp
 */
class jcTable_Projects extends JTable
{
	var $id	= null;
	var $title	= null;
	var $alias	= null;
	var $description	= null;
	var $start_at	= null;
	var $catid	= null;
	var $finish_at	= null;
	var $ordering	= null;
	var $hits	= null;
	var $created	= null;
	var $created_by	= null;
	var $created_by_alias	= null;
	var $modified	= null;
	var $modified_by	= null;
	var $state	= null;
	var $language	= null;
	var $featured	= null;
	var $xreference	= null;
	var $params	= null;
	var $checked_out	= null;
	var $checked_out_time	= null;	
	
	/**
	 * @param	JDatabase	A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__projects', 'id', $db);
	}
}