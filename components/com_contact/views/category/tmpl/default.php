<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_contacts
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$pageClass = $this->params->get('pageclass_sfx');
?>
<div class="contact-category<?php echo $pageClass;?>">
<?php if ($this->params->def('show_page_heading', 1)) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
<?php if($this->params->get('show_category_title', 1)) : ?>
<h2>
	<?php echo JHtml::_('content.prepare', $this->category->title); ?>
</h2>
<?php endif; ?>
<?php if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="category-desc">
	<?php if ($this->params->get('image') != -1 && $this->params->get('image') != '') : ?>
		<img src="<?php echo $this->baseurl .'/'. 'images' . '/'. $this->params->get('image'); ?>" align="<?php echo $this->params->get('image_align'); ?>" hspace="6" alt="<?php echo JText::_( 'Contacts' ); ?>" />
	<?php elseif ($this->category->image) : ?>
		<img src="<?php echo $this->baseurl .'/'. 'images' . '/'. $this->category->image; ?>" align="<?php echo $this->category->image_position; ?>" hspace="6" alt="<?php echo JText::_( 'Contacts' ); ?>" />
	<?php endif; ?>
	
	<?php echo $this->category->description; ?>
	<div class="clr"></div>
	</div>
<?php endif; ?>

<?php echo $this->loadTemplate('items'); ?>

<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
<div class="cat-children">
	<h3><?php echo JText::_('JGLOBAL_SUBCATEGORIES') ; ?></h3>
	<?php echo $this->loadTemplate('children'); ?>
</div>
<?php endif; ?>
</div>