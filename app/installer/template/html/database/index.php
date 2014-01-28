<ul class="nav nav-tabs"><li id="configuration" class="step"><a onclick="return Install.goToPage('configuration')" href="#"><span class="badge">1</span> Configuration</a></li><li id="database" class="step active"><a onclick="return Install.goToPage('database')" href="#"><span class="badge">2</span> Database</a></li><li id="summary" class="step"><a onclick="Install.submitform();" href="#"><span class="badge">3</span> Overview</a></li></ul>
<form action="index.php" method="post" id="adminForm" class="form-validate form-horizontal">
	<div class="btn-toolbar">
		<div class="btn-group pull-right">
			<a class="btn" href="#" onclick="return Install.goToPage('configuration');" rel="prev" title="<?php echo JText::_('JPrevious'); ?>"><i class="icon-arrow-left"></i> <?php echo JText::_('JPrevious'); ?></a>
			<a  class="btn btn-primary" href="#" onclick="Install.submitform();" rel="next" title="<?php echo JText::_('JNext'); ?>"><i class="icon-arrow-right icon-white"></i> <?php echo JText::_('JNext'); ?></a>
		</div>
	</div>
	<h3><?php echo JText::_('INSTL_DATABASE'); ?></h3>
	<hr class="hr-condensed" />
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('db_type'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('db_type'); ?>
			<p class="help-block">
				<?php echo JText::_('INSTL_DATABASE_TYPE_DESC'); ?>
			</p>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('db_host'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('db_host'); ?>
			<p class="help-block">
				<?php echo JText::_('INSTL_DATABASE_HOST_DESC'); ?>
			</p>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('db_user'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('db_user'); ?>
			<p class="help-block">
				<?php echo JText::_('INSTL_DATABASE_USER_DESC'); ?>
			</p>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('db_pass'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('db_pass'); ?>
			<p class="help-block">
				<?php echo JText::_('INSTL_DATABASE_PASSWORD_DESC'); ?>
			</p>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('db_name'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('db_name'); ?>
			<p class="help-block">
				<?php echo JText::_('INSTL_DATABASE_NAME_DESC'); ?>
			</p>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('db_prefix'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('db_prefix'); ?>
			<p class="help-block">
				<?php echo JText::_('INSTL_DATABASE_PREFIX_DESC'); ?>
			</p>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo $this->form->getLabel('db_old'); ?>
		</div>
		<div class="controls">
			<?php echo $this->form->getInput('db_old'); ?>
			<p class="help-block">
				<?php echo JText::_('INSTL_DATABASE_OLD_PROCESS_DESC'); ?>
			</p>
		</div>
	</div>
	<input type="hidden" name="<?php echo $this->application->getFormToken(); ?>" value="1" />
	<input type="hidden" name="view" value="database">
	<input type="hidden" name="task" value="setup.database" />
</form>
