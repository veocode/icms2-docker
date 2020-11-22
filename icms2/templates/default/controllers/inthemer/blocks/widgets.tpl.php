<?php if ($preview == 0) { ?>
	<img src="<?php echo cmsConfig::get('root'); ?>templates/inthemer/builder/images/blocks/widgets.png">
<?php } ?>
<?php if ($preview > 0) { ?>
<div class="widget">
	<div class="title">
		Первый виджет
	</div>
	<div class="body">
		Пастиш, если уловить хореический ритм или аллитерацию на "р", нивелирует мелодический не-текст. Даже в этом коротком фрагменте видно, что холодный цинизм разрушаем.
	</div>
</div>
<?php } ?>
<?php if ($preview == 3) { ?>
<div class="widget">
	<div class="title">
		Второй виджет
		<div class="links">
			<a href="/#">
				#1
			</a>
			<a href="/#">
				#2
			</a>
		</div>
	</div>
	<div class="body">
		Если архаический миф не знал противопоставления реальности тексту, симулякр начинает парафраз, потому что в стихах и в прозе автор рассказывает нам об одном и том же
	</div>
</div>
<?php } ?>
<?php if ($preview > 1) { ?>
<div class="widget_tabbed">
	<div class="tabs">
		<ul>
			<li class="tab">
				<a class="active">Таб 1</a>
			</li>
			<li class="tab">
				<a>Таб 2</a>
			</li>
			<li class="links">
				<div class="links-wrap" id="widget-links-62" style="display: block;">
					<a href="/news">
						 #1
					</a>
					<a href="/news-popular">
						 #2
					</a>
				</div>
			</li>
		</ul>
	</div>
	<div class="widgets">
		<div class="body">
			Если архаический миф не знал противопоставления реальности тексту, симулякр начинает парафраз, потому что в стихах и в прозе автор рассказывает нам об одном и том же
		</div>
	</div>
</div>
<?php } ?>