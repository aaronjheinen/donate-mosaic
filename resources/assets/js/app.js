// Globals
var chosen = [];
var vm;

(function($) {
  var Donate = {
    // All pages
    'common': {
      init: function() {

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

			window.addEventListener('load', function () {

				var h = $('#donate-img').height() / rows;
				// -1 for border-top
				$('.donate-box').css('height', h +'px');
				console.log(h);

			}, false);
		}
	},
    'user': {
      init: function() {

		$('.donate-box.available').on('click', function(){

			var id = $(this).attr('id');
			var sid = id.slice(7);

			if( $(this).hasClass('chosen') ){

				var index = chosen.indexOf( sid );

			    if(index >= 0){ chosen.splice(index, 1); }

			} else {

				chosen.push( sid );
			}

			$(this).toggleClass('chosen');
		});

		jQuery('form').submit(function(event) {

			console.log('user submitting');

	        var formData = {
	            'name'              : $('input[name=name]').val(),
	            'email'             : $('input[name=email]').val(),
	            'chosen'            : chosen
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
		  },

		  methods: {
		  	getSet: function($id){
	  			this.$http.get('api/sets/' + $id).success(function(set) {
				  this.$set('set', set);
				  this.$set('set.available_price', set.price * set.available);
				  console.log(set);
				}).error(function(error) {
				  console.log(error);
				});
		  	}
		  }

		});
		Vue.config.debug = true;

  		var isDown = false;   // Tracks status of mouse button

		$(document).mousedown(function() {
		    isDown = true;      // When mouse goes down, set isDown to true
		})
		.mouseup(function() {
		    isDown = false;    // When mouse goes up, set isDown to false
		});

  		$('.donate-box').on('click', function(){
  			toggleBoxAdmin(this);
		});

		$(".donate-box").mouseover(function(){
		    if(isDown) {        
		    	toggleBoxAdmin(this);
		    }
		});

      	jQuery('form').submit(function(event) {

			console.log('admin submitting');

	        var formData = {
	            'chosen'            : chosen
	        };

	        jQuery.ajax({
	            type        : 'POST', 
	            url         : 'admin/update', 
	            data        : formData, 
	            dataType    : 'json', 
	            encode      : true
	        })
	        .done(function(data) {

	            console.log(data); 

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

function toggleBoxAdmin(box){
	var id = $(box).attr('id');
	var sid = id.slice(7);

	if( $(box).hasClass('chosen') ){

		var index = chosen.indexOf( sid );

	    if(index >= 0){ chosen.splice(index, 1); }

	    vm.$set('set.available', vm.$get('set').available + 1);

	} else {

		chosen.push( sid );

	    console.log(vm.$get('set').available);
	    vm.$set('set.available', vm.$get('set').available - 1);
	    console.log(vm.$get('set').available);
	}

	$(box).toggleClass('chosen');
}