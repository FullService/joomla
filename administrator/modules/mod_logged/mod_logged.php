<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @copyright		Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include dependancies.
require_once JPATH::dirname(__FILE__).'/helper.php';

$users = modLoggedHelper::getList($params);
require JModuleHelper::getLayoutPath('mod_logged', $params->get('layout', 'default'));