<?php 
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php';
jimport('joomla.application.categories');

abstract class modArticlesCategoriesHelper
{
	public static function getList(&$params)
	{
		$language = JSite::getLanguage();
		$options = array();
		$options['language'] = $language;
		$categories = JCategories::getInstance('Content', $options);
		$category = $categories->get($params->get('parent', 'root'));
		$items = $category->getChildren();
		if($params->get('count', 0) > 0 && count($items) > $params->get('count', 0))
		{
			$items = array_slice($items, 0, $params->get('count', 0));
		}
		return $items;
	}

}
