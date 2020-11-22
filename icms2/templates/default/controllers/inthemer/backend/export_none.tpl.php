<?php

    $this->addBreadcrumb(LANG_INTHEMER_BACKEND_TAB_EXPORT, $this->href_to('export'));
    $this->addBreadcrumb(LANG_INTHEMER_EXPORT_RESULTS);

?>

<div id="inthemer-export-results">

	<h2><?php echo LANG_INTHEMER_EXPORT_RESULTS; ?></h2>

	<div class="none">
		<?php echo LANG_INTHEMER_EXPORT_RESULT_NONE; ?>
		<div class="links">
			<a href="<?php echo $this->href_to('layouts'); ?>"><?php echo LANG_INTHEMER_EXPORT_RESULT_BACK_TO_LIST; ?></a>
			<a href="<?php echo $this->href_to('export'); ?>"><?php echo LANG_INTHEMER_EXPORT_RESULT_NEW_EXPORT; ?></a>
		</div>
	</div>

</div>
