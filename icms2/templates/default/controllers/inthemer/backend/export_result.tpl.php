<?php
    $this->addBreadcrumb(LANG_INTHEMER_BACKEND_TAB_EXPORT, $this->href_to('export'));
    $this->addBreadcrumb(LANG_INTHEMER_EXPORT_RESULTS);
	$this->setPageTitle(LANG_INTHEMER_EXPORT_RESULTS);
?>

<div id="inthemer-export-results">

	<h2><?php echo LANG_INTHEMER_EXPORT_RESULTS; ?></h2>

	<div class="file">
		<div class="title"><?php echo LANG_INTHEMER_EXPORT_RESULT_FILE; ?></div>
		<div class="link">
			<a href="<?php echo $file_url; ?>"><?php echo basename($file_url); ?></a>
		</div>
		<div class="clear">
			<a href="<?php echo $this->href_to('export_clear'); ?>"><?php echo LANG_INTHEMER_EXPORT_RESULT_CLEAR; ?></a>
		</div>
	</div>

	<div class="manual">
		<div class="title"><?php echo LANG_INTHEMER_EXPORT_RESULT_MANUAL; ?></div>
		<ol>
			<?php if ($target == 'inthemer') { ?>
				<li><?php echo LANG_INTHEMER_EXPORT_MANUAL_INTHEMER_1; ?>;</li>
				<li><?php echo LANG_INTHEMER_EXPORT_MANUAL_INTHEMER_2; ?>;</li>
				<li><?php echo LANG_INTHEMER_EXPORT_MANUAL_INTHEMER_3; ?>;</li>
				<li><?php echo LANG_INTHEMER_EXPORT_MANUAL_INTHEMER_4; ?>.</li>
			<?php } ?>
			<?php if ($target == 'inplayer') { ?>
				<li><?php echo LANG_INTHEMER_EXPORT_MANUAL_INPLAYER_1; ?>;</li>
				<li><?php echo LANG_INTHEMER_EXPORT_MANUAL_INPLAYER_2; ?>;</li>
				<li><?php echo LANG_INTHEMER_EXPORT_MANUAL_INPLAYER_3; ?>;</li>
				<li><?php echo LANG_INTHEMER_EXPORT_MANUAL_INPLAYER_4; ?>;</li>
				<li><?php echo sprintf(LANG_INTHEMER_EXPORT_MANUAL_INPLAYER_5, $theme); ?>;</li>
				<li><?php echo LANG_INTHEMER_EXPORT_MANUAL_INPLAYER_6; ?></li>
			<?php } ?>
		</ol>
	</div>

</div>
