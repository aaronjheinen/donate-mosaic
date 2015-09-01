// Globals
var vm;
var isDown = false;   // Tracks status of mouse button

(function($) {
  var Donate = {
    // All pages
    'common': {
      init: function() {
		Vue.http.headers.common['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr('content');
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
	            }).error(function (data, status, request) {
	                console.log(data);
	            });
		  		console.log('done uploading');
	        }
		  }

		});

		jQuery('form').submit(function(event) {

			console.log(vm.purchase);

/*
	        var formData = {
	            'name'      : $('input[name=name]').val(),
	            'email'     : $('input[name=email]').val(),
	            'media_id' 	: 
	            'chosen'    : chosen
	        };

	        jQuery.ajax({
	            type        : 'POST', 
	            url         : 'purchase', 
	            data        : formData, 
	            dataType    : 'json', 
	            encode      : true
	        })
	        .done(function(data) {

	            console.log(data); 

	        });
*/

	        event.preventDefault();
	    });
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
	console.log( sid );
	if( $(box).hasClass('chosen') ){

		var index = vm.chosen.indexOf( parseInt( sid ) );

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