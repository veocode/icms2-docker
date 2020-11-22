<?php

    $this->addBreadcrumb(LANG_INTHEMER_LAYOUT_REVISIONS);

?>

<div id="layout-revisions-list">

	<h3><?php printf(LANG_INTHEMER_LAYOUT_REVISIONS_TITLE, $layout['title']); ?>:</h3>
	<div class="hint"><?php echo LANG_INTHEMER_LAYOUT_REVISIONS_HINT; ?></div>

	<?php $this->renderGrid($this->href_to('layout_revisions_ajax', $layout['id']), $grid); ?>

</div>
