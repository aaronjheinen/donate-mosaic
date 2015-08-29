$(document).ready(function(){
	var chosen = [];

	$('.donate-box').on('click', function(){
		var id = $(this).attr('id');
		var y = id.slice(7,9);
		var x = id.slice(10,12);
		chosen.push({
			'x' : x,
			'y' : y
		})
		console.log( chosen );
		$(this).addClass('chosen');
	});

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	$('form').submit(function(event) {

		console.log('purachsing');

        var formData = {
            'name'              : $('input[name=name]').val(),
            'email'             : $('input[name=email]').val(),
            'chosen'            : chosen
        };

        $.ajax({
            type        : 'POST', 
            url         : 'purchase', 
            data        : formData, 
            dataType    : 'json', 
            encode      : true
        })
        .done(function(data) {

            console.log(data); 

        });

        event.preventDefault();
    });

});

window.addEventListener('load', function () {
	var h = $('#donate-img').height() / 100;
	// -1 for border-top
	$('.donate-box').css('height', h - 1 +'px');
	console.log(h);
}, false);