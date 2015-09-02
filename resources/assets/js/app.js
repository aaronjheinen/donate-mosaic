// Globals
var vm;
var isDown = false;   // Tracks status of mouse button

(function($) {
  var Donate = {
    // All pages
    'common': {
      init: function() {
		Vue.http.headers.common['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr('content');
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
		    }
		});
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // donate page
    'donate': {
      init: function() {
      		$( window ).resize(function() {
			  resize();
			});
			$(document).mousedown(function() {
		    	isDown = true;      // When mouse goes down, set isDown to true
			})
			.mouseup(function() {
			    isDown = false;    // When mouse goes up, set isDown to false
			});
		}
	},
    'user': {
      init: function() {

		$('.donate-box.available').on('click', function(){
  			toggleBoxUser(this);
		});

		$(".donate-box").mouseover(function(){
		    if(isDown) {        
		    	toggleBoxUser(this);
		    }
		});

		vm = new Vue({

		  el: '.user',

		  data: {
		  	chosen: []
		  },

		  ready: function() {
		  	this.$set('purchase.set_id', 1);
		  	this.getSet(1);
		  	this.$watch('chosen', function (newVal, oldVal) {
		  		var total = this.$get('set').price * newVal.length;
			  	this.$set('purchase.price', total);
			});
		  },

		  methods: {
		  	getSet: function($id){
	  			this.$http.get('api/sets/' + $id).success(function(set) {
				  this.$set('set', set);

				  resize();
				}).error(function(error) {
				  console.log(error);
				});
		  	},
		  	upload: function(e) {
	            e.preventDefault();
	            var files = this.$$.image.files;
	            var data = new FormData();
	            data.append('image', files[0]);
	            this.$http.post('api/image/upload', data, function (data, status, request) {
	            	this.$set('img_url', data.url);
	            	this.$set('purchase.media_id', data.id);
	            }).error(function (data, status, request) {
	                console.log(data);
	            });
		  		console.log('done uploading');
	        }
		  }

		});

		// Interactive Credit Card
		// https://github.com/jessepollak/card
		var card = new Card({
		    form: 'form',
		    container: '.card-wrapper',

		    formSelectors: {
		        nameInput: 'input[name="name"]'
		    }
		});

		jQuery('form').submit(function(event) {
			var month = $('input[name="expiry"]').val().slice(0,2);
			var year = $('input[name="expiry"]').val().slice(5);

    		// Disable the submit button to prevent repeated clicks
    		
    		$('#btn_submit').prop('disabled', true);

    		Stripe.card.createToken({
			  number: $('input[name="number"]').val(),
			  cvc: $('input[name="cvc"]').val(),
			  exp_month: parseInt(month),
			  exp_year: parseInt(year)
			}, stripeResponseHandler);

	        event.preventDefault();

	    });
		function stripeResponseHandler(status, response) {
		  if (response.error) {
		    // Show the errors on the form
		    $('.payment-errors').text(response.error.message);
		    $('#btn_submit').prop('disabled', false);
		  } else {
		    // response contains id and card, which contains additional card details
		    // Submit Purchase
		    var formData = {
                'token_id'  : response.id,
                'price' : vm.purchase.price,
                'name' : vm.purchase.name,
                'email' : vm.purchase.email,
                'media_id' : vm.purchase.media_id,
	            'chosen'    : vm.chosen
	        };

			console.log( formData );

	        jQuery.ajax({
	            type        : 'POST', 
	            url         : 'purchase', 
	            data        : formData, 
	            dataType    : 'json', 
	            encode      : true
	        })
	        .done(function(data) {

	        	Materialize.toast('Payment successfully received!', 4000);
	        });
		  }
		};
      }
    },
    'admin': {
      init: function() {

      	vm = new Vue({

		  el: '.admin',

		  data: {

		  },

		  ready: function() {
		  	this.getSet(1);
		  	this.$watch('set.price', function (newVal, oldVal) {
			  	var set = this.$get('set');
				this.$set('set.available_price', set.price * set.available);
			});
		  	this.$watch('set.available', function (newVal, oldVal) {
			  	var set = this.$get('set');
				this.$set('set.available_price', set.price * set.available);
			});
		  	this.$watch('chosen', function (newVal, oldVal) {
		  		var total = this.$get('set').rows * this.$get('set').cols;
			  	this.$set('set.available', total - this.$get('chosen').length);
			});
		  },

		  methods: {
		  	getSet: function($id){
	  			this.$http.get('api/admin/sets/' + $id).success(function(set) {
				  this.$set('set', set);
				  this.$set('set.available_price', set.price * set.available);

				  this.$set('chosen', []);
				  for( var a = 0; a < set.squares.length; a++ ){
				  	this.chosen.push(set.squares[a].id);
				  }

				  resize();
				}).error(function(error) {
				  console.log(error);
				});
		  	}
		  }

		});
		Vue.config.debug = true;

		$(".donate-box").mouseover(function(){
		    if(isDown) {        
		    	toggleBoxAdmin(this);
		    }
		});

  		$('.donate-box').on('click', function(){
  			toggleBoxAdmin(this);
		});


      	jQuery('form').submit(function(event) {

	        var formData = {
	        	'set'    : vm.set,
	            'chosen' : vm.chosen
	        };

	        jQuery.ajax({
	            type        : 'POST', 
	            url         : 'admin/update', 
	            data        : formData, 
	            dataType    : 'json', 
	            encode      : true
	        })
	        .done(function(data) {

	            Materialize.toast('Saved!', 4000) 

	        });

	        event.preventDefault();
	    });

      }
  	}
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Donate;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.

function toggleBoxUser(box){
	var id = $(box).attr('id');
	var sid = id.slice(7);

	if( $(box).hasClass('chosen') ){

		var index = vm.chosen.indexOf( sid );
		vm.chosen.$remove( index );

	} else {

		vm.chosen.push( sid );

	}

	console.log(vm.chosen);

	$(box).toggleClass('chosen');
}
function toggleBoxAdmin(box){
	var id = $(box).attr('id');
	var sid = id.slice(7);

	if( $(box).hasClass('invisible') ){

		var index = vm.chosen.indexOf( parseInt( sid ) );

		vm.chosen.$remove( index );

	} else {

		vm.chosen.push( sid );

	}

	$(box).toggleClass('invisible');
}
function resize(){
	console.log('resized');
	// Not null
	if(!!vm){
		var h = $('#donate-img').height() / vm.$get('set').rows;
		// -1 for border-top
		$('.donate-box').css('height', h +'px');
		console.log(h);
	}
}