<?php
	$cfg = cmsConfig::getInstance();
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo $cfg->root; ?>templates/inthemer/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $cfg->root; ?>templates/inthemer/builder/css/builder.css">
	<link id="it-layout-style" rel="stylesheet" href="<?php echo $cfg->root; ?>inthemer/css/<?php echo $layout['id']; ?>?short=yes">
	<script type="text/javascript" src="<?php echo $cfg->root; ?>templates/default/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo $cfg->root; ?>templates/default/js/jquery-modal.js"></script>
	<script type="text/javascript" src="<?php echo $cfg->root; ?>templates/default/js/modal.js"></script>
	<script type="text/javascript" src="<?php echo $cfg->root; ?>templates/default/js/messages.js"></script>
</head>
<body>
	<div class="it-layout">

		<div id="inthemer-page"></div>

		<div id="it-global-section-controls">
			<a href="#" class="it-block-add-btn it-tooltip" title="<?php echo LANG_INTHEMER_ADD_SECTION; ?>">
				<i class="fa fa-plus-square"></i>
			</a>
		</div>

	</div>
    <style id="inthemer-styles"></style>
</body>
</html>
