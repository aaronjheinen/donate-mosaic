// Globals
var vm;
var isDown = false;   // Tracks status of mouse button

(function($) {
  var Donate = {
    // All pages
    'common': {
      init: function() {
		Vue.config.debug = true;
		Vue.http.headers.common['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr('content');
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
		    }
		});
		$(".clickable-row").click(function() {
	        window.document.location = $(this).data("href");
	    });
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // donate page
    'donate': {
      init: function() {
			resize();

      		$( window ).resize(function() {
			  resize();
			});
			$(document).mousedown(function() {
		    	isDown = true;      // When mouse goes down, set isDown to true
			})
			.mouseup(function() {
			    isDown = false;    // When mouse goes up, set isDown to false
			});
			// Tooltip 
			$('.donate-box.taken.has-image').tooltipster({
                contentAsHTML: true,
   				touchDevices: false,
   				theme: 'tooltipster-noir'
            });
		}
	},
    'user': {
      init: function() {

		$('.donate-box.available,.donate-box.chosen').on('click', function(){
  			toggleBoxUser(this);
		});

		$(".donate-box.available,.donate-box.chosen").mouseover(function(){
		    if(isDown) {        
		    	toggleBoxUser(this);
		    }
		});

		vm = new Vue({

		  el: '.user',

		  data: {
		  	chosen: [],
		  	set: {
		  		price: null
		  	},
		  	purchase: {
		  		set_id: 1,
		  		email: '',
		  		name: '',
		  		blocks: 0,
		  		price: null,
		  		media_id: null,
		  		color: '#4fad2f',
		  		optin: true
		  	},
		  	image: null,
		  	img_url: null
		  },

		  ready: function() {
		  	this.$set('purchase.set_id', 1);
		  	this.getSet(1);
		  	this.$watch('chosen', function (newVal, oldVal) {
		  		if(this.$get('img_url')){
		  			$('.chosen').css('backgroundColor', 'transparent');
		  			$('.chosen').css('background-image', 'url(' + this.$get('img_url') + ')');
		  		} else {
		  			$('.chosen').css('backgroundColor', this.$get('purchase').color);
		  		}
		  		var total = this.$get('set').price * newVal.length;
			  	this.$set('purchase.price', total);
			  	this.$set('purchase.blocks', newVal.length);
			});
		  	this.$watch('purchase.color', function (newVal, oldVal) {
		  		$('.chosen').css('backgroundColor', newVal);
			});
		  	this.$watch('img_url', function (newVal, oldVal) {
		  		$('.chosen').css('backgroundColor', 'transparent');
		  		$('.chosen').css('background-image', 'url(' + newVal + ')');
			});
			$('.minicolors').minicolors();
		  },

		  methods: {
		  	getSet: function($id){
	  			this.$http.get('api/sets/' + $id).success(function(set) {
				  this.$set('set', set);
				  $('.donate-box').css('width', 100 / parseInt(set.cols) + '%');
				  $('.donate-box').css('height', 100 / parseInt(set.rows) + '%');
				}).error(function(error) {
				  console.log(error);
				});
		  	},
		  	updateBlocks: function(){
		  		console.log(this.$get('purchase').blocks);
		  		if( this.$get('purchase').blocks < 0 ){
		  			this.$set('purchase.blocks', 0);
		  		} else {
			  		if( this.$get('purchase').blocks > this.$get('chosen').length ){
			  			var difference = this.$get('purchase').blocks - this.$get('chosen').length;
			  			for(var i = 0; i < difference; i++){
			  				var random = Math.floor(Math.random() * $('.donate-box.available').length);
			  				var block = $('.donate-box.available')[random];
			  				toggleBoxUser(block);
			  			}
			  		} else if( this.$get('purchase').blocks < this.$get('chosen').length ){
			  			var length = this.$get('chosen').length;
			  			for(var i = length; i > this.$get('purchase').blocks; i--){
				  			var block = $('#square-'+vm.chosen[i - 1]);
			  				toggleBoxUser(block);
				  			vm.chosen.splice( i - 1, 1 );
				  		}
			  		}
		  		}
		  	},
		  	upload: function(e) {
	            e.preventDefault();
	            var file = $('#image').prop('files')[0];
	            var data = new FormData();
	            data.append('image', file);
	            this.$http.post('api/image/upload', data, function (data, status, request) {
	                this.$set('img_url', data.url);
	            	this.$set('purchase.media_id', data.id);
	            }).error(function (data, status, request) {
	                console.log(data);
	            });
	        },
	        setReward: function(blocks){
	        	this.$set('purchase.blocks', blocks);
	        	var length = this.$get('chosen').length;
	  			for(var i = length; i > this.$get('purchase').blocks; i--){
		  			var block = $('#square-'+vm.chosen[i - 1]);
	  				toggleBoxUser(block);
		  			vm.chosen.splice( i - 1, 1 );
		  		}
		  		vm.updateBlocks();
	        },
	        setMedia: function(media_id, img_url){
	        	this.$set('purchase.media_id', media_id);
	        	this.$set('img_url', img_url);
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
			// Disable the submit button to prevent repeated clicks
			$('#btn_submit').prop('disabled', true);
			// Verify all blocks are still available
			 var blockData = {
	            'chosen'    : vm.chosen
	        };
			jQuery.ajax({
	            type        : 'POST', 
	            url         : 'available', 
	            data        : blockData, 
	            dataType    : 'json', 
	            encode      : true
	        })
	        .done(function(data) {
	        	console.log(data);
	        	if (data.hasOwnProperty('status')) {
					// Continue
	    			generateStripeToken();
				} else {
					// Make toast and scroll back to top so user is sure to see what got unselected
	            	Materialize.toast("A block you've selected has been purchased already! It has been unselected for you. Please review your selection and try again.", 6500);
	            	$('body').scrollTop(0);
					for(var i = 0; i < data.length; i++){
						// Reset these squares
						var block = $('#square-' + data[i]);
		  				toggleBoxUser(block);
		  				block.removeClass('available');
		  				block.addClass('taken');
					}
		    		$('#btn_submit').prop('disabled', false);
				}
	        });
	        event.preventDefault();
	    });
	    function generateStripeToken(){
	    	var month = $('input[name="expiry"]').val().slice(0,2);
			var year = $('input[name="expiry"]').val().slice(5);

    		Stripe.card.createToken({
			  number: $('input[name="number"]').val(),
			  cvc: $('input[name="cvc"]').val(),
			  exp_month: parseInt(month),
			  exp_year: parseInt(year)
			}, stripeResponseHandler);

	    };
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
                'price'     : vm.purchase.price,
                'name'      : vm.purchase.name,
                'email'     : vm.purchase.email,
                'media_id'  : vm.purchase.media_id,
                'color'     : vm.purchase.color,
	            'optin'     : vm.purchase.optin,
	            'chosen'    : vm.chosen,
	        };

	        jQuery.ajax({
	            type        : 'POST', 
	            url         : 'purchase', 
	            data        : formData, 
	            dataType    : 'json', 
	            encode      : true
	        })
	        .done(function(data) {
	        	window.location.href = baseUrl + "/thanks/" + data.id;
	        });
		  }
		};
      }
    },
    'user_mobile': {
      init: function() {
      	
        // $('.navbar-toggle').sideNav();
      	vm = new Vue({

		  el: '.user-mobile',

		  data: {
		  	set: {
		  		price: null
		  	},
		  	purchase: {
		  		set_id: 1,
		  		email: '',
		  		name: '',
		  		blocks: 0,
		  		price: null,
		  		media_id: null,
		  		color: '#4fad2f',
		  		optin: true
		  	},
		  	img_url: null
		  },

		  ready: function() {
		  	this.$set('purchase.set_id', 1);
		  	this.getSet(1);
		  	this.$watch('purchase.blocks', function (newVal, oldVal) {
		  		var total = this.$get('set').price * newVal;
			  	this.$set('purchase.price', total);
			});
			$('.minicolors').minicolors();
		  },

		  methods: {
		  	getSet: function($id){
	  			this.$http.get('api/sets/' + $id).success(function(set) {
				  this.$set('set', set);
				}).error(function(error) {
				  console.log(error);
				});
		  	},
		  	updateBlocks: function(){
		  		if( this.$get('purchase').blocks < 0 ){
		  			this.$set('purchase.blocks', 0);
		  		}
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
	        },
	        setReward: function(blocks){
	        	this.$set('purchase.blocks', parseInt( blocks ) );
	        },
	        setMedia: function(media_id, img_url){
	        	this.$set('purchase.media_id', media_id);
	        	this.$set('img_url', img_url);
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
			// Disable the submit button to prevent repeated clicks
			$('#btn_submit').prop('disabled', true);
			
	    	generateStripeToken();
				
	        event.preventDefault();
	    });
	    function generateStripeToken(){
	    	var month = $('input[name="expiry"]').val().slice(0,2);
			var year = $('input[name="expiry"]').val().slice(5);

    		Stripe.card.createToken({
			  number: $('input[name="number"]').val(),
			  cvc: $('input[name="cvc"]').val(),
			  exp_month: parseInt(month),
			  exp_year: parseInt(year)
			}, stripeResponseHandler);

	    };
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
                'blocks'	: vm.purchase.blocks,
                'price'     : vm.purchase.price,
                'name'      : vm.purchase.name,
                'email'     : vm.purchase.email,
                'media_id'  : vm.purchase.media_id,
                'color'     : vm.purchase.color,
	            'optin'     : vm.purchase.optin,
	            'mobile'    : 1,
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
	        	window.location.href = baseUrl + "/thanks/" + data.id;
	        });
		  }
		};
      }
    },
    'admin_edit_content': {
      init: function() {
      	var editor;
      	editor = ContentTools.EditorApp.get();
		editor.init('*[data-editable]', 'data-name');
		editor.bind('save', function (regions) {
		    var name, onStateChange, payload, xhr;

		    // Set the editor as busy while we save our changes
		    this.busy(true);

		    // Collect the contents of each region into a FormData instance
		    formData = {
		    	'header-content' : $('[data-name=header-content]').html(),
		    	'disclaimer-content' : $('[data-name=disclaimer-content]').html()
		    };
		    // http://getcontenttools.com/tutorials/saving-strategies
		    console.log(formData);
		    jQuery.ajax({
	            type        : 'POST', 
	            url         : baseUrl + '/api/admin/set/1/content', 
	            data        : formData, 
	            dataType    : 'json', 
	            encode      : true
	        })
	        .done(function(data){
	        	editor.busy(false);
	        })
	        .success(function(data) {
	        	new ContentTools.FlashUI('ok');
	        })
	        .error(function(data) {
	        	new ContentTools.FlashUI('no');
	        });
		});
      }
  	},
  	'admin_grid': {
  		init: function() {

      	vm = new Vue({

		  el: '.admin-grid',

		  data: {},

		  ready: function() {
		  	this.getSet(1);
		  },

		  methods: {
		  	getSet: function($id){
	  			this.$http.get(baseUrl + '/api/admin/set/' + $id + '/meta').success(function(set) {
				  $('.donate-box').css('width', 100 / parseInt(set.cols) + '%');
				  $('.donate-box').css('height', 100 / parseInt(set.rows) + '%');
				}).error(function(error) {
				  console.log(error);
				});
		  	}
		  }

		});
      }
  	},
  	'user_thanks': {
  		init: function() {

      	vm = new Vue({

		  el: '.user-thanks',

		  data: {},

		  ready: function() {
		  	this.getSet(1);
		  },

		  methods: {
		  	getSet: function($id){
	  			this.$http.get(baseUrl + '/api/sets/' + $id).success(function(set) {
				  $('.donate-box').css('width', 100 / parseInt(set.cols) + '%');
				  $('.donate-box').css('height', 100 / parseInt(set.rows) + '%');
				}).error(function(error) {
				  console.log(error);
				});
		  	}
		  }

		});
      }
  	},
    'donate_admin': {
      init: function() {

      	vm = new Vue({

		  el: '.donate-admin',

		  data: {
			set: {
		  		price: null,
		  		available: null,
		  		available_price: null
		  	},
		  	chosen: null,
		  	unchosen: null
		  },

		  ready: function() {
		  	this.getSet(1);
		  	this.$watch('chosen', function (newVal, oldVal) {
		  		var total = this.$get('set').rows * this.$get('set').cols;
			  	this.$set('set.available', total - this.$get('chosen').length);
			});
		  },

		  methods: {
		  	getSet: function($id){
	  			this.$http.get(baseUrl + '/api/admin/set/' + $id).success(function(set) {
				  this.$set('set', set);
				  $('.donate-box').css('width', 100 / parseInt(set.cols) + '%');
				  $('.donate-box').css('height', 100 / parseInt(set.rows) + '%');

				  this.$set('chosen', []);
				  this.$set('unchosen', []);

				}).error(function(error) {
				  console.log(error);
				});
		  	}
		  }

		});

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
	            'chosen'   : vm.chosen,
	            'unchosen' : vm.unchosen
	        };

	        jQuery.ajax({
	            type        : 'POST', 
	            url         : baseUrl + '/api/admin/set/'+vm.set.id+'/available', 
	            data        : formData, 
	            dataType    : 'json', 
	            encode      : true
	        })
	        .done(function(data) {
	            Materialize.toast('Saved!', 4000);
	        });

	        event.preventDefault();
	    });

      }
  	},
  	'set_settings': {
      init: function() {
  	    var originalRows = 0;
  	    var originalCols = 0;
      	vm = new Vue({
		  el: '.set-settings',

		  data: {
			set: {
		  		price: null,
		  		available: null,
		  		available_price: ''
		  	},
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
		  	this.$watch('set.rows', function (newVal, oldVal) {
		  		newVal = parseInt(newVal);
		  		if(newVal > 200){
		  			this.$set('set.rows', 200);
		  		} else if(newVal < 1){
		  			this.$set('set.rows', 1);
		  		}
		  		this.gridSize();
			});
		  	this.$watch('set.cols', function (newVal, oldVal) {
		  		newVal = parseInt(newVal);
		  		if(newVal > 200){
		  			this.$set('set.cols', 200);
		  		} else if(newVal < 1){
		  			this.$set('set.cols', 1);
		  		}
		  		this.gridSize();
			});
		  },

		  methods: {
		  	getSet: function($id){
	  			this.$http.get(baseUrl + '/api/admin/set/' + $id + '/squares').success(function(set) {
  				  originalCols = set.cols;
  				  originalRows = set.rows;
				  this.$set('set', set);
				  this.$set('set.available_price', set.price * set.available);
				  $('.donate-box').css('width', 100 / parseInt(set.cols) + '%');
				  $('.donate-box').css('height', 100 / parseInt(set.rows) + '%');
				}).error(function(error) {
				  console.log(error);
				});
		  	},
		  	gridSize: function(){
			  	var set = this.$get('set');
				this.$set('set.squares', new Array( set.rows * set.cols ) );
				$('.donate-box').css('width', 100 / parseInt(set.cols) + '%');
				$('.donate-box').css('height', 100 / parseInt(set.rows) + '%');
		  	},
		  	submitForm: function(){
		        var formData = {
		        	'_method'  : 'put',
		        	'name'     : vm.set.name,
		        	'price'    : vm.set.price,
		        	'rows'     : parseInt(vm.set.rows),
		        	'cols'     : parseInt(vm.set.cols)
		        };
		        console.log(formData);
		        jQuery.ajax({
		            type        : 'POST', 
		            url         : baseUrl + '/api/admin/set/'+vm.set.id, 
		            data        : formData, 
		            dataType    : 'json', 
		            encode      : true
		        })
		        .success(function(data){
		        	console.log(data);
		            Materialize.toast('Saved!', 4000);
		        })
		        .error(function(data){
		        	console.log(data);
		        });
		  	}
		  }

		});

      	jQuery('form').submit(function(event) {
		    event.preventDefault();
      		// check if rows / cols differ from setOriginal - if so show confirm modal
      		console.log(vm.set.cols);
      		console.log(originalCols);
      		if( vm.set.rows < originalRows || vm.set.cols < originalCols ){
      			jQuery('#modalConfirm').openModal();
      		} else {
      			vm.submitForm();
      		}
	    });

      }
  	},
    'set_purchases': {
      init: function() {

      	vm = new Vue({

		  el: '.set-purchases',

		  data: {
			set: {
		  		price: null,
		  		available: null,
		  		available_price: null
		  	},
		  	moving: false,
		  	move : {
		  		id : null,
		  		background_image: null,
		  		background_color: null,
		  	}
		  },

		  ready: function() {
		  	this.getSet(1);
		  },

		  methods: {
		  	getSet: function($id){
	  			this.$http.get(baseUrl + '/api/admin/set/' + $id).success(function(set) {
				  this.$set('set', set);
				  $('.donate-box').css('width', 100 / parseInt(set.cols) + '%');
				  $('.donate-box').css('height', 100 / parseInt(set.rows) + '%');

				}).error(function(error) {
				  console.log(error);
				});
		  	}
		  }

		});

  		$('.donate-box.taken').on('click', function(){
  			if(!vm.$get('moving')){
	  			var id = $(this).attr('id');
				var sid = id.slice(7);
				vm.$set('moving', true);
				vm.$set('move.id', sid);
	        	// bg = bg.replace('url(','').replace(')','');
	        	vm.$set('move.background_image', $(this).css('background-image'));
	        	$(this).css('background-image', '');
  			}
		});
  		$('.donate-box.available').hover(function(){
  			if(vm.$get('moving')){
  				$(this).css('background-image', vm.$get('move.background_image'));
  			}
  		}, function() {
			if(vm.$get('moving')){
  				$(this).css('background-image', '');
  			}
  		});
  		$('.donate-box.available').on('click', function(){
  			if(vm.$get('moving')){
				vm.$set('moving', false);
				// Update classes on old position
				$('#square-'+vm.$get('move').id).removeClass('taken');
				$('#square-'+vm.$get('move').id).removeClass('tooltipstered');
				$('#square-'+vm.$get('move').id).addClass('available');
				// Update classes to new position
				$('#square-'+$(this).attr('id').slice(7)).removeClass('available');
				$('#square-'+$(this).attr('id').slice(7)).addClass('taken');
				// Submit update
		        var formData = {
		            'from'   : vm.$get('move').id,
		            'to' : $(this).attr('id').slice(7)
		        };

		        jQuery.ajax({
		            type        : 'POST', 
		            url         : baseUrl + '/api/admin/set/'+vm.set.id+'/move', 
		            data        : formData, 
		            dataType    : 'json', 
		            encode      : true
		        })
		        .done(function(data) {
		            Materialize.toast('Move saved!', 1500);
		        });

  			}
		});

      	

      }
  	},
  	'generate_image' : {
	    init: function() {

	      	vm = new Vue({

			  el: '.generate-image',

			  data: {
				set: {
			  		price: null,
			  		available: null,
			  		available_price: null
			  	},
			  	chosen: null,
			  	unchosen: null
			  },

			  ready: function() {
			  	
			  },

			  methods: {
			  	generateImage: function(){
			  		console.log('generating image');
			  		html2canvas([document.getElementById('donate-overlay-div')], {
					    onrendered: function (canvas) {
					        document.getElementById('canvas').appendChild(canvas);
					        var data = canvas.toDataURL('image/png');
					        console.log(data);
					        // AJAX call to send `data` to a PHP file that creates an image from the dataURI string and saves it to a directory on the server
				            var formData = {
					        	'image'     : data,
					        };
				            jQuery.ajax({
				            	type : 'POST',
				            	url  : baseUrl+'/api/image/upload',
					            data        : formData, 
					            dataType    : 'json', 
					            encode      : true
				            })
				            .success(function(data) {
				            	console.log(data);
					        })
				            .error(function(data) {
				            	console.log(data);
					        });
					        var image = new Image();
					        image.src = data;
					        document.getElementById('image').appendChild(image);
					    }
					});
			  	}
			  }

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
		vm.chosen.splice( index, 1 );
		$(box).css('backgroundColor', 'rgba(0,0,0,0)');
		$(box).css('background-image', 'none');

	} else {

		vm.chosen.push( sid );

	}

	$(box).toggleClass('chosen');
	$(box).toggleClass('available');
}
function toggleBoxAdmin(box){
	var id = $(box).attr('id');
	var sid = id.slice(7);

	if( $(box).hasClass('invisible') ){

		vm.unchosen.push( parseInt( sid ) );

		var index = vm.chosen.indexOf( parseInt( sid ) );

		if( index == -1 ){
			index = vm.chosen.indexOf( sid );
		}

		vm.chosen.splice( index, 1 );

	} else {

		vm.chosen.push( parseInt( sid ) );

		var index = vm.unchosen.indexOf( parseInt( sid ) );

		if( index == -1 ){
			index = vm.unchosen.indexOf( sid );
		}

		vm.unchosen.splice( index, 1 );

	}

	$(box).toggleClass('invisible');
}
function resize(){
	
	$( window ).load(function() {
	  $('.donate-overlay').css('height', $('#donate-img').height() + "px" );
  	  $('.donate-box').removeClass('preload');
	});

}