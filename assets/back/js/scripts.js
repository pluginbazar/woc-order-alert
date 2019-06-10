jQuery(document).ready(function($) {
	
	var audioElement = document.createElement('audio');
	
	/* $(window).bind("beforeunload", function() { 
		if( confirm("Do you really want to close?") ){
			$.ajax(
			{
		type: 'POST',
		context: this,
		url:woa_ajax.woa_ajaxurl,
		data: {
			"action": "woa_ajax_update_checking_status", 
			"status": "checking_off", 
		},
		success: function(data) { return true },
			});
		}
		else return false;
	}) */

	$(document).on('click', '.pc-section-orderlist .single-order .order-viewed', function() {
		
		$(this).html( '<i class="fa fa-spin fa-cog"></i>' );
		order_id = $(this).attr( 'order_id' );
		
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:woa_ajax.woa_ajaxurl,
		data: {
			"action"	: "woa_ajax_order_viewed", 
			"order_id"	: order_id, 
		},
		success: function(data) {
			audioElement.pause();
			audioElement.currentTime = 0;
			$(this).parent().remove();
		},
			});
	})
	
	$(document).on('click', '.pc-section-checker .pc-mute', function() {
		
		mute_status = $(this).attr( 'mute_status' );
		if( typeof mute_status == 'undefined' ) mute_status = "unmuted";
		
		if( mute_status == "unmuted" ){
			
			$(this).attr( 'mute_status', "muted" );
			$(this).html( 'Sound Off <i class="fa fa-volume-off"></i>' );
			// $(this).css( 'text-decoration', 'normal' );
			audioElement.pause();
			audioElement.currentTime = 0;
		} 
		else {
			
			$(this).attr( 'mute_status', "unmuted" );
			$(this).html( 'Sound On <i class="fa fa-volume-up"></i>' );
			// $(this).css( 'text-decoration', 'line-through' );
			audioElement.play();
		}
	})
	
	$(document).on('click', '.pc-stop', function() {
		
		audioElement.pause();
		audioElement.currentTime = 0;
			
		$('.pc-section-checker .fa-cog').removeClass('fa-spin');
		$('.pc-section-checker .pc-start').attr( 'disabled', false );
		$('.pc-section-checker .pc-stop').attr( 'disabled', true );
		
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:woa_ajax.woa_ajaxurl,
		data: {
			"action": "woa_ajax_update_checking_status", 
			"status": "checking_off", 
		},
		success: function(data) {},
			});
	})
	
	$(document).on('click', '.pc-start', function() {
		
		$('.pc-section-checker .fa-cog').addClass('fa-spin');
		$('.pc-section-checker .pc-start').attr( 'disabled', true );
		$('.pc-section-checker .pc-stop').attr( 'disabled', false );
		
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:woa_ajax.woa_ajaxurl,
		data: {
			"action": "woa_ajax_update_checking_status", 
			"status": "checking_on", 
		},
		success: function(data) {
	
		},
			});
	})
	
	setInterval(function(){
		
		var disabled = $('.pc-section-checker .pc-start').attr('disabled');
		if (typeof disabled !== typeof undefined && disabled !== false) {
	
			$.ajax(
				{
			type: 'POST',
			context: this,
			url:woa_ajax.woa_ajaxurl,
			data: {
				"action": "woa_ajax_check_new_order", 
			},
			success: function( response ) {
				
				console.log( response );
				
				var data = JSON.parse(response);
				
				$('.pc-section-orderlist .pc-orders-list').append( data['html'] );
				
				if( data['count'] > 0 ) {
					audioElement.setAttribute( 'src', data['audio'] );
					audioElement.setAttribute( 'autoplay', 'autoplay' );
					$.get();
					audioElement.addEventListener( 'load', function() { audioElement.play(); }, true);
					audioElement.addEventListener( 'ended', function(){audioElement.currentTime = 0;audioElement.play();});
				}
			},
				});
			
		}
	}, 5000 );
		
});

	
	
	