<?php

/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="login<?php echo $this->params->get('pageclass_sfx')?>">
<?php if ($this->params->get('logindescription_show')==1 || $this->params->get('login_image')!='') : ?>
<div class="login-description">
<?php endif ; ?>

<?php if($this->params->get('logindescription_show')==1) : ?>
<?php echo $this->params->get('login_description'); ?>
<?php endif; ?>
<?php if (($this->params->get('login_image')!='')) :?>
<img src="<?php echo $this->params->get('login_image'); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
<?php endif; ?>

<?php if ($this->params->get('logindescription_show')==1 || $this->params->get('login_image')!='') : ?>
</div>
<?php endif ; ?>
<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post">

	<fieldset>
		<?php foreach ($this->form->getFieldset('credentials') as $field): ?>
			<?php if (!$field->hidden): ?>
				<div class="login-fields"><?php echo $field->label; ?>
				<?php echo $field->input; ?></div>
			<?php endif; ?>
		<?php endforeach; ?>
	</fieldset>

	<button type="submit" class="button">Submit</button>

	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<?php echo JHtml::_('form.token'); ?>
</form>

</div>