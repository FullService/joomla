<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

/**
 * Menu Item Model for Menus.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @version		1.6
 */
class MenusModelItem extends JModelForm
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	 protected $_context		= 'com_menus.item';

	/**
	 * Returns a reference to the a Table object, always creating it
	 *
	 * @param	type 	$type 	 The table type to instantiate
	 * @param	string 	$prefix	 A prefix for the table class name. Optional.
	 * @param	array	$options Configuration array for model. Optional.
	 * @return	JTable	A database object
	*/
	public function &getTable($type = 'Menu', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @return	void
	 */
	protected function _populateState()
	{
		// Initialize variables.
		$app = &JFactory::getApplication('administrator');

		// Load the group state.
		if (!($itemId = (int)$app->getUserState('com_menus.edit.item.id'))) {
			$itemId = (int)JRequest::getInt('item_id');
		}
		$this->setState('item.id', $itemId);

		// Load the parameters.
		$params = &JComponentHelper::getParams('com_menus');
		$this->setState('params', $params);
	}

	/**
	 * Method to get a menu item.
	 *
	 * @access	public
	 * @param	integer	The id of the menu item to get.
	 * @return	mixed	Menu item data object on success, false on failure.
	 * @since	1.0
	 */
	public function & getItem($itemId = null)
	{
		// Initialize variables.
		$itemId = (!empty($itemId)) ? $itemId : (int)$this->getState('item.id');
		$false	= false;

		// Get a menu item row instance.
		$table = &$this->getTable();

		// Attempt to load the row.
		$return = $table->load($itemId);

		// Check for a table object error.
		if ($return === false && $table->getError()) {
			$this->serError($table->getError());
			return $false;
		}

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return $false;
		}

		$value = JArrayHelper::toObject($table->getProperties(1), 'JObject');
		return $value;
	}

	/**
	 * Method to get the menu item form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 * @since	1.6
	 */
	public function &getForm()
	{
		// Initialize variables.
		$app	= &JFactory::getApplication();
		$false	= false;

		// Get the form.
		jimport('joomla.form.form');
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT.'/models/fields');
		$form = &JForm::getInstance('jform', 'item', true, array('array' => true));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return $false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_menus.edit.item.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}

		return $form;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function save($data)
	{
		$itemId = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('item.id');
		$isNew	= true;

		// Get a group row instance.
		$table = &$this->getTable();

		// Load the row if saving an existing item.
		if ($itemId > 0) {
			$table->load($itemId);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Get the root item.
		$this->_db->setQuery(
			'SELECT `id`' .
			' FROM `#__menu`' .
			' WHERE `parent_id` = 0',
			0, 1
		);
		$rootId	= (int)$this->_db->loadResult();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Rebuild the hierarchy.
		if (!$table->rebuildTree($rootId)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Rebuild the tree path.
		if (!$table->rebuildPath($table->id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return $table->id;
	}

	/**
	 * Method to delete groups.
	 *
	 * @param	array	An array of item ids.
	 * @return	boolean	Returns true on success, false on failure.
	 */
	public function delete($itemIds)
	{
		// Sanitize the ids.
		$itemIds = (array) $itemIds;
		JArrayHelper::toInteger($itemIds);

		// Get a group row instance.
		$table = &$this->getTable();

		// Iterate the items to delete each one.
		foreach ($itemIds as $itemId) {
			$table->delete($itemId);
		}

		// Get the root item.
		$this->_db->setQuery(
			'SELECT `id`' .
			' FROM `#__menu`' .
			' WHERE `parent_id` = 0',
			0, 1
		);
		$rootId	= (int)$this->_db->loadResult();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Rebuild the hierarchy.
		if (!$table->rebuildTree($rootId)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}


	/**
	 * Method to publish categories.
	 *
	 * @param	array	The ids of the items to publish.
	 * @param	int		The value of the published state
	 *
	 * @return	boolean	True on success.
	 */
	function publish($itemIds, $value = 1)
	{
		// Sanitize the ids.
		$itemIds = (array) $itemIds;
		JArrayHelper::toInteger($itemIds);

		// Get the current user object.
		$user = &JFactory::getUser();

		// Get a category row instance.
		$table = &$this->getTable();

		// Attempt to publish the items.
		if (!$table->publish($itemIds, $value, $user->get('id'))) {
			$this->setError($table->getError());
			return false;
		}

		return true;
	}

	/**
	 * Adjust the category ordering.
	 *
	 * @access	public
	 * @param	integer	Primary key of the item to adjust.
	 * @param	integer	Increment, usually +1 or -1
	 * @return	boolean	True on success.
	 * @since	1.0
	 */
	function ordering($id, $move = 0)
	{
		// Sanitize the id and adjustment.
		$id = (int) $id;
		$move = (int) $move;

		// Get a category row instance.
		$table = &$this->getTable();

		// Attempt to adjust the row ordering.
		if (!$table->ordering($move, $id)) {
			$this->setError($table->getError());
			return false;
		}

		return true;
	}

	/**
	 * Method to check in an item.
	 *
	 * @param	integer	The id of the row to check in.
	 * @return	boolean	True on success.
	 */
	public function checkin($itemId = null)
	{
		// Initialize variables.
		$itemId	= (!empty($itemId)) ? $itemId : (int)$this->getState('item.id');
		$result	= true;

		// Only attempt to check the row in if it exists.
		if ($itemId)
		{
			// Get a category row instance.
			$table = &$this->getTable();

			// Attempt to check the row in.
			if (!$table->checkin($itemId)) {
				$this->setError($table->getError());
				$result	= false;
			}
		}

		return $result;
	}

	/**
	 * Method to check out a item.
	 *
	 * @param	integer	The id of the row to check out.
	 * @return	boolean	True on success.
	 */
	public function checkout($itemId = null)
	{
		// Initialize variables.
		$itemId	= (!empty($itemId)) ? $itemId : (int)$this->getState('item.id');
		$result	= true;

		// Only attempt to check the row in if it exists.
		if ($itemId)
		{
			// Get a category row instance.
			$table = &$this->getTable('Category', 'JTable');

			// Get the current user object.
			$user = &JFactory::getUser();

			// Attempt to check the row out.
			if (!$table->checkout($user->get('id'), $itemId)) {
				$this->setError($table->getError());
				$result	= false;
			}
		}

		return $result;
	}

	/**
	 * Method to perform batch operations on a category or a set of categories.
	 *
	 * @access	public
	 * @param	array	An array of commands to perform.
	 * @param	array	An array of category ids.
	 * @return	boolean	Returns true on success, false on failure.
	 * @since	1.0
	 */
	function batch($commands, $itemIds)
	{
		// Sanitize the ids.
		$itemIds = (array) $itemIds;
		JArrayHelper::toInteger($itemIds);

		// Get the current user object.
		$user = &JFactory::getUser();

		// Get a category row instance.
		$table = &$this->getTable('Category', 'JTable');

		/*
		 * BUILD OUT BATCH OPERATIONS
		 */

		return true;
	}
}
