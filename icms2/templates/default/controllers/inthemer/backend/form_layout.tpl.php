<?php

    $this->addBreadcrumb(LANG_INTHEMER_LAYOUTS, $this->href_to('layouts'));

    if ($do == 'add') {
        $page_title = LANG_INTHEMER_ADD_LAYOUT;
    }

    if ($do == 'edit') {
        $page_title = LANG_INTHEMER_EDIT_LAYOUT;
    }

    if ($do == 'copy') {
        $page_title = LANG_INTHEMER_COPY_LAYOUT;
    }

    $this->setPageTitle($page_title);
    $this->addBreadcrumb($page_title);

    $this->renderForm($form, $layout, array(
            'action' => '',
            'method' => 'post',
            'toolbar' => false
    ), $errors);

?>
<script>
	$(document).ready(function(){
		$('select#scope').change(function(){
			$('#fset_masks').toggle($(this).val() == 0);
		}).change();
	});
</script>