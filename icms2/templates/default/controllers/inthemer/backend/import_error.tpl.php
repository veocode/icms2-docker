<?php
    $this->addBreadcrumb(LANG_INTHEMER_IMPORT);
	$this->setPageTitle(LANG_INTHEMER_IMPORT);
?>

<div id="inthemer-export-results">

	<h2><?php echo LANG_INTHEMER_IMPORT; ?></h2>

	<div class="none">
		<?php echo $error; ?>
		<div class="links">
			<a href="<?php echo $this->href_to('layouts'); ?>"><?php echo LANG_INTHEMER_EXPORT_RESULT_BACK_TO_LIST; ?></a>
		</div>
	</div>

</div>
