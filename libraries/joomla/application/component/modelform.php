<?php
/**
 * @version		$Id$
 * @package		Joomla
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.form.form');

/**
 * Prototype form model.
 *
 * @package		Joomla.Framework
 * @subpackage	Application
 * @since		1.6
 */
class JModelForm extends JModel
{
	/**
	 * Array of form objects.
	 */
	protected $_forms = array();

	/**
	 * Method to allow derived classes to add more forms.
	 *
	 * @param	object	A form object.
	 *
	 * @return	mixed	True if successful.
	 * @throws	Exception if there is an error loading the form.
	 * @since	1.6
	 */
	protected function addForms($form)
	{
		return true;
	}

	/**
	 * Method to checkin a row.
	 *
	 * @param	integer	$pk The numeric id of the primary key.
	 *
	 * @return	boolean	False on failure or error, true otherwise.
	 */
	public function checkin($pk = null)
	{
		// Only attempt to check the row in if it exists.
		if ($pk) {
			$user = JFactory::getUser();

			// Get an instance of the row to checkin.
			$table = $this->getTable();
			if (!$table->load($pk)) {
				$this->setError($table->getError());
				return false;
			}

			// Check if this is the user having previously checked out the row.
			if ($table->checked_out > 0 && $table->checked_out != $user->get('id')) {
				$this->setError(JText::_('JError_Checkin_user_mismatch'));
				return false;
			}

			// Attempt to check the row in.
			if (!$table->checkin($pk)) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to check-out a row for editing.
	 *
	 * @param	int		$pk	The numeric id of the primary key.
	 *
	 * @return	boolean	False on failure or error, true otherwise.
	 */
	public function checkout($pk = null)
	{
		// Only attempt to check the row in if it exists.
		if ($pk) {
			$user = JFactory::getUser();

			// Get an instance of the row to checkout.
			$table = $this->getTable();
			if (!$table->load($pk)) {
				$this->setError($table->getError());
				return false;
			}

			// Check if this is the user having previously checked out the row.
			if ($table->checked_out > 0 && $table->checked_out != $user->get('id')) {
				$this->setError(JText::_('JError_Checkout_user_mismatch'));
				return false;
			}

			// Attempt to check the row out.
			if (!$table->checkout($user->get('id'), $pk)) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to get a form object.
	 *
	 * @param	string		$name		The name of the form.
	 * @param	string		$data		The form data. Can be XML string if file flag is set to false.
	 * @param	array		$options	Optional array of parameters.
	 * @param	boolean		$clear		Optional argument to force load a new form.
	 * @param	string		$xpath		An optional xpath to search for the fields.
	 * @return	mixed		JForm object on success, False on error.
	 */
	function getForm($name, $data = null, $options = array(), $clear = false, $xpath = false)
	{
		// Handle the optional arguments.
		$options['control']	= JArrayHelper::getValue($options, 'control', false);
		$options['event']	= JArrayHelper::getValue($options, 'event');
		$options['group']	= JArrayHelper::getValue($options, 'group');

		// Create a signature hash.
		$hash = md5($data.serialize($options));

		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear) {
			return $this->_forms[$hash];
		}

		// Get the form.
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT.'/models/fields');

		try {
			$form = JForm::getInstance($name, $data, $options, false, $xpath);

			// Allow for supplemental data to be loaded before the triggers.
			$this->addForms($form);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			$false = false;
		}

		// Look for an event to fire.
		if ($options['event'] !== null) {
			// Get the dispatcher.
			$dispatcher	= JDispatcher::getInstance();

			// Load an optional plugin group.
			if ($options['group'] !== null) {
				JPluginHelper::importPlugin($options['group']);
			}

			// Trigger the form preparation event.
			$results = $dispatcher->trigger($options['event'], array($form->getName(), $form));

			// Check for errors encountered while preparing the form.
			if (count($results) && in_array(false, $results, true)) {
				// Get the last error.
				$error = $dispatcher->getError();

				// Convert to a JException if necessary.
				if (!JError::isError($error)) {
					$error = new JException($error, 500);
				}

				return $error;
			}
		}

		// Store the form for later.
		$this->_forms[$hash] = $form;

		return $form;
	}



	/**
	 * Method to validate the form data.
	 *
	 * @param	object		$form		The form to validate against.
	 * @param	array		$data		The data to validate.
	 * @return	mixed		Array of filtered data if valid, false otherwise.
	 * @since	1.1
	 */
	function validate($form, $data)
	{
		// Filter and validate the form data.
		$data	= $form->filter($data);
		$return	= $form->validate($data);

		// Check for an error.
		if (JError::isError($return)) {
			$this->setError($return->getMessage());
			return false;
		}

		// Check the validation results.
		if ($return === false) {
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message) {
				$this->setError($message);
			}

			return false;
		}

		return $data;
	}
}
