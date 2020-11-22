$(document).ready(function(){
	
	var $select = $('#inthemer-layout-select');	
	
	$select.change(function(){
		var name = $select.val();	
		location.href = '?layout=' + name;
	});
	
	function updateAddLink(is_no_id){
		
		var is_no_id = is_no_id || false;
		var $link = $('.it-widgets-layout-selector .actions .add');
		var href = $link.data('href');
		
		var p = page_id;		
		var url = is_no_id ? href : href + '?wpid=' + p;
		
		$link.attr('href', url);
		
	}

	$('#datatree').on('click', '.dynatree-node', function(e){
		var is_no_id = $(this).hasClass('dynatree-folder');
		setTimeout(function(){
			updateAddLink(is_no_id);
		}, 100);		
	});

	updateAddLink();
	
});
