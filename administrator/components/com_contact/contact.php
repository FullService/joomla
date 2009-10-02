<?php
/**
 * @version		$Id: weblinks.php 11952 2009-06-01 03:21:19Z robs $
 * @package		Joomla.Administrator
 * @subpackage	com_weblinks
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_contact')) {
	JFactory::getApplication()->redirect('', JText::_('ALERTNOTAUTH'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JController::getInstance('contact');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
