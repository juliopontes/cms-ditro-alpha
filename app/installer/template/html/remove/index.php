<form action="index.php" method="post" id="adminForm" class="form-validate form-horizontal">
	<div class="alert alert-error inlineError" id="theDefaultError" style="display: none">
		<h4 class="alert-heading"><?php echo JText::_('JERROR'); ?></h4>
		<p id="theDefaultErrorMessage"></p>
	</div>
	<div class="alert alert-success">
	<h3><?php echo JText::_('INSTL_COMPLETE_TITLE'); ?></h3>
	</div>
	<div class="alert">
		<p><?php echo JText::_('INSTL_COMPLETE_REMOVE_INSTALLATION'); ?></p>
		<input type="button" class="btn btn-warning" name="instDefault" onclick="Install.removeFolder(this);" value="<?php echo JText::_('INSTL_COMPLETE_REMOVE_FOLDER'); ?>" />
	</div>

	<div class="btn-toolbar">
		<div class="btn-group">
			<a class="btn" href="<?php echo BASE_URL; ?>" title="<?php echo JText::_('JSITE'); ?>"><i class="icon-eye-open"></i> <?php echo JText::_('JSITE'); ?></a>
		</div>
		<div class="btn-group">
			<a class="btn btn-primary" href="<?php echo BASE_URL; ?>administrator/" title="<?php echo JText::_('JADMINISTRATOR'); ?>"><i class="icon-lock icon-white"></i> <?php echo JText::_('JADMINISTRATOR'); ?></a>
		</div>
	</div>

	<input type="hidden" name="<?php echo $this->application->getFormToken(); ?>" value="1" />
</form>