<?php
    $this->setPageTitle(LANG_INTHEMER_BACKEND_TAB_EXPORT);
    $this->addBreadcrumb(LANG_INTHEMER_BACKEND_TAB_EXPORT);
?>

<div id="inthemer-export-form">
	<?php
		$this->renderForm($form, $opts, array(
				'action' => '',
				'method' => 'post',
				'toolbar' => false
		), $errors);
	?>
	<div class="loader_wrap">
		<div class="loader"></div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('select#target').change(function(){
			$('#fset_theme').toggle($(this).val() == 'inplayer');
			$('#fset_theme input').prop('required', false);
		}).change();
		$('#inthemer-export-form form').submit(function(){
			$('#inthemer-export-form .loader_wrap').show();
		});
	});
</script>