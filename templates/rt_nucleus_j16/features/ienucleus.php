<?php
/**
 * @package     gantry
 * @subpackage  features
 * @version		${project.version} ${build_date}
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - ${copyright_year} RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

defined('JPATH_BASE') or die();

gantry_import('core.gantryfeature');

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryFeatureIENucleus extends GantryFeature {
    var $_feature_name = 'ienucleus';

    function isEnabled(){
        return true;
    }
    function isInPosition($position) {
        return false;
    }
	function isOrderable(){
		return false;
	}
	

	function init() {
		global $gantry;

		if ($gantry->browser->name == 'ie' && $gantry->browser->shortversion == '6') {
			$gantry->addScript('suckerfish-ie6.js');
		}
	}
}