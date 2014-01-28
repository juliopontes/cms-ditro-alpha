<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->get('default.language'); ?>" lang="<?php echo $this->get('default.language'); ?>" dir="<?php echo $this->getLanguage()->isRTL() ? 'rtl' : '' ; ?>">
	<head>
		<link href="../../app/installer/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
		<link rel="stylesheet" href="../media/bootstrap/v3/css/bootstrap.min.css" type="text/css" />
		<link rel="stylesheet" href="../media/bootstrap/v3/css/bootstrap-responsive.min.css" type="text/css" />
		<link rel="stylesheet" href="../media/bootstrap/v3/css/bootstrap-extended.css" type="text/css" />
		<link rel="stylesheet" href="../../app/installer/template/css/template.css" type="text/css" />
		<link rel="stylesheet" href="../media/joomla/jui/css/chosen.css" type="text/css" />
		<link rel="stylesheet" href="../media/joomla/jui/css/spinner.css" type="text/css" />
		<script src="../media/jquery/core/js/jquery.min.js" type="text/javascript"></script>
		<script src="../media/jquery/core/js/jquery-migrate.min.js" type="text/javascript"></script>
		<script src="../media/joomla/jui/js/spin.min.js" type="text/javascript"></script>
		<script src="../media/bootstrap/v3/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="../media/joomla/jui/js/chosen.jquery.min.js" type="text/javascript"></script>
		<script src="../media/joomla/jui/js/joomla.min.js" type="text/javascript"></script>
		<script src="../media/joomla/jui/js/validator.min.js" type="text/javascript"></script>
		<script src="../../app/installer/template/js/installation.js" type="text/javascript"></script>
		<!--[if lt IE 9]>
			<script src="../media/joomla/jui/js/html5.js"></script>
		<![endif]-->
		<script type="text/javascript">
			jQuery(function()
			{	// Delay instantiation after document.formvalidation and other dependencies loaded
				window.setTimeout(function(){
					window.Install = new Installation('container-installation', '<?php echo BASE_URL; ?>');
			   	}, 500);
			});
		</script>
	</head>
	<body>
		<!-- Header -->
		<div class="header">
			<img src="../../app/installer/template/images/joomla.png" alt="Joomla" />
			<hr />
			<h5>
				<?php
				$joomla = '<a href="http://www.joomla.org" target="_blank">Joomla!</a><sup>&#174;</sup>';
				$license = '<a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" target="_blank">' . JText::_('INSTL_GNU_GPL_LICENSE') . '</a>';
				echo JText::sprintf('JGLOBAL_ISFREESOFTWARE', $joomla, $license);
				?>
			</h5>
		</div>
		<!-- Container -->
		<div class="container">
			<div id="system-message-container">
				<div id="system-message">
				</div>
			</div>
			<div id="container-installation">
				{component}
			</div>
			<hr />
		</div>
		<script>
			function initElements()
			{
				(function($){
					$('.hasTooltip').tooltip()

					// Chosen select boxes
					$("select").chosen({
						disable_search_threshold : 10,
						allow_single_deselect : true
					});

					// Turn radios into btn-group
				    $('.radio.btn-group label').addClass('btn');
				    $(".btn-group label:not(.active)").click(function()
					{
				        var label = $(this);
				        var input = $('#' + label.attr('for'));

				        if (!input.prop('checked'))
						{
				            label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
				            if(input.val()== '')
							{
				                    label.addClass('active btn-primary');
				             } else if(input.val()==0 || input.val()=='remove')
							{
				                    label.addClass('active btn-danger');
				             } else {
				            label.addClass('active btn-success');
				             }
				            input.prop('checked', true);
				        }
				    });
				    $(".btn-group input[checked=checked]").each(function()
					{
						if ($(this).val()== '')
						{
				           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
				        } else if($(this).val()==0 || $(this).val()=='remove')
						{
				           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
				        } else {
				            $("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
				        }
				    });
				})(jQuery);
			}
			initElements();
		</script>
	</body>
</html>