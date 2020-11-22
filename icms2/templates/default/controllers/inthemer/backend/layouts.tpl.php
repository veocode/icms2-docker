<?php

    $this->addBreadcrumb(LANG_INTHEMER_LAYOUTS);

    $this->addToolButton(array(
        'class' => 'add',
        'title' => LANG_INTHEMER_ADD_LAYOUT,
        'href'  => $this->href_to('add_layout')
    ));

    $this->addToolButton(array(
        'class' => 'refresh',
        'title' => LANG_INTHEMER_UPDATE_CSS_CACHE,
        'href'  => $this->href_to('update_cache')
    ));

?>

<?php $this->renderGrid($this->href_to('layouts_ajax'), $grid); ?>

<div class="buttons">
    <?php echo html_button(LANG_SAVE_ORDER, 'save_button', "icms.datagrid.submit('{$this->href_to('layouts_reorder')}')"); ?>
</div>
