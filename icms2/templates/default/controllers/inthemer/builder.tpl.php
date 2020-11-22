<?php
$cfg = cmsConfig::getInstance();
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title><?php echo $layout['title']; ?> - <?php echo LANG_INTHEMER_CONTROLLER; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo $cfg->root; ?>templates/inthemer/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo $cfg->root; ?>templates/inthemer/builder/css/vendor/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="<?php echo $cfg->root; ?>templates/inthemer/builder/css/vendor/jquery/jquery-ui.css">
		<link rel="stylesheet" href="<?php echo $cfg->root; ?>templates/inthemer/builder/css/vendor/code/codemirror.css">
		<link rel="stylesheet" href="<?php echo $cfg->root; ?>templates/inthemer/builder/css/base.css">
		<link rel="stylesheet" href="<?php echo $cfg->root; ?>templates/inthemer/builder/css/builder.css">
		<link rel="stylesheet" href="<?php echo $cfg->root; ?>wysiwyg/redactor/files/redactor.css">
	</head>
	<body>

		<div id="inthemer">

			<div class="it-top-bar">
				<ul class="flat">
					<li>
						<div class="it-layouts-dropdown dropdown">
							<button class="btn btn-success dropdown-toggle" type="button"><i class="fa fa-window-maximize"></i> <i class="fa fa-caret-down"></i></button>
							<ul class="dropdown-menu">
								<?php $is_sep = false; ?>
								<?php if ($layouts) { ?>
									<?php foreach ($layouts as $next_layout) { ?>
										<?php if ($next_layout['id'] != $layout['id']) {
											$is_sep = true; ?>
											<li>
												<a href="<?php echo $this->href_to('builder', $next_layout['id']); ?>">
													<i class="fa fa-fw fa-window-maximize"></i>
													<?php echo $next_layout['title']; ?>
												</a>
											</li>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								<?php if ($is_sep) { ?>
									<li class="divider"></li>
								<?php } ?>
								<li>
									<a href="<?php echo href_to('admin/controllers/edit/inthemer/add_layout'); ?>"><i class="fa fa-fw fa-plus"></i> <?php echo LANG_INTHEMER_CREATE_LAYOUT; ?></a>
									<a href="<?php echo href_to('admin/controllers/edit/inthemer/copy_layout', $layout['id']); ?>"><i class="fa fa-fw fa-copy"></i> <?php echo LANG_INTHEMER_COPY_CURRENT_LAYOUT; ?></a>
								</li>
							</ul>
						</div>
						<span class="it-layout-title">
							<?php echo $layout['title']; ?>
						</span>
						<div class="it-layout-revision dropdown">
							<button class="btn btn-link dropdown-toggle" type="button">
								<span class="title">
									<?php if ($rev_no == $layout['rev_no']) { ?>
										<?php printf(LANG_INTHEMER_REV_CURRENT, $rev_no); ?>
									<?php } else { ?>
										<?php printf(LANG_INTHEMER_REV_LABEL, $rev_no, html_date($layout['date_pub'])); ?>
									<?php } ?>
								</span>
								<i class="fa fa-caret-down"></i>
							</button>
							<ul class="dropdown-menu">
								<?php if ($revisions) { $last_rev_no = 0; ?>
									<?php if ($rev_no != $layout['rev_no']) { ?>
										<li class="rev-item">
											<a href="<?php echo href_to('inthemer/builder', $layout['id']); ?>">
												<?php printf(LANG_INTHEMER_REV_CURRENT, $layout['rev_no']); ?>
											</a>
										</li>
										<li class="divider rev-item"></li>
									<?php } ?>
									<?php foreach($revisions as $rev){ ?>
										<li class="rev-item">
											<a href="<?php echo href_to('inthemer/builder', $layout['id'], $rev['no']); ?>">
												<?php printf(LANG_INTHEMER_REV_LABEL, $rev['no'], html_date_time($rev['date_pub'])); ?>
											</a>
										</li>
										<?php $last_rev_no = $rev['no']; ?>
									<?php } ?>
								<?php } ?>
								<li>
									<a href="<?php echo href_to('admin/controllers/edit/inthemer/layout_revisions', $layout['id']); ?>">
										<?php echo LANG_INTHEMER_REV_VIEW_ALL; ?>
									</a>
								</li>
							</ul>
						</div>
					</li>
					<li class="pull-right">
						<div style="float:left; margin-right:20px">
							<button id="btn-treeview" class="btn btn-success"><i class="fa fa-tasks"></i> <?php echo LANG_INTHEMER_STRUCTURE_BUTTON; ?></button>
							<button id="btn-layout-options" class="btn btn-success" title=""><i class="fa fa-sliders"></i> <?php echo LANG_INTHEMER_LAYOUT_OPTIONS_BUTTON; ?></button>
							<div class="dropdown">
								<button id="btn-layout-code" class="btn btn-success dropdown-toggle" title=""><i class="fa fa-code"></i> <?php echo LANG_INTHEMER_LAYOUT_CODE; ?></button>
								<ul class="dropdown-menu">
									<li>
										<a href="#" class="btn-code-edit" data-code="head"><?php html(LANG_INTHEMER_LAYOUT_CODE_HEAD); ?></a>
										<a href="#" class="btn-code-edit" data-code="body"><?php html(LANG_INTHEMER_LAYOUT_CODE_BODY); ?></a>
									</li>
								</ul>
							</div>
							<button id="btn-layout-data-sources" class="btn btn-info" title=""><i class="fa fa-database"></i> <?php echo LANG_INTHEMER_DATA_SOURCES; ?></button>
							<button id="btn-wipe-layout" class="btn btn-primary" title="<?php echo LANG_INTHEMER_WIPE_LAYOUT; ?>"><i class="fa fa-eraser"></i></button>
						</div>
						<div class="btn-group" role="group">
							<button id="btn-save-layout" class="btn btn-success" type="submit"><i class="fa fa-check"></i> <?php echo LANG_SAVE; ?></button>
							<a id="btn-preview" class="btn btn-primary" href="<?php echo href_to_home(); ?>" target="_blank"><i class="fa fa-eye"></i></a>
							<a id="btn-close" class="btn btn-primary" href="<?php echo href_to('admin/controllers/edit/inthemer/layouts'); ?>"><i class="fa fa-times"></i></a>
						</div>
					</li>
				</ul>
			</div>

			<div class="it-width-bar"></div>

			<div class="it-frame-wrap">
				<iframe id="inthemer-layout-frame" data-src="<?php echo $this->href_to('layout', $layout['id'], [$rev_no?$rev_no:'']); ?>" frameborder="0"></iframe>
			</div>

			<div class="loading-wrap">
				<div class="loading">
					<i class="fa fa-gear fa-spin"></i>
					<div class="loading-text"></div>
				</div>
			</div>

		</div>

		<div id="inthemer-images-browser" class="modal-window" title="<?php echo LANG_INTHEMER_IMAGE_SELECT; ?>">
			<div class="toolbar">
				<div id="pagination"></div>
			</div>
			<div class="images-view">
				<ul class="images-list"></ul>
				<div class="no-images"><?php echo LANG_INTHEMER_IMAGES_NONE; ?></div>
				<div id="image-loading" class="loading"><i class="fa fa-gear fa-spin"></i></div>
			</div>
			<input id="fileupload" type="file" name="file" data-url="<?php echo href_to('inthemer', 'upload'); ?>">
		</div>

		<div id="inthemer-style-editor">

			<div class="states tabbed-view small">
				<ul class="tabs">
					<li class="active" data-target="base"><?php echo LANG_INTHEMER_CSS_STATE_BASE; ?></li>
					<li data-target="hover"><?php echo LANG_INTHEMER_CSS_STATE_HOVER; ?></li>
					<li data-target="active"><?php echo LANG_INTHEMER_CSS_STATE_ACTIVE; ?></li>
				</ul>
				<select class="width-list"></select>
			</div>

			<div id="properties" class="tabbed-view">
				<ul class="tabs">
					<li data-target="dimensions"><i class="fa fa-arrows"></i></li>
					<li data-target="text" class="active" title="<?php echo LANG_INTHEMER_CSS_GROUP_TEXT; ?>"><i class="fa fa-font"></i></li>
					<li data-target="bg" title="<?php echo LANG_INTHEMER_CSS_GROUP_BACKGROUND; ?>"><i class="fa fa-picture-o"></i></li>
					<li data-target="size" title="<?php echo LANG_INTHEMER_CSS_POSITION; ?>"><i class="fa fa-object-ungroup"></i></li>
					<li data-target="border" title="<?php echo LANG_INTHEMER_CSS_GROUP_BORDER; ?>"><i class="fa fa-bars"></i></li>
					<li data-target="display" title="<?php echo LANG_INTHEMER_CSS_GROUP_DISPLAY; ?>"><i class="fa fa-paint-brush"></i></li>
					<li data-target="css" title="<?php echo LANG_INTHEMER_SELECTOR_CUSTOM_CSS; ?>"><i class="fa fa-code"></i></li>
					<li class="tab-used" data-target="used" title="<?php echo LANG_INTHEMER_CSS_SHOW_USED; ?>"><i class="fa fa-pencil"></i></li>
				</ul>
				<ul class="fields">

					<li class="tab-dimensions tab">

						<div class="field fixed">
							<label><?php echo LANG_INTHEMER_CSS_GROUP_SIZE; ?></label>
							<div id="sizes-box">
								<div class="col-title width"><?php echo LANG_INTHEMER_CSS_WIDTH; ?></div>
								<div class="col-title height"><?php echo LANG_INTHEMER_CSS_HEIGHT; ?></div>
								<div class="row-title default"><?php echo LANG_INTHEMER_CSS_BASE; ?></div>
								<div class="row-title min"><?php echo LANG_INTHEMER_CSS_MIN; ?></div>
								<div class="row-title max"><?php echo LANG_INTHEMER_CSS_MAX; ?></div>
							</div>
						</div>

						<div class="field fixed">
							<div id="paddings-box">
								<div class="border-box"></div>
							</div>
						</div>

					</li>

					<li class="tab-css tab field fixed" id="custom-css-field">
						<label><?php echo LANG_INTHEMER_SELECTOR_CUSTOM_CSS; ?></label>
						<div id="css-editor-wrap" class="value">
							<textarea class="input" id="css-editor"></textarea>
						</div>
					</li>

				</ul>
			</div

		</div>

		<?php echo $this->controller->getBuilderScripts(); ?>

		<script src="<?php echo $cfg->root; ?>templates/default/js/jquery-modal.js"></script>
		<script src="<?php echo $cfg->root; ?>templates/default/js/modal.js"></script>
		<script src="<?php echo $cfg->root; ?>templates/default/js/core.js"></script>
		<script src="<?php echo $cfg->root; ?>templates/default/js/files.js"></script>

		<script src="<?php echo $cfg->root; ?>templates/inthemer/builder/js/vendor/color/colors.js"></script>
		<script src="<?php echo $cfg->root; ?>templates/inthemer/builder/js/vendor/color/picker.js"></script>

		<script src="<?php echo $cfg->root; ?>wysiwyg/redactor/files/redactor.js"></script>
		<script src="<?php echo $cfg->root; ?>wysiwyg/redactor/files/lang/ru.js"></script>
		<script src="<?php echo $cfg->root; ?>wysiwyg/redactor/files/plugins/fontfamily/fontfamily.js"></script>
		<script src="<?php echo $cfg->root; ?>wysiwyg/redactor/files/plugins/fontsize/fontsize.js"></script>
		<script src="<?php echo $cfg->root; ?>wysiwyg/redactor/files/plugins/fontcolor/fontcolor.js"></script>

		<script>

			var lang = {
				yes: '<?php echo LANG_YES; ?>',
				no: '<?php echo LANG_NO; ?>',
				never: '<?php echo LANG_INTHEMER_OPTION_NEVER; ?>',
				save: '<?php echo LANG_SAVE; ?>',
				done: '<?php echo LANG_INTHEMER_DONE; ?>',
				cancel: '<?php echo LANG_CANCEL; ?>',
				close: '<?php echo LANG_CLOSE; ?>',
				select: '<?php echo LANG_SELECT; ?>',
				upload: '<?php echo LANG_INTHEMER_IMAGE_UPLOAD; ?>',
				delete_image: '<?php echo LANG_INTHEMER_IMAGE_DELETE_CONFIRM; ?>',
				delete: '<?php echo LANG_DELETE; ?>',
				duplicate: '<?php echo LANG_INTHEMER_BLOCK_DUPLICATE; ?>',
				copy: '<?php echo LANG_INTHEMER_BLOCK_COPY; ?>',
				paste: '<?php echo LANG_INTHEMER_BLOCK_PASTE; ?>',
				options: '<?php echo LANG_OPTIONS; ?>',
				link: '<?php echo LANG_INTHEMER_LINK; ?>',
				link_url: '<?php echo LANG_INTHEMER_LINK_URL; ?>',
				edit_styles: '<?php echo LANG_INTHEMER_BLOCK_EDIT_STYLES; ?>',
				block_options: '<?php echo LANG_INTHEMER_BLOCK_OPTIONS; ?>',
				block: '<?php echo LANG_INTHEMER_BLOCK; ?>',
				block_name: '<?php echo LANG_INTHEMER_BLOCK_NAME; ?>',
				block_class: '<?php echo LANG_INTHEMER_BLOCK_CLASS; ?>',
				add_block: '<?php echo LANG_INTHEMER_ADD_BLOCK; ?>',
				add_field: '<?php echo LANG_INTHEMER_ADD_FIELD; ?>',
				add_section: '<?php echo LANG_INTHEMER_ADD_SECTION; ?>',
				add_row: '<?php echo LANG_INTHEMER_ADD_ROW; ?>',
				wipe_confirm: '<?php echo LANG_INTHEMER_WIPE_LAYOUT_CONFIRM; ?>',
				layout_options: '<?php echo LANG_INTHEMER_LAYOUT_OPTIONS; ?>',
				bg_image: '<?php echo LANG_INTHEMER_BG_IMAGE; ?>',
				bg_gradient: '<?php echo LANG_INTHEMER_BG_GRADIENT; ?>',
				confirm_title: '<?php echo LANG_INTHEMER_CONFIRM_TITLE; ?>',
				all: '<?php echo LANG_ALL; ?>',
				treeview: '<?php echo LANG_INTHEMER_STRUCTURE; ?>',
				html_no_preview: '<?php echo LANG_INTHEMER_HTML_NO_PREVIEW; ?>',
				rev_revert_confirm: '<?php echo LANG_INTHEMER_REV_REVERT_CONFIRM; ?>',
				saving_globals: '<?php echo LANG_INTHEMER_SAVING_GLOBALS; ?>',
				saving_layout: '<?php echo LANG_INTHEMER_SAVING_LAYOUT; ?>',
				sizes: {
					title: '<?php echo LANG_INTHEMER_SIZES; ?>',
					row_width: '<?php echo LANG_INTHEMER_ROW_MAX_WIDTH_DEFAULT; ?>',
					section_width: '<?php echo LANG_INTHEMER_SECTION_MAX_WIDTH_DEFAULT; ?>',
				},
				access: {
					title: '<?php echo LANG_INTHEMER_ACCESS; ?>',
					show_to_groups: '<?php echo LANG_INTHEMER_ACCESS_SHOW_TO_GROUPS; ?>',
					hide_from_groups: '<?php echo LANG_INTHEMER_ACCESS_HIDE_FROM_GROUPS; ?>',
					show_on: '<?php echo LANG_INTHEMER_ACCESS_SHOW_ON_DEVICES; ?>',
					hide_on: '<?php echo LANG_INTHEMER_ACCESS_HIDE_ON_DEVICES; ?>',
				},
				library: {
					library: '<?php echo LANG_INTHEMER_LIBRARY; ?>',
					add: '<?php echo LANG_INTHEMER_LIBRARY_ADD_TO; ?>',
					add_confirm: '<?php echo LANG_INTHEMER_LIBRARY_ADD_CONFIRM; ?>',
					title: '<?php echo LANG_INTHEMER_LIBRARY_TITLE; ?>',
					is_global: '<?php echo LANG_INTHEMER_LIBRARY_IS_GLOBAL; ?>',
				},
				presets: {
					presets: '<?php echo LANG_INTHEMER_PRESETS; ?>',
					none: '<?php echo LANG_INTHEMER_PRESETS_NONE; ?>',
					add: '<?php echo LANG_INTHEMER_PRESET_ADD; ?>',
					add_confirm: '<?php echo LANG_INTHEMER_PRESET_ADD_CONFIRM; ?>',
					delete_confirm: '<?php echo LANG_INTHEMER_PRESET_DELETE_CONFIRM; ?>',
					title: '<?php echo LANG_INTHEMER_PRESET_TITLE; ?>',
				},
				invalid: {
					required: '<?php echo LANG_INTHEMER_INVALID_REQUIRED; ?>',
					number: '<?php echo LANG_INTHEMER_INVALID_NUMBER; ?>',
				},
				anim: {
					title: '<?php echo LANG_INTHEMER_ANIM; ?>',
					event: '<?php echo LANG_INTHEMER_ANIM_EVENT; ?>',
					event_load: '<?php echo LANG_INTHEMER_ANIM_EVENT_LOAD; ?>',
					event_view: '<?php echo LANG_INTHEMER_ANIM_EVENT_VIEW; ?>',
					class: '<?php echo LANG_INTHEMER_ANIM_CLASS; ?>',
					speed: '<?php echo LANG_INTHEMER_ANIM_SPEED; ?>',
					delay: '<?php echo LANG_INTHEMER_ANIM_DELAY; ?>',
				},
				data_sources: {
					source: '<?php echo LANG_INTHEMER_DATA_SOURCE; ?>',
					title: '<?php echo LANG_INTHEMER_DATA_SOURCES; ?>',
					source_title: '<?php echo LANG_INTHEMER_DATA_SOURCE_TITLE; ?>',
					none: '<?php echo LANG_INTHEMER_DATA_SOURCES_NONE; ?>',
					add: '<?php echo LANG_INTHEMER_DATA_SOURCES_ADD; ?>',
					delete: '<?php echo LANG_INTHEMER_DATA_SOURCES_DELETE; ?>',
					delete_confirm: '<?php echo LANG_INTHEMER_DATA_SOURCES_DELETE_CONFIRM; ?>',
					skip: '<?php echo LANG_INTHEMER_DATA_SOURCES_SKIP; ?>',
					limit: '<?php echo LANG_INTHEMER_DATA_SOURCES_LIMIT; ?>',
					content: {
						title: '<?php echo LANG_INTHEMER_CONTENT; ?>',
						ctype: '<?php echo LANG_CONTENT_TYPE; ?>',
						dataset: '<?php echo LANG_INTHEMER_CONTENT_DATASET; ?>',
						item: '<?php echo LANG_INTHEMER_CONTENT_ITEM; ?>',
						item_sample: '<?php echo LANG_INTHEMER_CONTENT_ITEM_SAMPLE; ?>',
						category: '<?php echo LANG_CATEGORY; ?>',
					},
				},
				font: {
					font: '<?php echo LANG_INTHEMER_FONT; ?>',
					family: '<?php echo LANG_INTHEMER_FONT_FAMILY; ?>',
					urls: '<?php echo LANG_INTHEMER_FONT_URLS; ?>'
				},
				colors: {
					colors: '<?php echo LANG_INTHEMER_COLORS; ?>',
					base: '<?php echo LANG_INTHEMER_COLORS_BASE; ?>',
					accent: '<?php echo LANG_INTHEMER_COLORS_ACCENT; ?>',
					ui: '<?php echo LANG_INTHEMER_COLORS_UI; ?>',
					text: '<?php echo LANG_INTHEMER_COLORS_TEXT; ?>',
					link: '<?php echo LANG_INTHEMER_COLORS_LINK; ?>',
					bg: '<?php echo LANG_INTHEMER_COLORS_BG; ?>',
				},
				code: {
					titles: {
						head: '<?php echo LANG_INTHEMER_LAYOUT_CODE_HEAD; ?>',
						body: '<?php echo LANG_INTHEMER_LAYOUT_CODE_BODY; ?>',
					},
					fields: {
						head: '<?php echo LANG_INTHEMER_LAYOUT_CODE_HEAD_TITLE; ?>',
						body: '<?php echo LANG_INTHEMER_LAYOUT_CODE_BODY_TITLE; ?>',
					}
				},
				content: {
					props: {
						block: '<?php echo LANG_INTHEMER_PROPS_BLOCK; ?>',
						table: '<?php echo LANG_INTHEMER_PROPS_TABLE; ?>',
						row: '<?php echo LANG_INTHEMER_PROPS_TABLE_ROW; ?>',
						cell: '<?php echo LANG_INTHEMER_PROPS_TABLE_CELL; ?>',
						heading: '<?php echo LANG_INTHEMER_PROPS_TABLE_HEAD_CELL; ?>',
						title: '<?php echo LANG_INTHEMER_PROPS_TABLE_TITLE_CELL; ?>',
						value: '<?php echo LANG_INTHEMER_PROPS_TABLE_VALUE_CELL; ?>',
					}
				},
				blocks: {
					section: '<?php echo LANG_INTHEMER_BLOCK_SECTION; ?>',
					row: '<?php echo LANG_INTHEMER_BLOCK_ROW; ?>',
					column: '<?php echo LANG_INTHEMER_BLOCK_COLUMN; ?>',
					image: '<?php echo LANG_INTHEMER_BLOCK_IMAGE; ?>',
					menu: '<?php echo LANG_INTHEMER_BLOCK_MENU; ?>',
					heading: '<?php echo LANG_INTHEMER_BLOCK_HEADING; ?>',
					text: '<?php echo LANG_INTHEMER_BLOCK_TEXT; ?>',
					body: '<?php echo LANG_INTHEMER_BLOCK_BODY; ?>',
					widgets: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS; ?>',
					data_block: '<?php echo LANG_INTHEMER_BLOCK_DATA_BLOCK; ?>',
					slider: '<?php echo LANG_INTHEMER_BLOCK_SLIDER; ?>',
					slide: '<?php echo LANG_INTHEMER_BLOCK_SLIDE; ?>',
					tabs: '<?php echo LANG_INTHEMER_BLOCK_TABS; ?>',
					tabpane: '<?php echo LANG_INTHEMER_BLOCK_TABPANE; ?>',
					accordion: '<?php echo LANG_INTHEMER_BLOCK_ACCORDION; ?>',
					accordionpane: '<?php echo LANG_INTHEMER_BLOCK_ACCORDIONPANE; ?>',
					youtube: '<?php echo LANG_INTHEMER_BLOCK_YOUTUBE; ?>',
					breadcrumb: '<?php echo LANG_INTHEMER_BLOCK_BREADCRUMB; ?>',
					search: '<?php echo LANG_INTHEMER_BLOCK_SEARCH; ?>',
					html: '<?php echo LANG_INTHEMER_BLOCK_HTML; ?>',
					php: '<?php echo LANG_INTHEMER_BLOCK_PHP; ?>',
				},
				search: {
					placeholder: '<?php echo LANG_INTHEMER_BLOCK_SEARCH_PLACEHOLDER; ?>',
					default_placeholder: '<?php echo LANG_INTHEMER_BLOCK_SEARCH_PLACEHOLDER_DEFAULT; ?>',
					button_title: '<?php echo LANG_INTHEMER_BLOCK_SEARCH_PLACEHOLDER_DEFAULT; ?>',
					default_button_title: '<?php echo LANG_INTHEMER_BLOCK_SEARCH_BUTTON_DEFAULT; ?>',
					form: '<?php echo LANG_INTHEMER_BLOCK_SEARCH_FORM; ?>',
					input: '<?php echo LANG_INTHEMER_BLOCK_SEARCH_FORM_INPUT; ?>',
					button: '<?php echo LANG_INTHEMER_BLOCK_SEARCH_FORM_BUTTON; ?>',
				},
				section: {
					is_parallax: '<?php echo LANG_INTHEMER_SECTION_PARALLAX; ?>',
					is_pin_down: '<?php echo LANG_INTHEMER_SECTION_IS_PIN_DOWN; ?>',
					is_inline: '<?php echo LANG_INTHEMER_SECTION_IS_INLINE; ?>',
				},
				data_block: {
					is_link: '<?php echo LANG_INTHEMER_BLOCK_DATA_BLOCK_LINK; ?>',
					bg_image: '<?php echo LANG_INTHEMER_BLOCK_DATA_BLOCK_BG; ?>',
					item: '<?php echo LANG_INTHEMER_BLOCK_DATA_BLOCK_ITEM; ?>',
				},
				text: {
					max_len: '<?php echo LANG_INTHEMER_BLOCK_TEXT_MAX_LEN; ?>',
				},
				row: {
					col_size: '<?php echo LANG_INTHEMER_BLOCK_ROW_COL_SIZES; ?>',
					col_class: '<?php echo LANG_INTHEMER_BLOCK_ROW_COL_CLASS; ?>',
				},
				image: {
					src: '<?php echo LANG_INTHEMER_BLOCK_IMAGE_SRC; ?>',
					alt: '<?php echo LANG_INTHEMER_BLOCK_IMAGE_ALT; ?>',
					title: '<?php echo LANG_INTHEMER_BLOCK_IMAGE_TITLE; ?>',
					default: '<?php echo LANG_INTHEMER_BLOCK_IMAGE_DEFAULT; ?>',
					placehold: '<?php echo LANG_INTHEMER_BLOCK_IMAGE_PLACEHOLD; ?>',
					placehold_src: '<?php echo LANG_INTHEMER_BLOCK_IMAGE_PLACEHOLD_SRC; ?>',
					placehold_src_flat: '<?php echo LANG_INTHEMER_BLOCK_IMAGE_PLACEHOLD_SRC_FLAT; ?>',
					placehold_src_photo: '<?php echo LANG_INTHEMER_BLOCK_IMAGE_PLACEHOLD_SRC_PHOTO; ?>',
					width: '<?php echo LANG_INTHEMER_BLOCK_IMAGE_WIDTH; ?>',
					height: '<?php echo LANG_INTHEMER_BLOCK_IMAGE_HEIGHT; ?>',
				},
				menu: {
					menu: '<?php echo LANG_MENU; ?>',
					child_menu: '<?php echo LANG_INTHEMER_MENU_CHILD; ?>',
					item: '<?php echo LANG_INTHEMER_MENU_ITEM; ?>',
					child_item: '<?php echo LANG_INTHEMER_MENU_CHILD_ITEM; ?>',
					active_item: '<?php echo LANG_INTHEMER_MENU_ACTIVE_ITEM; ?>',
					active_child_item: '<?php echo LANG_INTHEMER_MENU_ACTIVE_CHILD_ITEM; ?>',
					button: '<?php echo LANG_INTHEMER_MENU_BUTTON; ?>',
					orientation: '<?php echo LANG_INTHEMER_MENU_ORIENTATION; ?>',
					horizontal_top: '<?php echo LANG_INTHEMER_MENU_HORIZONTAL_TOP; ?>',
					horizontal_bottom: '<?php echo LANG_INTHEMER_MENU_HORIZONTAL_BOTTOM; ?>',
					vertical_left: '<?php echo LANG_INTHEMER_MENU_VERTICAL_LEFT; ?>',
					vertical_right: '<?php echo LANG_INTHEMER_MENU_VERTICAL_RIGHT; ?>',
					collapse: '<?php echo LANG_INTHEMER_MENU_COLLAPSE; ?>',
				},
				widgets: {
					position: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_POS; ?>',
					wrap: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_WRAP; ?>',
					title: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_TITLE; ?>',
					body: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_BODY; ?>',
					link: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_LINK; ?>',
					tabbed_wrap: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_TABBED_WRAP; ?>',
					tabbed_title: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_TABBED_TITLE; ?>',
					tabbed_tab: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_TABBED_TAB; ?>',
					tabbed_tab_active: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_TABBED_TAB_ACTIVE; ?>',
					tabbed_body: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_TABBED_BODY; ?>',
					tabbed_link: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_TABBED_LINK; ?>',
					preview: {
						mode: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_PREVIEW_MODE; ?>',
						show3: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_PREVIEW_3; ?>',
						show2: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_PREVIEW_2; ?>',
						show1: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_PREVIEW_1; ?>',
						none: '<?php echo LANG_INTHEMER_BLOCK_WIDGETS_PREVIEW_0; ?>',
					}
				},
				slider: {
					height: '<?php echo LANG_INTHEMER_BLOCK_SLIDER_HEIGHT; ?>',
					count: '<?php echo LANG_INTHEMER_BLOCK_SLIDER_COUNT; ?>',
					slide_delay: '<?php echo LANG_INTHEMER_BLOCK_SLIDER_SLIDE_DELAY; ?>',
					anim_delay: '<?php echo LANG_INTHEMER_BLOCK_SLIDER_ANIM_DELAY; ?>',
					is_auto: '<?php echo LANG_INTHEMER_BLOCK_SLIDER_IS_AUTO; ?>',
					is_hide_nav: '<?php echo LANG_INTHEMER_BLOCK_SLIDER_IS_HIDE_NAV; ?>',
					is_dots: '<?php echo LANG_INTHEMER_BLOCK_SLIDER_COUNT_IS_DOTS; ?>',
					arrows: '<?php echo LANG_INTHEMER_BLOCK_SLIDER_ARROWS; ?>',
					arrow_prev: '<?php echo LANG_INTHEMER_BLOCK_SLIDER_ARROWS_PREV; ?>',
					arrow_next: '<?php echo LANG_INTHEMER_BLOCK_SLIDER_ARROWS_NEXT; ?>',
				},
				tabs: {
					tabs_list: '<?php echo LANG_INTHEMER_BLOCK_TABS_LIST; ?>',
					tab: '<?php echo LANG_INTHEMER_BLOCK_TABS_TAB; ?>',
					tab_titles: '<?php echo LANG_INTHEMER_BLOCK_TABS_TAB_TITLES; ?>',
					count: '<?php echo LANG_INTHEMER_BLOCK_TABS_TAB_COUNT; ?>',
					tab_active: '<?php echo LANG_INTHEMER_BLOCK_TABS_TAB_ACTIVE; ?>',
				},
				accordion: {
					pane_titles: '<?php echo LANG_INTHEMER_BLOCK_ACCORDION_PANEL_TITLES; ?>',
					pane: '<?php echo LANG_INTHEMER_BLOCK_ACCORDION_PANE; ?>',
					pane_title: '<?php echo LANG_INTHEMER_BLOCK_ACCORDION_PANEL_TITLE; ?>',
					pane_body: '<?php echo LANG_INTHEMER_BLOCK_ACCORDION_PANEL_BODY; ?>',
					pane_active: '<?php echo LANG_INTHEMER_BLOCK_ACCORDION_PANEL_PANE_ACTIVE; ?>',
					title_active: '<?php echo LANG_INTHEMER_BLOCK_ACCORDION_PANEL_TITLE_ACTIVE; ?>',
					body_active: '<?php echo LANG_INTHEMER_BLOCK_ACCORDION_PANEL_BODY_ACTIVE; ?>',
					linked_panes: '<?php echo LANG_INTHEMER_BLOCK_ACCORDION_LINKED_PANES; ?>',
				},
				youtube: {
					url: '<?php echo LANG_INTHEMER_BLOCK_YOUTUBE; ?>',
					width: '<?php echo LANG_INTHEMER_BLOCK_YOUTUBE_WIDTH; ?>',
					height: '<?php echo LANG_INTHEMER_BLOCK_YOUTUBE_HEIGHT; ?>',
				},
				heading: {
					text: '<?php echo LANG_INTHEMER_BLOCK_HEADING_TEXT; ?>',
					type: '<?php echo LANG_INTHEMER_BLOCK_HEADING_TYPE; ?>',
				},
				breadcrumb: {
					strip_last: '<?php echo LANG_INTHEMER_BLOCK_BREADCRUMB_STRIP_LAST; ?>',
					separator: '<?php echo LANG_INTHEMER_BLOCK_BREADCRUMB_SEPARATOR; ?>',
					item: '<?php echo LANG_INTHEMER_BLOCK_BREADCRUMB_ITEM; ?>',
					active_item: '<?php echo LANG_INTHEMER_BLOCK_BREADCRUMB_ACTIVE_ITEM; ?>',
				},
				widths: {
					1440: '<?php echo LANG_INTHEMER_WIDTH_1440; ?>',
					1024: '<?php echo LANG_INTHEMER_WIDTH_1024; ?>',
					768: '<?php echo LANG_INTHEMER_WIDTH_768; ?>',
					425: '<?php echo LANG_INTHEMER_WIDTH_425; ?>',
					375: '<?php echo LANG_INTHEMER_WIDTH_375; ?>',
					320: '<?php echo LANG_INTHEMER_WIDTH_320; ?>',
				},
				styleNames: {
					'color': '<?php echo LANG_INTHEMER_CSS_TEXT_COLOR; ?>',
					'font-size': '<?php echo LANG_INTHEMER_CSS_FONT_SIZE; ?>',
					'font-weight': '<?php echo LANG_INTHEMER_CSS_FONT_WEIGHT; ?>',
					'font-style': '<?php echo LANG_INTHEMER_CSS_FONT_STYLE; ?>',
					'font-family': '<?php echo LANG_INTHEMER_CSS_FONT_FAMILY; ?>',
					'text-decoration': '<?php echo LANG_INTHEMER_CSS_TEXT_DECORATION; ?>',
					'text-transform': '<?php echo LANG_INTHEMER_CSS_TEXT_TRANSFORM; ?>',
					'text-align': '<?php echo LANG_INTHEMER_CSS_TEXT_ALIGN; ?>',
					'line-height': '<?php echo LANG_INTHEMER_CSS_LINE_HEIGHT; ?>',
					'text-indent': '<?php echo LANG_INTHEMER_CSS_TEXT_INDENT; ?>',
					'word-spacing': '<?php echo LANG_INTHEMER_CSS_WORD_SPACING; ?>',
					'letter-spacing': '<?php echo LANG_INTHEMER_CSS_LETTER_SPACING; ?>',
					'text-shadow': '<?php echo LANG_INTHEMER_CSS_TEXT_SHADOW; ?>',
					'background-color': '<?php echo LANG_INTHEMER_CSS_BG_COLOR; ?>',
					'background-image': '<?php echo LANG_INTHEMER_CSS_BG_IMAGE; ?>',
					'background-repeat': '<?php echo LANG_INTHEMER_CSS_BG_REPEAT; ?>',
					'background-position-x': '<?php echo LANG_INTHEMER_CSS_BG_POSITION_X; ?>',
					'background-position-y': '<?php echo LANG_INTHEMER_CSS_BG_POSITION_Y; ?>',
					'background-attachment': '<?php echo LANG_INTHEMER_CSS_BG_ATTACHMENT; ?>',
					'background-size': '<?php echo LANG_INTHEMER_CSS_BG_SIZE; ?>',
					'width': '<?php echo LANG_INTHEMER_CSS_WIDTH; ?>',
					'min-width': '<?php echo LANG_INTHEMER_CSS_MIN_WIDTH; ?>',
					'max-width': '<?php echo LANG_INTHEMER_CSS_MAX_WIDTH; ?>',
					'height': '<?php echo LANG_INTHEMER_CSS_HEIGHT; ?>',
					'min-height': '<?php echo LANG_INTHEMER_CSS_MIN_HEIGHT; ?>',
					'max-height': '<?php echo LANG_INTHEMER_CSS_MAX_HEIGHT; ?>',
					'position': '<?php echo LANG_INTHEMER_CSS_POSITION; ?>',
					'float': '<?php echo LANG_INTHEMER_CSS_FLOAT; ?>',
					'left': '<?php echo LANG_INTHEMER_CSS_LEFT; ?>',
					'right': '<?php echo LANG_INTHEMER_CSS_RIGHT; ?>',
					'top': '<?php echo LANG_INTHEMER_CSS_TOP; ?>',
					'bottom': '<?php echo LANG_INTHEMER_CSS_BOTTOM; ?>',
					'z-index': '<?php echo LANG_INTHEMER_CSS_Z_INDEX; ?>',
					'padding': '<?php echo LANG_INTHEMER_CSS_PADDING; ?>',
					'padding-top': '<?php echo LANG_INTHEMER_CSS_PADDING_TOP; ?>',
					'padding-right': '<?php echo LANG_INTHEMER_CSS_PADDING_RIGHT; ?>',
					'padding-bottom': '<?php echo LANG_INTHEMER_CSS_PADDING_BOTTOM; ?>',
					'padding-left': '<?php echo LANG_INTHEMER_CSS_PADDING_LEFT; ?>',
					'margin': '<?php echo LANG_INTHEMER_CSS_MARGIN; ?>',
					'margin-top': '<?php echo LANG_INTHEMER_CSS_MARGIN_TOP; ?>',
					'margin-right': '<?php echo LANG_INTHEMER_CSS_MARGIN_RIGHT; ?>',
					'margin-bottom': '<?php echo LANG_INTHEMER_CSS_MARGIN_BOTTOM; ?>',
					'margin-left': '<?php echo LANG_INTHEMER_CSS_MARGIN_LEFT; ?>',
					'border-width': '<?php echo LANG_INTHEMER_CSS_BORDER_WIDTH; ?>',
					'border-top-width': '<?php echo LANG_INTHEMER_CSS_BORDER_TOP_WIDTH; ?>',
					'border-bottom-width': '<?php echo LANG_INTHEMER_CSS_BORDER_BOTTOM_WIDTH; ?>',
					'border-left-width': '<?php echo LANG_INTHEMER_CSS_BORDER_LEFT_WIDTH; ?>',
					'border-right-width': '<?php echo LANG_INTHEMER_CSS_BORDER_RIGHT_WIDTH; ?>',
					'border-color': '<?php echo LANG_INTHEMER_CSS_BORDER_COLOR; ?>',
					'border-style': '<?php echo LANG_INTHEMER_CSS_BORDER_STYLE; ?>',
					'border-radius': '<?php echo LANG_INTHEMER_CSS_BORDER_RADIUS; ?>',
					'border-top-left-radius': '<?php echo LANG_INTHEMER_CSS_BORDER_TL_RADIUS; ?>',
					'border-top-right-radius': '<?php echo LANG_INTHEMER_CSS_BORDER_TR_RADIUS; ?>',
					'border-bottom-left-radius': '<?php echo LANG_INTHEMER_CSS_BORDER_BL_RADIUS; ?>',
					'border-bottom-right-radius': '<?php echo LANG_INTHEMER_CSS_BORDER_BR_RADIUS; ?>',
					'box-shadow': '<?php echo LANG_INTHEMER_CSS_BOX_SHADOW; ?>',
					'display': '<?php echo LANG_INTHEMER_CSS_DISPLAY; ?>',
					'visibility': '<?php echo LANG_INTHEMER_CSS_VISIBILITY; ?>',
					'opacity': '<?php echo LANG_INTHEMER_CSS_OPACITY; ?>',
				},
				styleOptions: {
					'none': '<?php echo LANG_NO; ?>',
					'repeat': '<?php echo LANG_INTHEMER_CSS_BG_REPEAT_XY; ?>',
					'repeat-x': '<?php echo LANG_INTHEMER_CSS_BG_REPEAT_X; ?>',
					'repeat-y': '<?php echo LANG_INTHEMER_CSS_BG_REPEAT_Y; ?>',
					'no-repeat': '<?php echo LANG_INTHEMER_CSS_BG_REPEAT_NO; ?>',
					'scroll': '<?php echo LANG_INTHEMER_CSS_BG_ATTACH_SCROLL; ?>',
					'fixed': '<?php echo LANG_INTHEMER_CSS_BG_ATTACH_FIXED; ?>',
					'outset': '<?php echo LANG_INTHEMER_CSS_BOX_SHADOW_OUTSET; ?>',
					'inset': '<?php echo LANG_INTHEMER_CSS_BOX_SHADOW_INSET; ?>',
				}
			};

			var urls = {
				root: '<?php echo cmsConfig::get('root'); ?>',
				save_layout: '<?php echo $this->href_to('save'); ?>',
				save_globals: '<?php echo $this->href_to('save_globals'); ?>',
				images: '<?php echo href_to('inthemer', 'images'); ?>',
				delete_image: '<?php echo href_to('inthemer', 'delete_image'); ?>',
				render: '<?php echo href_to('inthemer', 'render'); ?>',
				data_source: '<?php echo href_to('inthemer', 'datasource'); ?>',
				opt_dump: '<?php echo href_to('inthemer', 'options_dump'); ?>',
				code: {
					load: '<?php echo href_to('inthemer', 'code_load'); ?>',
					save: '<?php echo href_to('inthemer', 'code_save'); ?>',
				},
				presets: {
					add: '<?php echo href_to('inthemer', 'preset_add'); ?>',
					list: '<?php echo href_to('inthemer', 'presets'); ?>',
					delete: '<?php echo href_to('inthemer', 'preset_delete'); ?>',
				},
				library: {
					add: '<?php echo href_to('inthemer', 'library_add'); ?>',
					list: '<?php echo href_to('inthemer', 'library'); ?>',
					item: '<?php echo href_to('inthemer', 'library_item'); ?>'
				}
			};

			var menus = {
				<?php foreach ($menus as $menu) { ?>
					<?php echo $menu['id']; ?>: '<?php echo $menu['title']; ?>',
				<?php } ?>
			};

			var groups = <?php echo json_encode($groups); ?>;
			var devices = <?php echo json_encode($devices); ?>;

			var builder;

			$(document).ready(function () {
				builder = new InthemerBuilder({
					id: <?php echo $layout['id']; ?>,
					revNo: <?php echo $rev_no; ?>,
					latestRevNo: <?php echo $layout['rev_no']; ?>,
					schema: <?php echo json_encode(json_decode($layout['blocks'], true)); ?>,
					options: <?php echo $layout['options']; ?>,
					dataSources: <?php echo $layout['data_sources']; ?>,
					selectors: <?php echo $layout['selectors']; ?>
				});
				builder.init();
			});

		</script>

	</body>
</html>
