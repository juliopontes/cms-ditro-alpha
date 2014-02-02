<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->get('default.language'); ?>" lang="<?php echo $this->get('default.language'); ?>" dir="<?php echo $this->getLanguage()->isRTL() ? 'rtl' : '' ; ?>">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo $this->get('uri.base.full'); ?>template/lite/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
		<title>Lite Template for Joomla! CMS</title>
		<link rel="stylesheet" href="<?php echo $this->get('uri.base.full'); ?>media/bootstrap/v3/css/bootstrap.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $this->get('uri.base.full'); ?>media/bootstrap/v3/css/bootstrap-responsive.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $this->get('uri.base.full'); ?>media/bootstrap/v3/css/bootstrap-extended.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $this->get('uri.base.full'); ?>templates/lite/css/style.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $this->get('uri.base.full'); ?>media/joomla/jui/css/chosen.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo $this->get('uri.base.full'); ?>media/joomla/jui/css/spinner.css" type="text/css" />
		<script src="<?php echo $this->get('uri.base.full'); ?>media/jquery/core/js/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo $this->get('uri.base.full'); ?>media/jquery/core/js/jquery-migrate.min.js" type="text/javascript"></script>
		<script src="<?php echo $this->get('uri.base.full'); ?>media/joomla/jui/js/spin.min.js" type="text/javascript"></script>
		<script src="<?php echo $this->get('uri.base.full'); ?>media/bootstrap/v3/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="<?php echo $this->get('uri.base.full'); ?>media/joomla/jui/js/chosen.jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo $this->get('uri.base.full'); ?>media/joomla/jui/js/joomla.min.js" type="text/javascript"></script>
		<script src="<?php echo $this->get('uri.base.full'); ?>media/joomla/jui/js/validator.min.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	      <div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand" href="#">Project name</a>
	        </div>
	        <div class="collapse navbar-collapse">
	          <ul class="nav navbar-nav">
	            <li class="active"><a href="#">Home</a></li>
	            <li><a href="#about">About</a></li>
	            <li><a href="#contact">Contact</a></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </div>
		<!-- Container -->
		<div class="container">
			{component}
		</div>
	</body>
</html>