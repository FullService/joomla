<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_most_commented
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// merge the component configuration into the module parameters
$params->merge(JComponentHelper::getParams('com_social'));

// import library dependencies
require_once(dirname(__FILE__).DS.'helper.php');

// get the user object
$user = &JFactory::getUser();

// get the document object
$document = &JFactory::getDocument();

// get the item list
$list = modSocialMostCommentedHelper::getList($params);

// render the module
require(JModuleHelper::getLayoutPath('mod_social_most_commented', $params->get('layout', 'default')));
