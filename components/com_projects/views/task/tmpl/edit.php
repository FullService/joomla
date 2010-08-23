<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

// HTML Helpers
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

// Vars
$params = $this->params;
?>
<div class="project-item <?php echo $params->get('pageclass_sfx'); ?>">
    <div class="edit item-page">
        <form action="<?php echo ProjectsHelper::getLink('task'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
            <?php if (false): ?>
             <fieldset>
                    <legend><?php echo JText::_('COM_PROJECTS_FIELD_TYPE_LABEL'); ?></legend>
                    <div class="formelm">
                    <?php echo $this->form->getInput('type', null, $this->params->get('type', 3)); ?>
                </div>
            </fieldset>
            <?php endif; ?>

			<fieldset>
            	<legend><?php echo JText::_('JGLOBAL_DESCRIPTION'); ?></legend>

				<?php if ($this->type == 'task') : ?>
                <div class="formelm">
                    <?php echo $this->form->getLabel('parent_id'); ?>
                    <?php echo $this->form->getInput('parent_id', null, $this->params->get('parent_id')); ?>
                </div>
				<?php endif; ?>
				
                <div class="formelm">
                    <?php echo $this->form->getLabel('title'); ?>
                    <?php echo $this->form->getInput('title'); ?>
                    <?php echo $this->form->getInput('catid', null, $this->params->get('catid')); ?>
                </div>

                <div class="formelm">
                    <?php echo $this->form->getInput('description'); ?>
                </div>
            </fieldset>

         	<fieldset>
             	<legend><?php echo JText::_('JDETAILS'); ?></legend>
				
			<?php if ($this->type == 'task'): ?>	
                <div class="formelm">
                    <?php echo $this->form->getLabel('priority'); ?>
                    <?php echo $this->form->getInput('priority'); ?>
                </div>				
           		
           		<div class="formelm">
                    <?php echo $this->form->getLabel('state'); ?>
                    <?php echo $this->form->getInput('state'); ?>
                </div>

                <div class="formelm">
                    <?php echo $this->form->getLabel('estimate'); ?>
                    <?php echo $this->form->getInput('estimate'); ?>
               	</div>

               	<div class="formelm">
                    <?php echo $this->form->getLabel('start_at'); ?>
                    <?php echo $this->form->getInput('start_at'); ?>
               	</div>
               	<div class="formelm">
                    <?php echo $this->form->getLabel('finish_at'); ?>
                    <?php echo $this->form->getInput('finish_at'); ?>
               	</div>
            <?php else: ?>
                <div class="formelm">
                    <?php echo $this->form->getLabel('priority'); ?>
                    <?php echo $this->form->getInput('priority'); ?>
                </div>
			<?php endif; ?>
            </fieldset>
     
           	<input type="hidden" name="task" value="task.save" />
            <?php echo JHTML::_('form.token'); ?>
        </form>
    </div>
</div>