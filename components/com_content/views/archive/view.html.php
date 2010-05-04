<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Content component
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since 1.5
 */
class ContentViewArchive extends JView
{
	protected $state = null;
	protected $item = null;
	protected $items = null;
	protected $pagination = null;

	function display($tpl = null)
	{
		$app =& JFactory::getApplication();
		$user		= &JFactory::getUser();

		$state 		= $this->get('State');
		$items 		= $this->get('Items');
		$pagination	= $this->get('Pagination');

		$pathway	= &$app->getPathway();
		$document	= &JFactory::getDocument();

		// Get the page/component configuration
		$params =& $state->params;

		foreach ($items as $item)
		{
			$item->catslug = ($item->category_alias) ? ($item->catid . ':' . $item->category_alias) : $item->catid;
			$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;
			$article_params = new JRegistry;
			$article_params->loadJSON($item->attribs);
			$temp = clone($params);
			$temp->merge($article_params);
			$item->params = $temp;
		}

		$form = new stdClass();
		// Month Field
		$months = array(
			'' => JText::_('COM_CONTENT_MONTH'),
			'01' => JText::_('JANUARY_SHORT'),
			'02' => JText::_('FEBRUARY_SHORT'),
			'03' => JText::_('MARCH_SHORT'),
			'04' => JText::_('APRIL_SHORT'),
			'05' => JText::_('MAY_SHORT'),
			'06' => JText::_('JUNE_SHORT'),
			'07' => JText::_('JULY_SHORT'),
			'08' => JText::_('AUGUST_SHORT'),
			'09' => JText::_('SEPTEMBER_SHORT'),
			'10' => JText::_('OCTOBER_SHORT'),
			'11' => JText::_('NOVEMBER_SHORT'),
			'12' => JText::_('DECEMBER_SHORT')
		);
		$form->monthField = JHtml::_(
			'select.genericlist',
			$months,
			'month',
			array(
				'list.attr' => 'size="1" class="inputbox"',
				'list.select' => $state->get('filter.month'),
				'option.key' => null
			)
		);
		// Year Field
		$years = array();
		$years[] = JHtml::_('select.option', null, JText::_('Year'));
		for ($i = 2000; $i <= 2020; $i++) {
			$years[] = JHtml::_('select.option', $i, $i);
		}
		$form->yearField = JHtml::_(
			'select.genericlist',
			$years,
			'year',
			array('list.attr' => 'size="1" class="inputbox"', 'list.select' => $state->get('filter.year'))
		);
		$form->limitField = $pagination->getLimitBox();

		$this->assign('filter', $state->get('list.filter'));
		$this->assignRef('form', $form);
		$this->assignRef('items', $items);
		$this->assignRef('params', $params);
		$this->assignRef('user', $user);
		$this->assignRef('pagination', $pagination);

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app		= &JFactory::getApplication();
		$menus		= &JSite::getMenu();
		$pathway	= &$app->getPathway();
		$title 		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('COM_CONTENT_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');
		if (empty($title))
		{
			$title = htmlspecialchars_decode($app->getCfg('sitename'));
		}
		$this->document->setTitle($title);
	}
}
?>
