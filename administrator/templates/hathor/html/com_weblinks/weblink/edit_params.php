<?php
/**
 * @version		$Id: edit_params.php 16403 2010-04-24 00:35:09Z eddieajau $
 * @package		Joomla.Administrator
 * @subpackage	com_weblinks
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$fieldSets = $this->form->getFieldsets('params');
foreach ($fieldSets as $name => $fieldSet) :
	echo JHtml::_('sliders.panel',JText::_($fieldSet->label), $name.'-params');
	if (isset($fieldSet->description) && trim($fieldSet->description)) :
		echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
	endif;
	?>
	<fieldset class="panelform">
	<legend class="element-invisible"><?php echo JText::_($fieldSet->label); ?></legend>
		<?php foreach ($this->form->getFieldset($name) as $field) : ?>
			<?php echo $field->label; ?>
			<?php echo $field->input; ?>
		<?php endforeach; ?>
	</fieldset>
<?php endforeach; ?>