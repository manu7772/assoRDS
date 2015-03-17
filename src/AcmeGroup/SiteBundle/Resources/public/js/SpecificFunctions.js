jQuery(document).ready(function($) {

	////////////////////////////////////////////////////////
	// Flexi-menu / carousel / tooltips
	////////////////////////////////////////////////////////

	$(".flexy-menu").flexymenu({
		speed: 400,
		type: "vertical"
	});
	$('.car-intro').each(function() {
		$(this).carousel({ interval: $(this).attr('data-speed') });
	});
	$('.car-event').each(function() {
		$(this).carousel({
			interval: false,
			speed: false,
			wrap: false
		});
	});
	// menu pour carousel
	$('body').on('mouseenter', '.carousel-menu', function() {
		if($(this).attr('data-action') != 'ebablecolor') {
			$(this).data('bgcolor', $(this).css('background-color'));
			$(this).css('background-color', '#666');
		}
	});
	$('body').on('mouseleave', '.carousel-menu', function() {
		if($(this).attr('data-action') != 'ebablecolor') {
			$(this).css('background-color', $(this).data('bgcolor'));
		}
	});
	$('body').on('click', '.carousel-menu', function() {
		id = $(this).attr('id').split('-');
		indx = parseInt(id[1]);
		// alert('Change carousel : '+indx);
		$($(this).attr('data-ref')).carousel(indx);
	});
	// tooltips
	$('.tooltips').tooltip();



	////////////////////////////////////////////////////////
	// Vidéos en modale
	////////////////////////////////////////////////////////
	$('body').on("click", ".modalevideo", function(event) {
		event.preventDefault();
		dat = $(this).attr("data-prototype").split("__");
		link = dat[0];
		title = dat[1];
		$videowin = $('<div title="'+title+'"><iframe width="560" height="315" src="//www.youtube.com/embed/'+link+'" frameborder="0" allowfullscreen></iframe></div>');
		$videowin.dialog({
			modal: true,
			width: 585,
			height: 408,
			show: {
				effect: "fadeIn",
				duration: 1000
	  		},
	  		hide: {
				effect: "FadeOut",
				duration: 1000
	 		 },
			buttons: {"Fermer": function(){$(this).dialog("close");}},
			close: function() {
				$(this).dialog("destroy").remove();
			},
			create: function() {
				$(".ui-dialog-title", $(this)).hide();
			}
		});
		return false;
	});


});












