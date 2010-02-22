<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
?>

<script type="text/javascript">
<!--
	function submitbutton(task)
	{
		if (task == 'user.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			submitform(task);
		}
	}
// -->
</script>

<form action="<?php JRoute::_('index.php?option=com_users'); ?>" method="post" name="adminForm" id="user-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('Users_User_Account_Details'); ?></legend>
			<?php echo $this->form->getLabel('name'); ?>
			<?php echo $this->form->getInput('name'); ?>

			<?php echo $this->form->getLabel('username'); ?>
			<?php echo $this->form->getInput('username'); ?>

			<?php echo $this->form->getLabel('password'); ?>
			<?php echo $this->form->getInput('password'); ?>

			<?php echo $this->form->getLabel('password2'); ?>
			<?php echo $this->form->getInput('password2'); ?>

			<?php echo $this->form->getLabel('email'); ?>
			<?php echo $this->form->getInput('email'); ?>
		</fieldset>

		<?php
		foreach($fieldsets as $fieldset)
		{
			if($fieldset->name == 'settings')
			{
				?>
		<fieldset class="adminform">
			<legend><?php echo JText::_($fieldset->label); ?></legend>
			<?php foreach($this->form->getFieldset($fieldset->name) as $field): ?>
				<?php if ($field->hidden): ?>
					<?php echo $field->input; ?>
				<?php else: ?>
					<?php echo $field->label; ?>
					<?php echo $field->input; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</fieldset>
				<?php
			}
		}
		?>
	</div>

	<div class="width-40 fltrt">
			<?php echo JHTML::_('sliders.start');
		foreach($fieldsets as $fieldset)
		{
			if($fieldset->name != 'settings')
			{
				echo JHTML::_('sliders.panel', JText::_($fieldset->label), $fieldset->name);
				?><fieldset class="panelform"><?php
				foreach($this->form->getFieldset($fieldset->name) as $field): ?>
				<?php if ($field->hidden): ?>
					<?php echo $field->input; ?>
				<?php else: ?>
					<?php echo $field->label; ?>
					<?php echo $field->input; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			</fieldset>
			<?php }
		} ?>
			<?php echo JHTML::_('sliders.end'); ?>

		<fieldset id="user-groups">
			<legend><?php echo JText::_('Users_Assigned_Groups'); ?></legend>
				<?php if ($this->grouplist) :
					echo $this->loadTemplate('groups');
				endif; ?>
		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
