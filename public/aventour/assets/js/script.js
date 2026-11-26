(function($) {
	
	"use strict";
	
	//Update Header Style and Scroll to Top
	function headerStyle() {
		if($('.main-header').length){
			let windowpos = $(window).scrollTop();
			let siteHeader = $('.main-header');
			let scrollLink = $('.scroll-top');
			if (windowpos >= 110) {
				siteHeader.addClass('fixed-header');
				scrollLink.addClass('open');
			} else {
				siteHeader.removeClass('fixed-header');
				scrollLink.removeClass('open');
			}
		}
	}
	
	headerStyle();


	//Submenu Dropdown Toggle
	if($('.main-header li.dropdown ul').length){
		$('.main-header .navigation li.dropdown').append('<div class="dropdown-btn"><span class="fas fa-angle-down"></span></div>');
		
	}

	//Mobile Nav Hide Show
	if($('.mobile-menu').length){
		
		$('.mobile-menu .menu-box');
		
		let mobileMenuContent = $('.main-header .menu-area .main-menu').html();
		$('.mobile-menu .menu-box .menu-outer').append(mobileMenuContent);
		$('.sticky-header .main-menu').append(mobileMenuContent);
		
		//Dropdown Button
		$('.mobile-menu li.dropdown .dropdown-btn').on('click', function() {
			$(this).toggleClass('open');
			$(this).prev('ul').slideToggle(500);
		});
		//Dropdown Button
		$('.mobile-menu li.dropdown .dropdown-btn').on('click', function() {
			$(this).prev('.megamenu').slideToggle(900);
		});
		//Menu Toggle Btn
		$('.mobile-nav-toggler').on('click', function() {
			$('body').addClass('mobile-menu-visible');
		});

		//Menu Toggle Btn
		$('.mobile-menu .menu-backdrop,.mobile-menu .close-btn').on('click', function() {
			$('body').removeClass('mobile-menu-visible');
		});
	}


	// Scroll to a Specific Div
	if($('.scroll-to-target').length){
		$(".scroll-to-target").on('click', function() {
			let target = $(this).attr('data-target');
		   // animate
		   $('html, body').animate({
			   scrollTop: $(target).offset().top
			 }, 100);
	
		});
	}

	// Elements Animation
	if($('.wow').length){
		let wow = new WOW({
		mobile:       false
		});
		wow.init();
	}

	//Contact Form Validation
	if($('.contact-form').length){
		$('.contact-form').validate({
			rules: {
				username: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				phone: {
					required: true
				},
				message: {
					required: true
				}
			}
		});
	}

	//Fact Counter + Text Count
	if($('.count-box').length){
		$('.count-box').appear(function(){
	
			let $t = $(this),
				n = $t.find(".count-text").attr("data-stop"),
				r = parseInt($t.find(".count-text").attr("data-speed"), 10);
				
			if (!$t.hasClass("counted")) {
				$t.addClass("counted");
				$({
					countNum: $t.find(".count-text").text()
				}).animate({
					countNum: n
				}, {
					duration: r,
					easing: "linear",
					step: function() {
						$t.find(".count-text").text(Math.floor(this.countNum));
					},
					complete: function() {
						$t.find(".count-text").text(this.countNum);
					}
				});
			}
			
		},{accY: 0});
	}


	//LightBox / Fancybox
	if($('.lightbox-image').length) {
		$('.lightbox-image').fancybox({
			openEffect  : 'fade',
			closeEffect : 'fade',
			helpers : {
				media : {}
			}
		});
	}


	//Tabs Box
	if($('.tabs-box').length){
		$('.tabs-box .tab-buttons .tab-btn').on('click', function(e) {
			e.preventDefault();
			let target = $($(this).attr('data-tab'));
			
			if ($(target).is(':visible')){
				return false;
			}else{
				target.parents('.tabs-box').find('.tab-buttons').find('.tab-btn').removeClass('active-btn');
				$(this).addClass('active-btn');
				target.parents('.tabs-box').find('.tabs-content').find('.tab').fadeOut(0);
				target.parents('.tabs-box').find('.tabs-content').find('.tab').removeClass('active-tab');
				$(target).fadeIn(100);
				$(target).addClass('active-tab');
			}
		});
	}



	//Accordion Box
	if($('.accordion-box').length){
		$(".accordion-box").on('click', '.acc-btn', function() {
			
			let outerBox = $(this).parents('.accordion-box');
			let target = $(this).parents('.accordion');
			
			if($(this).hasClass('active')!==true){
				$(outerBox).find('.accordion .acc-btn').removeClass('active');
			}
			
			if ($(this).next('.acc-content').is(':visible')){
				return false;
			}else{
				$(this).addClass('active');
				$(outerBox).children('.accordion').removeClass('active-block');
				$(outerBox).find('.accordion').children('.acc-content').slideUp(300);
				target.addClass('active-block');
				$(this).next('.acc-content').slideDown(300);	
			}
		});	
	}


	if ($('.banner-carousel').length) {
        let bannercarousel = new Swiper('.banner-carousel', {
			direction: 'horizontal',
			effect: "fade",
            slidesPerView: 1,
            spaceBetween: 30,
            speed: 2500,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.banner-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.banner-prev-btn',
                prevEl: '.banner-next-btn',
            },
            breakpoints: {
            1024: {
	                slidesPerView: 1,
	            },
	            800: {
	                slidesPerView: 1,
	            },
	            600: {
	                slidesPerView: 1,
	            },
	            480: {
	                slidesPerView: 1,
	            },
	            320: {
	                slidesPerView: 1,
	            }
	        }
        });
	}


	if ($('.single-item-carousel').length) {
        let singlecolumncarousel = new Swiper('.single-item-carousel', {
            slidesPerView: 1,
            spaceBetween: 30,
            mousewheel: false,
            speed: 500,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.single-column-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.single-column-prev-btn',
                prevEl: '.single-column-next-btn',
            },
            breakpoints: {
            1900: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            },
	            1199: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            },
	            991: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            },
	            767: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            },
	            499: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            }
	        }
        });
	}


	if ($('.two-item-carousel').length) {
        let twocolumncarousel = new Swiper('.two-item-carousel', {
            slidesPerView: 1,
            spaceBetween: 30,
            mousewheel: false,
            speed: 500,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.two-column-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.two-column-prev-btn',
                prevEl: '.two-column-next-btn',
            },
            breakpoints: {
            1900: {
	                slidesPerView: 2,
	                spaceBetween: 30
	            },
	            1199: {
	                slidesPerView: 2,
	                spaceBetween: 30
	            },
	            991: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            },
	            767: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            },
	            499: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            }
	        }
        });
	}


	if ($('.three-item-carousel').length) {
        let threecolumncarousel = new Swiper('.three-item-carousel', {
            slidesPerView: 1,
            spaceBetween: 30,
            mousewheel: false,
            speed: 500,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.three-item-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.three-item-prev-btn',
                prevEl: '.three-item-next-btn',
            },
            breakpoints: {
            1900: {
	                slidesPerView: 3,
	                spaceBetween: 30
	            },
	            1199: {
	                slidesPerView: 3,
	                spaceBetween: 30
	            },
	            991: {
	                slidesPerView: 2,
	                spaceBetween: 30
	            },
	            767: {
	                slidesPerView: 2,
	                spaceBetween: 30
	            },
	            499: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            }
	        }
        });
	}


	if ($('.four-item-carousel').length) {
        let fourcolumncarousel = new Swiper('.four-item-carousel', {
            slidesPerView: 1,
            spaceBetween: 30,
            mousewheel: false,
            speed: 500,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.four-column-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.four-column-prev-btn',
                prevEl: '.four-column-next-btn',
            },
            breakpoints: {
            1900: {
	                slidesPerView: 4,
	                spaceBetween: 30
	            },
	            1199: {
	                slidesPerView: 4,
	                spaceBetween: 30
	            },
	            991: {
	                slidesPerView: 3,
	                spaceBetween: 30
	            },
	            767: {
	                slidesPerView: 2,
	                spaceBetween: 30
	            },
	            499: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            }
	        }
        });
	}


	if ($('.six-item-carousel').length) {
        let chooseuscarousel = new Swiper('.six-item-carousel', {
            slidesPerView: 1,
            spaceBetween: 30,
            mousewheel: false,
            speed: 500,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: '.six-item-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.six-item-prev-btn',
                prevEl: '.six-item-next-btn',
            },
            breakpoints: {
            1900: {
	                slidesPerView: 6,
	                spaceBetween: 30
	            },
	            1199: {
	                slidesPerView: 4,
	                spaceBetween: 30
	            },
	            991: {
	                slidesPerView: 3,
	                spaceBetween: 30
	            },
	            767: {
	                slidesPerView: 2,
	                spaceBetween: 30
	            },
	            499: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            }
	        }
        });
	}


	if ($('.brand-carousel').length) {
        let brandcarousel = new Swiper('.brand-carousel', {
            slidesPerView: 1,
            spaceBetween: 30,
            mousewheel: false,
            speed: 500,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            loop: true,
            autoplay: {
                delay: 5000,
            },
            breakpoints: {
            1900: {
	                slidesPerView: 8,
	                spaceBetween: 30
	            },
	            1199: {
	                slidesPerView: 6,
	                spaceBetween: 30
	            },
	            991: {
	                slidesPerView: 4,
	                spaceBetween: 30
	            },
	            767: {
	                slidesPerView: 2,
	                spaceBetween: 30
	            },
	            499: {
	                slidesPerView: 1,
	                spaceBetween: 30
	            }
	        }
        });
	}



	//Sortable Masonary with Filters
	function enableMasonry() {
		if($('.sortable-masonry').length){
	
			let winDow = $(window);
			// Needed letiables
			let $container=$('.sortable-masonry .items-container');
			let $filter=$('.filter-btns');
	
			$container.isotope({
				filter:'*',
				 masonry: {
					columnWidth : '.masonry-item.small-column'
				 },
				animationOptions:{
					duration:500,
					easing:'linear'
				}
			});
			
	
			// Isotope Filter 
			$filter.find('li').on('click', function(){
				let selector = $(this).attr('data-filter');
	
				try {
					$container.isotope({ 
						filter	: selector,
						animationOptions: {
							duration: 500,
							easing	: 'linear',
							queue	: false
						}
					});
				} catch(err) {
	
				}
				return false;
			});
	
	
			winDow.on('resize', function(){
				let selector = $filter.find('li.active').attr('data-filter');

				$container.isotope({ 
					filter	: selector,
					animationOptions: {
						duration: 500,
						easing	: 'linear',
						queue	: false
					}
				});
			});
	
	
			let filterItemA	= $('.filter-btns li');
	
			filterItemA.on('click', function(){
				let $this = $(this);
				if ( !$this.hasClass('active')) {
					filterItemA.removeClass('active');
					$this.addClass('active');
				}
			});
		}
	}


    // Progress Bar
	if ($('.count-bar').length) {
		$('.count-bar').appear(function(){
			let el = $(this);
			let percent = el.data('percent');
			$(el).css('width',percent).addClass('counted');
		},{accY: -50});

	}


	//Search Popup
	if($('#search-popup').length){
		
		//Show Popup
		$('.search-toggler').on('click', function() {
			$('#search-popup').addClass('popup-visible');
		});
		$(document).keydown(function(e){
	        if(e.keyCode === 27) {
	            $('#search-popup').removeClass('popup-visible');
	        }
	    });
		//Hide Popup
		$('.close-search,.search-popup .overlay-layer').on('click', function() {
			$('#search-popup').removeClass('popup-visible');
		});
	}


	//nice select
	$(document).ready(function() {
      $('select:not(.ignore)').niceSelect();
    });


	//odometer
	if ($(".odometer").length) {
	    let odo = $(".odometer");
	    odo.each(function () {
	      $(this).appear(function () {
	        let countNumber = $(this).attr("data-count");
	        $(this).html(countNumber);
	      });
	    });
	}


	// sidebar-popup
	if($('#sidebar-popup').length){
		
		//Show Popup
		$('.sidebar-toggler').on('click', function() {
			$('#sidebar-popup').addClass('popup-visible');
		});
		$(document).keydown(function(e){
	        if(e.keyCode === 27) {
	            $('#sidebar-popup').removeClass('popup-visible');
	        }
	    });
		//Hide Popup
		$('.close-popup,.sidebar-popup .overlay-layer').on('click', function() {
			$('#sidebar-popup').removeClass('popup-visible');
		});
	}


	{let t=document.querySelectorAll(".moving-item");0<t.length&&t[0].classList.add("active"),t.forEach(e=>{e.addEventListener("mouseenter",function(){t.forEach(e=>e.classList.remove("active")),this.classList.add("active")})})}


	// Helper function to safely initialize CircleType on an element
	function initCurvedText(selector, options = {}) {
	const element = document.getElementById(selector);
	if (element) {
		const circle = new CircleType(element);
	
		// Apply options
		if (options.radius !== undefined) circle.radius(options.radius);
		if (options.direction !== undefined) circle.dir(options.direction);
	} else {
		console.warn(`Element with ID "${selector}" not found.`);
	}
	}
	
	// Initialize curved text styles
	initCurvedText('curve_text_1', { radius: 90 });
 
 
	// Date picker
	function datepicker () {
	    if ($('#datepicker').length) {
	        $('#datepicker').datepicker();
	    };
	}


	if($('.timer').length){
	   $(function(){
		    $('[data-countdown]').each(function() {
		   let $this = $(this), finalDate = $(this).data('countdown');
		   $this.countdown(finalDate, function(event) {
		     $this.html(event.strftime('%D days %H:%M:%S'));
		   });
		 });
		});

	   $('.cs-countdown').countdown('').on('update.countdown', function(event) {
		  let $this = $(this).html(event.strftime('<div class="count-col"><span>%D</span><h6>days</h6></div> <div class="count-col"><span>%H</span><h6>Hrs</h6></div> <div class="count-col"><span>%M</span><h6>Mins</h6></div> <div class="count-col"><span>%S</span><h6>Secs</h6></div>'));
		});
	}


	//Price Range Slider
	if($('.price-range-slider').length){
		$( ".price-range-slider" ).slider({
			range: true,
			min: 499,
			max: 5000,
			values: [ 499, 2000 ],
			slide: function( event, ui ) {
			$( "input.property-amount" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			}
		});
		
		$( "input.property-amount" ).val( $( ".price-range-slider" ).slider( "values", 0 ) + " - $" + $( ".price-range-slider" ).slider( "values", 1 ) );	
	}


    

	/*	=========================================================================
	When document is Scrollig, do
	========================================================================== */




	/* ==========================================================================
   When document is Scrollig, do
   ========================================================================== */
	
	$(window).on('scroll', function() {
		headerStyle();
	});

	
	
	/* ==========================================================================
   When document is loaded, do
   ========================================================================== */
	
	$(window).on('load', function() {
		enableMasonry();
		datepicker ();
	});

	

})(window.jQuery);