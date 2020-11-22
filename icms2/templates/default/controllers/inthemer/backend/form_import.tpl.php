<?php
    $this->setPageTitle(LANG_INTHEMER_IMPORT);
    $this->addBreadcrumb(LANG_INTHEMER_IMPORT);
?>

<div id="inthemer-export-form">
	<?php
		$this->renderForm($form, $opts, array(
			'action' => '',
			'method' => 'post',
			'toolbar' => false,
			'submit' => array(
				'title' => LANG_INTHEMER_IMPORT_SUBMIT
			)
		), $errors);
	?>
	<div class="loader_wrap">
		<div class="loader"></div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#inthemer-export-form form').submit(function(){
			$('#inthemer-export-form .loader_wrap').show();
		});
	});
</script>